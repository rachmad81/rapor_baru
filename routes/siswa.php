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
Route::group(['middleware'=>'mid_siswa'],function(){
    Route::group(['prefix'=>'siswa'],function(){
        Route::group(['prefix'=>'dashboard'],function(){
            Route::get('/','Siswa\DashboardController@main')->name('dashboard_siswa');
        });

        Route::group(['prefix'=>'rapor_akhir'],function(){
            Route::get('/','Siswa\RaporController@main')->name('rapor_akhir');
            Route::post('/data','Siswa\RaporController@data')->name('data_rapor_akhir');
        });

        Route::group(['prefix'=>'nilai'],function(){
            Route::get('/','Siswa\IsianController@main')->name('nilai_siswa');
            Route::get('/main/{id_mengajar}','Siswa\IsianController@mengajar')->name('mengajar_nilai_siswa');

            Route::post('/get_pages','Siswa\IsianController@get_pages')->name('get_pages_siswa');
            Route::post('/pages1','Siswa\IsianController@pages1')->name('pages1_siswa');
            Route::post('/pages2','Siswa\IsianController@pages2')->name('pages2_siswa');

            Route::post('/simpan11','Siswa\IsianController@simpan11')->name('simpan_nilai_siswa_11');
            Route::post('/simpankd','Siswa\IsianController@simpankd')->name('simpan_nilai_siswa_kd');
            Route::post('/simpansd','Siswa\IsianController@simpansd')->name('simpan_nilai_siswa_sd');
        });
    });
});