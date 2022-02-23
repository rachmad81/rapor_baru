<?php

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

Route::group(['middleware'=>'mid_admin'],function(){
    Route::group(['prefix'=>'admin'],function(){
        Route::group(['prefix'=>'dashboard'],function(){
            Route::get('/','Admin\DashboardController@main')->name('dashboard_admin');
        });

        Route::group(['prefix'=>'mapel'],function(){
            Route::get('/','Admin\MapelController@main')->name('mapel');
            Route::post('/form','Admin\MapelController@form')->name('form_mapel');
            Route::post('/simpan','Admin\MapelController@simpan')->name('simpan_mapel');
            Route::get('/data','Admin\MapelController@get_data')->name('get_data_mapel');
        });

        Route::group(['prefix'=>'kd'],function(){
            Route::get('/kelas-{kelas}','Admin\KdController@main')->name('kd');
            Route::post('/get_mapel','Admin\KdController@get_mapel')->name('get_mapel_kd');
            Route::post('/setting','Admin\KdController@setting')->name('setting_kd');
            Route::post('/simpan','Admin\KdController@simpan')->name('simpan_kd');
            Route::post('/update','Admin\KdController@update')->name('update_kd');
            Route::post('/get_kd','Admin\KdController@get_kd')->name('get_kd');
            Route::post('/hapus','Admin\KdController@hapus')->name('hapus_kd');
        });
    });
});
