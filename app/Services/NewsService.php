<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class NewsService
{
    protected $client;
    protected $query;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.news_api.url'),
            'timeout' => 5
        ]);
        $this->query = [
            'api-key' => config('services.news_api.api_key'),
            'show-fields' => 'thumbnail,short-url',
            'page-size' => 15
        ];
    }

    public function search(string $q = ''): array
    {
        try {
            if(!empty($q)) {
                $this->query['q'] = $q;
            }
            $query = [
                'query' => $this->query
            ];
            $response = $this->client->get('search', $query);
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['response'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}
