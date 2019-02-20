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
// POBIERANIE LISTY DOKUMENTOW
Route::get('/Doc', 'DocumentController@getDoc')->name('getDocList');
// DODANIE DOKUMENTU
Route::post('/Doc', 'DocumentController@storeDoc')->name('storeDoc');
// EDYCJA DOKUMENTU
Route::put('/Doc', 'DocumentController@saveDoc');
// USUWANIE DOKUMENTU
Route::delete('/Doc/{id}', 'DocumentController@deleteDoc');
// POBIERANIE PLIKU DOKUMENTU
Route::get('/Doc/{id}', 'DocumentController@downloadDoc')->name('downloadDoc');
// PODGLĄD DOKUMENTU
Route::get('/displayDoc/{id}', 'DocumentController@displayDoc')->name('displayDoc');

