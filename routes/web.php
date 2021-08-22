<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/vuebasic', function () {
    return view('vuebasic');
});

Route::get('/ecpay_demo'
    ,'ecpay\ECPayController@DemoPage');

Route::get('/ecpay_demo/enterpage'
    ,'ecpay\ECPayController@EnterPage');

Route::get('/ecpay_demo/billlistpage'
    ,'ecpay\ECPayController@BilllistPage');

Route::post('/ecpay_demo/topay'
    ,'ecpay\ECPayController@Topay');

Route::post('/callback', 'ecpay\ECPayController@callback');
Route::get('/success', 'ecpay\ECPayController@redirectFromECpay');


Route::get('/ecpay_demo/acceptorderTopay', 'ecpay\ECPayController@acceptorderTopay');
Route::post('/acceptorderTopayCallback', 'ecpay\ECPayController@acceptorderTopayCallback');
Route::get('/acceptorderTopayOK', 'ecpay\ECPayController@acceptorderTopayOK');