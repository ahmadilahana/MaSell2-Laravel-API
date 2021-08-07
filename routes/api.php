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
Route::group([
    'namespace' => 'App\Http\Controllers\Api'
], function(){
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');
    Route::get('book', 'BookController@book');
    
    Route::get('bookall', 'BookController@bookAuth')->middleware('jwt.verify');
    Route::get('user', 'UserController@getAuthenticatedUser')->middleware('jwt.verify');
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
