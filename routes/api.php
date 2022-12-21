<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\RatingsController;

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

Route::get('/ping', function(){
    return ['pong' => true];
});

//posts
Route::get('/posts', [PostsController::class, 'index']);
Route::get('/post/{id}', [PostsController::class, 'findOne']);
Route::get('/post/rating/{id}', [PostsController::class, 'findRatings']);
Route::delete('/post/{id}', [PostsController::class, 'delete']);
Route::post('/post', [PostsController::class, 'create']);
Route::post('/post/edit/{id}', [PostsController::class, 'update']);
Route::post('/post/edit/imagem/{id}', [PostsController::class, 'updateImagem']);

//comments
Route::post('/comment/post', [CommentsController::class, 'create']);

//Rating 
Route::post('/rating/post', [RatingsController::class, 'add']);
