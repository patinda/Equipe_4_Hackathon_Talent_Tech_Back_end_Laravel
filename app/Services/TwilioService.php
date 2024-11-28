<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Http\CurlClient;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $phoneNumber = env('TWILIO_PHONE_NUMBER');

        // Vérifiez que toutes les informations d'identification sont configurées
        if (!$sid || !$authToken || !$phoneNumber) {
            throw new \Exception('Twilio credentials are not configured properly in .env');
        }

        // Créez un client HTTP personnalisé pour désactiver la vérification SSL
        $httpClient = new CurlClient([
            CURLOPT_SSL_VERIFYPEER => false, // Désactive la vérification SSL
        ]);

        // Instanciez le client Twilio en configurant le client HTTP personnalisé
        $this->client = new Client($sid, $authToken);
        $this->client->setHttpClient($httpClient); // Définissez le client HTTP personnalisé
    }

    public function sendSms($to, $message)
    {
        return $this->client->messages->create($to, [
            'from' => env('TWILIO_PHONE_NUMBER'),
            'body' => $message,
        ]);
    }
}
