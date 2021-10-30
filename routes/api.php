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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');



    Route::group(['prefix' => '/users', 'middleware' => 'auth:api'], function() {
        Route::get('/{user}', 'UserController@show');
        Route::patch('/{username}', 'UserController@update');
    });
    Route::group(['prefix' => '/hils','middleware' => 'auth:api'], function() {

        Route::get('/', 'HilController@index');
        Route::post('/', 'HilController@store');
        Route::delete('/{hil}', 'HilController@destroy');
        Route::patch('/{hil}', 'HilController@update');
        Route::group(['prefix' => '/{hil}/hilentries'], function() {
            Route::post('/', 'HilEntryController@store');
            Route::get('/', 'HilEntryController@index');
        });

    });
    Route::group(['prefix' => '/properties', 'middleware' => 'auth:api'], function() {
        Route::get('/', 'PropertyController@show');
        Route::patch('/{username}', 'PropertyController@update');
    });
