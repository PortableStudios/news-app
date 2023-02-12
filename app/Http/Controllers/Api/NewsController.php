<?php
  
namespace App\Http\Controllers\Api;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\NewsSearchResults;
use Carbon\Carbon;

class NewsController extends Controller
{
    /**
     * Illuminate\Http\Request $request
     * 
     * return view
     */
    public function search(Request $request)
    {       
        $response = Http::get(config('services.guardian.apiurl'), [
            'q'       => ($request->input('query')) ? $request->input('query') : '',
            'page'    => 1, //remove/refactor to get more pages | pagination
            'api-key' => config('services.guardian.key')
        ]);
    
        foreach (json_decode($response) as $result) {

            if (strtolower($result->status) !== "ok") {
                throw new \ErrorException('Error: Please contact admin for more details with status: ' . $result->status);
            }

            $results = $result->results;
            $list = [];

            foreach($results as $item) {
                
                //Date format - 2022-10-26T15:57:39Z to be updated with d/m/Y
                $dateFormat = date('d/m/Y', strtotime($item->webPublicationDate)); 
                
                //TODO: Use Carbon
                //$dateFormat = Carbon::createFromFormat("Y-m-dTH:i:sZ", $item->webPublicationDate)->format('d/m/Y');

                $list[] = [
                    'content_ref_id' => $item->id,
                    'title'          => $item->webTitle,
                    'article_url'    => $item->webUrl,
                    'published_date' => $dateFormat//$item->webPublicationDate, //TODO: Convert dateformat
                ];

                //Enhancement: To Use with Model and return collection

                /*$newsSearchResults = new NewsSearchResults;
                $newsSearchResults->fill([
                    'content_ref_id' => $item->id,
                    'title'          => $item->webTitle,
                    'article_url'    => $item->webUrl,
                    'published_date' => $item->webPublicationDate,
                ]);
                $list[] = $newsSearchResults;*/

            }

            //Enhancement: Response as a Collection
            //$res = collect($list);

            return view('news')->with('results', $list);
        }
    }

     /**
     * return view
     */
    public function getPinnedArticles()
    {       
        $newsSearchResults = new NewsSearchResults;

        $result = $newsSearchResults::where('is_pinned', true)
                    ->get()
                    ->toArray();

        return view('news')->with('pinned_results', $result);
    }

    /**
     * Handles one request at a time
     * Illuminate\Http\Request $request
     * TODO: Update with bulk pinned articles
     * 
     * return view
     */
    public function storePinnedArticles(Request $request)
    {
        $newsSearchResults = new NewsSearchResults;


        $publishedDate = ($request->input('published_date')) ? Carbon::parse($request->input('published_date'))->format('d-m-Y') : null;
        
        $data = [
            'content_ref_id' => $request->input('content_ref_id'),
            'title'          => $request->input('title'),
            'article_url'    => $request->input('article_url'),
            'published_date' => $publishedDate,
        ];

        //TODO: add error handling
        $newsSearchResults::create($data)->refresh();


    }
}