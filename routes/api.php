<?php

use App\Http\Controllers\LeagueController;
use App\Http\Controllers\WordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/get-league',[LeagueController::class, 'getLeague']);

Route::post('/word', [WordController::class, 'createWord']);
Route::get('/get-dates', [WordController::class, 'getDates']);
Route::get('/get-words-with-date', [WordController::class, 'getWWDate']);
Route::get('/get-rand-words', [WordController::class, 'getRandWord']);
Route::get('/get-words-random', [WordController::class, 'getWordsRandom']);
Route::get('/get-all-words', [WordController::class, 'getAllWords']);
Route::post('/search-word', [WordController::class, 'searchWord']);
Route::post('/edit-word', [WordController::class, 'editWord']);
Route::delete('/delete-word', [WordController::class, 'deleteWord']);

Route::post('/search-word-with-elastic', [\App\Http\Controllers\WordController::class, 'searchWordElastic']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
