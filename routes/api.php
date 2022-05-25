<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// the Ajax API endpoint querying the NewsProvider class for the string entered
Use App\Models\Newsprovider;
    Route::get('news', function(Request $request) {
        return Newsprovider::getAllResultsForString($request);
});



