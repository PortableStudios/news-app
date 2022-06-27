<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\NewsController;
use GuzzleHttp\Exception\RequestException;

class GuardianController extends NewsController
{
    public static function updateFromSource() {
        $client = new Client();
        $key = env('GUARDIAN_KEY', false);
        $uri = new Uri('https://content.guardianapis.com/search');

        if (!$key) {
            Log::error('INVALID KEY', 'Guardian api key was invalid');

            return false;
        }

        try {
            $params =  [
                'api-key' => $key,
                'format' => 'json'
            ];

            $request = new Request(
                'GET',
                $uri->withQuery(Query::build($params))
            );

            $response = $client->send($request);
            Log::info($response->getBody());

            if ($response && $response->getStatusCode() === 200) {
                $result = $response->getBody()->getContents();

                echo var_dump($response);
            }

            // $response = json_decode($response, true);
        } catch (RequestException $e) {
            Log::error($e->getMessage());
        }
    }
}
