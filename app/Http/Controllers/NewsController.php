<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\News\Repositories\Interfaces\NewsRepositoryInterface;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $data = $this->newsService->search();
        $bookmark = Bookmark::all();

        return Inertia::render('News/Index', [
            'data' => $data,
            'bookmark' => $bookmark,
            'status' => 'success',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search(Request $request)
    {
        // @TODO Sanitize the request data
        $searchQuery = $request->q? $request->q: '';
        $data = $this->newsService->search($searchQuery);

        return response()->json(compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function bookmark(Request $request)
    {
        // @TODO backend validation
        $data = $request->all();
        // @TODO need to check if bookmark already exists. per user which is not done here.
        $bookmark = Bookmark::create($data);
        return response()->json(compact('bookmark'));
    }

}
