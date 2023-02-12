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
     * @param App\Models\NewsSearchResults $newsModel
     * 
     * @return void
     */
    public function __construct(NewsSearchResults $newsModel)
    {
        $this->newsModel   = $newsModel;
    }

    /**
     * @param Illuminate\Http\Request $request
     * 
     * @return void
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
                
                $dateFormat = Carbon::parse($item->webPublicationDate)->format('d/m/Y');

                $list[] = [
                    'content_ref_id' => $item->id,
                    'title'          => $item->webTitle,
                    'article_url'    => $item->webUrl,
                    'published_date' => $dateFormat
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
     * @return void
     */
    public function getPinnedArticles()
    {       
        $result = $this->newsModel::where('is_pinned', true)
                    ->get()
                    ->toArray();

        return view('news')->with('pinned_results', $result);
    }

    /**
     * Handles one request at a time | *TODO: Update with bulk pinned articles
     * 
     * @param Illuminate\Http\Request $request
     * 
     * //Currently working on it
     * 
     * @return void
     */
    public function storePinnedArticles(Request $request)
    {
        $publishedDate = ($request->input('published_date')) ? Carbon::parse($request->input('published_date'))->format('d-m-Y') : null;
        
        $data = [
            'content_ref_id' => $request->input('content_ref_id'),
            'title'          => $request->input('title'),
            'article_url'    => $request->input('article_url'),
            'published_date' => $publishedDate,
        ];

        //TODO: add error handling
        $this->newsModel::create($data)->refresh();
    }
}