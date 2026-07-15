<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class AramexService
{
     protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client([
            'timeout'         => 25,
            'connect_timeout' => 5,
        ]);
        $this->baseUrl = env('ARAMEX_API_BASE_URL', 'https://ws.aramex.net/ShippingAPI.V2');
    }

    public function sendRequest($endpoint, $payload)
    {
        try {
            $response = $this->client->post("{$this->baseUrl}/{$endpoint}", [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => $this->addClientInfo($payload),
            ]);

            $decoded = json_decode($response->getBody()->getContents(), true);

            if (!is_array($decoded)) {
                \Log::error('Aramex API returned a non-JSON/unparseable body', [
                    'endpoint' => $endpoint,
                    'body'     => $response->getBody()->getContents(),
                ]);
                return [
                    'error'    => 'Réponse Aramex invalide ou non-JSON.',
                    'response' => null,
                ];
            }

            return $decoded;
        } catch (ConnectException $e) {
            \Log::error('Aramex connection failed', [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage(),
            ]);
            return [
                'error'    => $e->getMessage(),
                'response' => null,
            ];
        } catch (RequestException $e) {
            \Log::error('Aramex request failed', [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage(),
            ]);
            return [
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ];
        }
    }

    public function trackShipment(array $payload)
    {
        $endpoint = '/Tracking/Service_1_0.svc/json/TrackShipments';
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

    public function createPickup(array $payload)
    {
        return $this->sendRequest('/Shipping/Service_1_0.svc/json/CreatePickup', $payload);
    }

    public function cancelPickup(string $pickupGuid, string $comments = ''): array
    {
        $clientInfo = $this->getClientInfo()['ClientInfo'];

        $payload = [
            'ClientInfo'  => $clientInfo,
            'Comments'    => $comments,
            'PickupGUID'  => $pickupGuid,
            'Transaction' => [
                'Reference1' => '',
                'Reference2' => '',
                'Reference3' => '',
                'Reference4' => '',
                'Reference5' => '',
            ],
        ];

        return $this->sendRequest('/Shipping/Service_1_0.svc/json/CancelPickup', $payload);
    }

    public function getClientInfo(): array
    {
        return [
            'ClientInfo' => [
                'UserName' => env('ARAMEX_API_USERNAME'),
                'Password' => env('ARAMEX_API_PASSWORD'),
                'Version' => env('ARAMEX_API_VERSION'),
                'AccountNumber' => env('ARAMEX_ACCOUNT_NUMBER'),
                'AccountPin' => env('ARAMEX_ACCOUNT_PIN'),
                'AccountEntity' => env('ARAMEX_ACCOUNT_ENTITY'),
                'AccountCountryCode' => env('ARAMEX_ACCOUNT_COUNTRY_CODE'),
                'Source' => env('ARAMEX_SOURCE'),
            ]
        ];
    }
}
