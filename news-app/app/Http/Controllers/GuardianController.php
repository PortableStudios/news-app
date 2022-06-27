<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\Article;
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
        $key = config('news.keys.GUARDIAN_KEY', false);
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
                $result = $response->getBody();
                $guardianResult = json_decode($result);
                $articles = $guardianResult->response->results;

                foreach ($articles as $article) {
                    try {
                        $newArticle = new Article(
                            [
                                'title' => $article->webTitle,
                                'sectionID' => $article->sectionId,
                                'sectionTitle' => $article->sectionName,
                                'webURL' => $article->webUrl,
                                'publicationDate' => $article->webPublicationDate
                            ]
                        );

                        $newArticle->save();
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                    }
                }
            }
        } catch (RequestException $e) {
            Log::error($e->getMessage());
        }
    }
}
