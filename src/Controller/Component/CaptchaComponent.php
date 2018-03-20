<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Network\Http\Client;

class CaptchaComponent extends Component
{
    public function validation($data)
    {
        // The submitted form does not have any Google's response ?
        if(!isset($data['g-recaptcha-response']))
        {
            return false;
        }

        // Is this user authorized by Google invisible CAPTCHA ?
        $response = (new Client())->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => Configure::read('Credentials.Google.CAPTCHA.secret'),
            'response' => $data['g-recaptcha-response']
        ]);

        if(!$response or !isset($response->json['success']) or !$response->json['success'])
        {
            return false;
        }

        else
        {
            return true;
        }
    }
}
