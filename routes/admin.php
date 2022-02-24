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

Route::middleware('mid_admin')->name('admin-')->prefix('admin')->group(function(){
    Route::name('dashboard-')->prefix('dashboard')->group(function(){
        Route::get('/','Admin\DashboardController@main')->name('main');
    });

    Route::name('tahun-ajaran-')->prefix('tahun_ajaran')->group(function(){
        Route::get('/','Admin\TaController@main')->name('main');
        Route::get('/data','Admin\TaController@get_data')->name('get_data');
        Route::post('/form','Admin\TaController@form')->name('form');
        Route::post('/simpan','Admin\TaController@simpan')->name('simpan');
        Route::post('/hapus','Admin\TaController@hapus')->name('hapus');
    });

    Route::name('mapel-')->prefix('mapel')->group(function(){
        Route::get('/','Admin\MapelController@main')->name('main');
        Route::post('/form','Admin\MapelController@form')->name('form');
        Route::post('/simpan','Admin\MapelController@simpan')->name('simpan');
        Route::get('/data','Admin\MapelController@get_data')->name('get_data');
    });

    Route::name('kd-')->prefix('kd')->group(function(){
        Route::get('/kelas-{kelas}','Admin\KdController@main')->name('main');
        Route::post('/get_mapel','Admin\KdController@get_mapel')->name('get_mapel');
        Route::post('/setting','Admin\KdController@setting')->name('setting');
        Route::post('/simpan','Admin\KdController@simpan')->name('simpan');
        Route::post('/update','Admin\KdController@update')->name('update');
        Route::post('/get_kd','Admin\KdController@get_kd')->name('get');
        Route::post('/hapus','Admin\KdController@hapus')->name('hapus');
    });
});
