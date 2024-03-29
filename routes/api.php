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

Route::prefix('absensiAPI')->group(function () {
    Route::match(['get','post'],'login', 'AndroCo@login')->name('apilogin');
    Route::match(['get','post'],'getdatabyId', 'AndroCo@getdatabyId')->name('infologin');
    Route::match(['get','post'],'updateaccount', 'AndroCo@updateprofile')->name('apiupdateprofile');
    Route::match(['get','post'],'updatesandi', 'AndroCo@updatesandi')->name('apiupdatesandi');
    Route::match(['get','post'],'addizin', 'AndroCo@addizin')->name('apiaddizin');
    Route::match(['get','post'],'getcuti/{id}/{idistansi}', 'AndroCo@getcuti')->name('apicuti');
    Route::match(['get','post'],'getdinas/{id}/{idistansi}', 'AndroCo@getdinas')->name('apidinas');
    Route::match(['get','post'],'deletecuti/{id}', 'AndroCo@deletecuti')->name('deletecuti');
    Route::match(['get','post'],'deletedinas/{id}', 'AndroCo@deletedinas')->name('deletedinas');
    Route::match(['get','post'],'addabsen', 'AndroCo@addabsen')->name('apiaddabsen');
    Route::match(['get','post'],'addabsenluarkantor', 'AndroCo@addabsenluarkantor')->name('apiaddabsenluarkantor');
    Route::match(['get','post'],'addcuti', 'AndroCo@addcuti')->name('apiaddcuti');
    Route::match(['get','post'],'adddinas', 'AndroCo@adddinas')->name('apiadddinas');
    Route::match(['get','post'],'getemployee', 'AndroCo@getemployee')->name('getemployee');
    Route::match(['get','post'],'updatecutinoimage', 'AndroCo@updatecutinoimage')->name('updatecutinoimage');
    Route::match(['get','post'],'updatecutiimage', 'AndroCo@updatecutiimage')->name('updatecutiimage');
    Route::match(['get','post'],'updatedinasnoimage', 'AndroCo@updatedinasnoimage')->name('updatedinasnoimage');
    Route::match(['get','post'],'updatedinasimage', 'AndroCo@updatedinasimage')->name('updatedinasimage');
    Route::match(['get','post'],'getabsenbypegawai', 'AndroCo@getabsenbypegawai')->name('getabsenbypegawai');
    Route::match(['get','post'],'getoutlocation', 'AndroCo@getoutlocation')->name('getoutlocation');
    Route::match(['get','post'],'deleteaccountunused', 'AndroCo@deleteaccountunused')->name('deleteaccountunused');


});


