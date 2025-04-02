<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AramexService
{
    protected $client;
    protected $baseUrl;


    public function __construct()
    {
        $this->client = new Client();
        // This should be just the base part of the URL
        $this->baseUrl = env('ARAMEX_API_BASE_URL', 'https://ws.aramex.net/ShippingAPI.V2');
    }

    public function sendRequest($endpoint, $payload)
    {
        try {
            $response = $this->client->post("{$this->baseUrl}/{$endpoint}", [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => $this->addClientInfo($payload),
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ];
        }
    }

    public function trackShipment($shipmentId)
    {
        $endpoint = 'Tracking/Service_1_0.svc/json/TrackShipments';
        $payload = [
            'ClientInfo' => [
                'UserName' => env('ARAMEX_API_USERNAME'),
                'Password' => env('ARAMEX_API_PASSWORD'),
                'Version' => env('ARAMEX_API_VERSION'),
                'AccountNumber' => env('ARAMEX_ACCOUNT_NUMBER'),
                'AccountPin' => env('ARAMEX_ACCOUNT_PIN'),
                'AccountEntity' => env('ARAMEX_ACCOUNT_ENTITY'),
                'AccountCountryCode' => env('ARAMEX_ACCOUNT_COUNTRY_CODE'),
                'Source' => env('ARAMEX_SOURCE'),
            ],
            'Transaction' => [
                'Reference1' => '',
                'Reference2' => '',
                'Reference3' => '',
                'Reference4' => '',
                'Reference5' => ''
            ],
            'GetLastTrackingUpdateOnly' => false,
            'Shipments' => [$shipmentId],
        ];
        return $this->sendRequest($endpoint, $payload);
    }
    private function addClientInfo($payload)
    {
        $payload['ClientInfo'] = [
            'UserName' => env('ARAMEX_API_USERNAME'),
            'Password' => env('ARAMEX_API_PASSWORD'),
            'Version' => env('ARAMEX_API_VERSION'),
            'AccountNumber' => env('ARAMEX_ACCOUNT_NUMBER'),
            'AccountPin' => env('ARAMEX_ACCOUNT_PIN'),
            'AccountEntity' => env('ARAMEX_ACCOUNT_ENTITY'),
            'AccountCountryCode' => env('ARAMEX_ACCOUNT_COUNTRY_CODE'),
            'Source' => env('ARAMEX_SOURCE'),
        ];

        return $payload;
    }
}
