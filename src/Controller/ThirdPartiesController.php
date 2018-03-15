<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Response;
use Cake\Network\Http\Client;
use Cake\Cache\Cache;
use Cake\I18n\Time;

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

        // We'll store our tokens for "LeDénicheur"'s API for 20 hours !
        Cache::config('short', [
            'className' => 'File',
            'duration'  => '+20 hours',
            'path'      => CACHE,
            'prefix'    => 'cake_short_'
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
        if(isset($user))
        {
            return true;
        }

        return parent::isAuthorized($user);
    }

    public function searchProducts()
    {
        $query = $this->request->getQuery('q');
        // No query ? Just die bro'
        if(!$query)
        {
            die();
        }

        $user = $this->Auth->user();

        // Is the user French ? Let's use the "LeDénicheur"'s API !
        if($user->preferredStore === 'FR')
        {
            // API endpoint
            $APIBaseURL = Configure::read('Credentials.LeDenicheur.endpoint');

            // Is the token still cached ?
            $token = Cache::read('tokenLeDenicheur');
            if($token === false)
            {
                // If not, let's retrieve a new one !
                $response = (new Client())->post($APIBaseURL . 'auth/token', [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => Configure::read('Credentials.LeDenicheur.id'),
                    'client_secret' => Configure::read('Credentials.LeDenicheur.secret'),
                    'audience'      => $APIBaseURL
                ]);

                $token = [
                    'token'  => $response->json['access_token'],
                    'cached' => Time::now()
                ];

                Cache::write('tokenLeDenicheur', $token);
            }

            // Okay, so we got a token ! Let's search for this query !
            $response = (new Client())->post($APIBaseURL . 'search', [
                'modes'        => 'products',
                'limit'        => 16,
                'query'        => urlencode($this->_cleanString($query)),
                'suggestions'  => false,
                'access_token' => $token['token']
            ]);

            // The resulted products will be stored there !
            $results['products'] = [];

            // Let's build a cool object with these data
            if(isset($response->json['resources']))
            {
                foreach($response->json['resources']['products']['items'] as $product => $value)
                {
                    array_push($results['products'], [
                        'title' => rawurlencode($value['name']),
                        'href'  => $value['web_uri'],
                        'src'   => $value['media']['product_images']['first'][280]
                    ]);
                }
            }
        }

        else
        {
            // Amazon goes here !
        }

        return new Response([
            'status' => 200,
            'type' => 'json',
            'body' => json_encode($results)
        ]);
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
