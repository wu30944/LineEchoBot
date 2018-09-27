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

Route::get('/', 'JiebaController@index');

Route::get('/.well-known/acme-challenge/{filename}','AcmeController');

Route::post('/callback','LineBotController');

Route::post('/jieba-process', 'JiebaController@jiebaProcess');