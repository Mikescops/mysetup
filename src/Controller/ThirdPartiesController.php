<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Response;
use Cake\Http\Client;
use Cake\Cache\Cache;
use ApaiIO\ApaiIO;
use ApaiIO\Operations\Search;
use ApaiIO\Request\GuzzleRequest;
use ApaiIO\Configuration\GenericConfiguration;
use App\Utility\SentryLogger;
use App\Errors\UsageError;

/**
 * ThirdParties Controller
 *
 * This controller will contain some logic... Sorry about that :S
 * Shortly, on mySetup.co we need to communicate with some third parties to retrieve data (as product results during Setups.add...).
 *
 * @method \App\Model\Entity\ThirdParty[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThirdPartiesController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        // We'll store some data used within this Model for 20 hours !
        Cache::setConfig('ThirdPartiesCacheConfig', [
            'className' => 'Cake\Cache\Engine\ApcuEngine',
            'duration'  => '+20 hours',
            'prefix'    => 'token_'
        ]);
    }

    /* /!\ Each method present in this very file will be authorized /!\ */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow();
    }

    public function isAuthorized($user)
    {
        // Only logged in users will be able to run search queries.
        if (isset($user)) {
            return true;
        }

        return parent::isAuthorized($user);
    }

    /*
     * The parameters of this method will NEVER BE PASSED THROUGH ROUTING.
     * They exist only for "internal" logic purpose (search for "store redirection" on this lint).
     */
    public function searchProducts($query = null, $store_redirection = null)
    {
        // Limit HTTP actions possible here
        if (!$this->request->is('get') && !$this->request->is('ajax')) {
            die();
        }

        $query = $this->request->getQuery('q', $query);
        // No query ? Just die bro'
        if (!$query) {
            die();
        }

        $user = $this->Auth->user();
        // User not connected ? Just die bro'
        if ($user === null) {
            $this->Flash->error(__('You are not authorized to access that location.'));
            return $this->redirect('/');
        }

        try {

            // This test handles the "store redirection" feature.
            if ($store_redirection === null) {
                // If no lang is set, we'll use the user's language to address the best store
                $store = strtoupper($this->request->getQuery('lang', $user->preferredStore));
            } else {
                // If the back-end specified a store to search on, let's set it here.
                $store = $store_redirection;
            }

            // Is the user French ? Let's use the "LeDénicheur"'s API !
            if ($store) {
                // API endpoint
                $APIBaseURL = Configure::read('Credentials.LeDenicheur.endpoint');

                // Is the token still cached ?
                $token = Cache::read('LeDenicheur', 'ThirdPartiesCacheConfig');
                if ($token === false) {
                    // If not, let's retrieve a new one !
                    $response = (new Client())->post($APIBaseURL . 'auth/token', [
                        'grant_type'    => 'client_credentials',
                        'client_id'     => Configure::read('Credentials.LeDenicheur.id'),
                        'client_secret' => Configure::read('Credentials.LeDenicheur.secret'),
                        'audience'      => $APIBaseURL
                    ]);

                    $token = $response->getJson()['access_token'];

                    Cache::write('LeDenicheur', $token, 'ThirdPartiesCacheConfig');
                }

                // Okay, so we got a token ! Let's search for this query !
                $response = (new Client())->post($APIBaseURL . 'search', [
                    'modes'        => 'products',
                    'limit'        => 16,
                    'query'        => urlencode($this->_cleanString($query)),
                    'suggestions'  => false,
                    'access_token' => $token
                ]);

                // The resulted products will be stored there !
                $results['products'] = [];

                // Let's build a cool object with these data
                $json_response = $response->getJson();
                if (isset($json_response['error'])) {
                    throw new UsageError('Fail getting product API Auth access.');
                }
                if (isset($json_response['resources'])) {
                    foreach ($json_response['resources']['products']['items'] as $product => $value) {
                        array_push($results['products'], [
                            'title' => rawurlencode($value['name']),
                            'href'  => $value['web_uri'],
                            'src'   => $value['media']['product_images']['first'][280]
                        ]);
                    }
                }

                /* SPECIAL TREATMENT FOR "NO RESULT OUTPUT" */
                if (!count($results['products'])) {
                    // If the research gave no result with LeDenicheur API, we "redirect" the query to the US Amazon store.
                    // We should not be redirecting the calling procedure on a public method, but its logic allows us to take some risks :)
                    // return $this->searchProducts($query, 'US');
                }
            } else {
                // Prepares a configuration object to communicate with Amazon stores
                // The statement below will voluntary fail with an error 500 on the production if the `lang` parameter specified does not exist.
                $conf = (new GenericConfiguration())
                    ->setCountry(
                        Configure::read('Credentials.Amazon.Stores.' . $store . '.country')
                    )
                    ->setAccessKey(
                        Configure::read('Credentials.Amazon.Stores.' . $store . '.access')
                    )
                    ->setSecretKey(
                        Configure::read('Credentials.Amazon.Stores.' . $store . '.secret')
                    )
                    ->setAssociateTag(
                        Configure::read('Credentials.Amazon.Stores.' . $store . '.associate_tag')
                    )
                    ->setRequest((new GuzzleRequest((new \GuzzleHttp\Client()))));

                // Our query will be set in another object here
                $search = (new Search())
                    ->setCategory('All')
                    ->setKeywords($query)
                    ->setResponsegroup(['Small', 'Images']);

                // What a weird way to convert XML response to a PHP object to iterate on !
                $response = json_decode(
                    json_encode(
                        simplexml_load_string(
                            (new ApaiIO($conf))->runOperation($search)
                        )
                    ),
                    true
                );

                // The resulted products will be stored there !
                $results['products'] = [];

                // Let's build a cool object with these data
                if (isset($response['Items']['Item'])) {
                    foreach ($response['Items']['Item'] as $product => $value) {
                        // Sometimes the Amazon API does not set the image directly within the first object...
                        if (isset($value['MediumImage'])) {
                            $src = $value['MediumImage']['URL'];
                        } elseif (isset($value['ImageSets']['ImageSet']['MediumImage'])) {
                            $src = $value['ImageSets']['ImageSet']['MediumImage']['URL'];
                        } elseif (isset($value['ImageSets']['ImageSet'][0]['MediumImage'])) {
                            $src = $value['ImageSets']['ImageSet'][0]['MediumImage']['URL'];
                        } else {
                            // Well well... This item looks not having any image for us :-\
                            // We drop it and jump directly to the next iteration thus.
                            continue;
                        }

                        array_push($results['products'], [
                            'title' => rawUrlEncode($value['ItemAttributes']['Title']),
                            'href'  => $value['DetailPageURL'],
                            'src'   => $src
                        ]);
                    }
                }
            }

            return new Response([
                'type' => 'json',
                'body' => json_encode($results)
            ]);
        } catch (\Throwable $exception) {
            (new SentryLogger())->logErrorWithUser($exception, $this->Auth);

            return new Response([
                'type' => 'json',
                'body' => json_encode([
                    'error' => [
                        'message' => 'Fail to fetch products',
                        'code' => 'FAIL_FETCHING_PRODUCTS'
                    ]
                ])
            ]);
        }
    }

    /* `protected` functions used in `public` ones */
    protected function _cleanString($string)
    {
        // We get rid of these characters :
        // * uppercases
        // * ; / ? : @ & = + $ , . ! ~ * ( )
        // * multiple spaces and underscores
        $string = strtolower($string);
        $string = preg_replace("/[^a-z0-9_'\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", " ", $string);
        return $string;
    }
}
