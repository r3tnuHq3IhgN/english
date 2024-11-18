<?php

use App\PaymentService\PaypalApi;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('layouts.app');
    });
    Route::get('/english', function () {
        return view('english');
    });
    Route::get('/game', function () {
        return view('game');
    });
    Route::get('/image', function () {
        return view('image');
    });
    Route::get('/search', function () {
        return view('search');
    });
    Route::get('/shoe', function () {
        return view('shoe');
    });
});

Route::get('/test', function () {
//    $user1 = new \App\Models\User();
//    $user2 = new \App\Models\User();
//    dd($user1, $user2);
    dd(View::make('layouts.app'));
    dd([
        app()->make('User'),
        app()->make('User')
    ]);
});
// Route::get('/test1', function () {
//     $_POST['password'] = '123456';

//     echo $f;
// });
Route::get('/test2/{age}', function ($age) {
    return $age;
})->middleware(\App\Http\Middleware\CheckAge::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/all-word', [\App\Http\Controllers\WordController::class, 'getAllElasticData']);
//Route::post('/search-word', [\App\Http\Controllers\WordController::class, 'searchWordElastic']);
Route::get('/search-vietnamese', [\App\Http\Controllers\WordController::class, 'searchVietnamese']);
Route::post('/search-english', [\App\Http\Controllers\WordController::class, 'searchEnglish']);
Route::delete('/delete-word', [\App\Http\Controllers\WordController::class, 'deleteAllData']);

Route::group(['prefix' => 'elastic-search'], function () {
    Route::post('/sync-data', [\App\Http\Controllers\ElasticSearchController::class, 'syncDataFromTable']);
    Route::post('/create-word-index-mapping', [\App\Http\Controllers\ElasticSearchController::class, 'createIndexWordWithMapping']);
});