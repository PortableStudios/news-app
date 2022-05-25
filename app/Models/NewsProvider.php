<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;



class NewsProvider extends Model
{
    use HasFactory;


    // takes the search string and runs it against every news source
    // collates a multidimensional array of articles within providers
    static function getAllResultsForString($req)
    {
        $return = [];
        $str = Request::get('search'); // $_GET the search var
        $providers = self::getAllProviders();

        $n = 0;
        foreach ($providers as $p) {

            $url = $p['url'] . $p['uri'] . urlencode(trim($str)) . "&api-key=" . $p['api-key'];
            $responseBody = self::getCached($url); // returns either from the local file-system or goes to the news Provider API depending on expiry time set

            $return[$n] = self::remapKeys($responseBody->response->results); // not all providers use the same format, but we need one format for all

            $return[$n]['status'] = 'ok'; // TODO - checks
            $return[$n]['name'] = $p['name']; // add the news provider's name
            $n++;
        }

        return $return;
    }


    static private function remapKeys($r)
    {
        // TODO - once other providers are added they may not use the same keys, date-formats, etc. This needs to be taken care of (here)
        return $r;
    }

    static private function getAllProviders()
    {
        // TODO - add more providers & auth methods
        $providers[0] = array('name' => 'the Guardian', "url" => "https://content.guardianapis.com/", "api-key" => "904792db-954a-45e5-bf9b-02e042c582d2", "uri" => "search?q=");

        // $providers[1] = array('name' => 'Testing Provider TWO ', "url" => "https://content.guardianapis.com/", "api-key" => "904792db-954a-45e5-bf9b-02e042c582d2", "uri" => "search?q=");

        return $providers;
    }



    static private function getCached($url)
    {
        // creates a json file in the spec'd directory and a ts file to check when it's time to update
        // we could have written the expiry date into the JSON structure, but doing it this way it also works with other files

        $clearCacheAfterMinutes = 5;
        $dir = 'Cache_news';    // name of cache directory

        $cacheFile      = $dir . '/' . md5($url) . '.json';
        $cacheFile_ts   = $dir . '/' . md5($url) . '.timestamp';

        if (Storage::exists($cacheFile_ts)) { // is there a cached file?

            $ts = Storage::get($cacheFile_ts);
            $timeDiff = time() - $ts;

            //  echo time(). ' - '.$ts. '='.  $timeDiff. ' <br> '.$clearCacheAfterMinutes*60; die();

            if ($timeDiff > $clearCacheAfterMinutes * 60) { // time to update the cache?
                $client = new \GuzzleHttp\Client();
                $response = $client->request('GET', $url);
                $responseBody = json_decode($response->getBody());
                Storage::put($cacheFile, (string)json_encode($responseBody));
                Storage::put($cacheFile_ts, time());
            }

        } else { // no cached file, create one

            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);
            $responseBody = json_decode($response->getBody());
            Storage::put($cacheFile, (string)json_encode($responseBody));
            Storage::put($cacheFile_ts, time());
        }
        return json_decode(Storage::get($cacheFile));
    }
}
