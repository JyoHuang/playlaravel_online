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
    Route::get('/gettextmessage', 'message\TextMessageController@getTextMessage');
    Route::post('/addtextmessage','message\TextMessageController@addTextMessage'); 
    Route::put('/updatetextmessage','message\TextMessageController@updateTextMessage'); 
    Route::delete('/deletetextmessage','message\TextMessageController@deleteTextMessage');
    Route::post('/searchtextmessage','message\TextMessageController@searchTextMessage'); 

    Route::get('/getimagemessage', 'message\ImageMessageController@getImageMessage');
    Route::post('/addimagemessage','message\ImageMessageController@addImageMessage'); 
    Route::post('/updateimagemessage','message\ImageMessageController@updateImageMessage'); 
    Route::delete('/deleteimagemessage','message\ImageMessageController@deleteImageMessage');
    Route::post('/searchimagemessage','message\ImageMessageController@searchImageMessage');
});