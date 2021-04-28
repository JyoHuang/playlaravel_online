<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'message'], function () {
    Route::get('/gettextmessage', 'App\Http\Controllers\message\TextMessageController@getTextMessage');
    Route::post('/addtextmessage','App\Http\Controllers\message\TextMessageController@addTextMessage'); 
    Route::put('/updatetextmessage','App\Http\Controllers\message\TextMessageController@updateTextMessage'); 
    Route::delete('/deletetextmessage','App\Http\Controllers\message\TextMessageController@deleteTextMessage');
    Route::post('/searchtextmessage','App\Http\Controllers\message\TextMessageController@searchTextMessage'); 

    Route::get('/getimagemessage', 'App\Http\Controllers\message\ImageMessageController@getImageMessage');
    Route::post('/addimagemessage','App\Http\Controllers\message\ImageMessageController@addImageMessage'); 
    Route::post('/updateimagemessage','App\Http\Controllers\message\ImageMessageController@updateImageMessage'); 
    Route::delete('/deleteimagemessage','App\Http\Controllers\message\ImageMessageController@deleteImageMessage');
    Route::post('/searchimagemessage','App\Http\Controllers\message\ImageMessageController@searchImageMessage');
});