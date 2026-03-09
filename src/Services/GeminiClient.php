<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeminiClient
{
    private $client;
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        $this->client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/',
            'timeout' => 100
        ]);
    }

    public function generateText($prompt)
    {
        try {
            $response = $this->client->post(
                "v1beta/models/gemini-2.5-flash:generateContent",
                [
                    'headers' => [
                        'x-goog-api-key' => $this->apiKey,
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        "contents" => [
                            [
                                "parts" => [
                                    [
                                        "text" => $prompt
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            );
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $error = json_decode($response->getBody(), true);

            if ($statusCode === 429 || (isset($error['error']['status']) && $error['error']['status'] === 'RESOURCE_EXHAUSTED')) {
                return [
                    "error" => "The AI is currently busy (Quota Exceeded). Please try again in a few seconds."
                ];
            }

            return [
                "error" => $error
            ];
        } catch (\GuzzleHttp\Exception\ConnectException $e) {

            return [
                "error" => "connection issues"
            ];
        }
    }
}
