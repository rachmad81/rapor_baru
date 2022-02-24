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

Route::group(['middleware'=>'mid_guru'],function(){
    Route::name('guru-')->prefix('guru')->group(function(){
        Route::name('dashboard-')->prefix('dashboard')->group(function(){
            Route::get('/','Guru\DashboardController@main')->name('main');
        });

        Route::name('walikelas-')->prefix('walikelas')->group(function(){
            Route::get('/','Guru\Walikelas\WalikelasController@main')->name('main');
            Route::post('/get_sekolah','Guru\Walikelas\WalikelasController@get_rombel')->name('get_rombel');
        });

        Route::name('isian-')->prefix('isian')->group(function(){
            Route::get('/mengajar/{id_rombel}/{mapel_id}','Guru\Walikelas\IsianController@main')->name('main');
            Route::post('/pages','Guru\Walikelas\IsianController@pages')->name('pages');
            Route::post('/simpan_kkm','Guru\Walikelas\IsianController@simpan_kkm')->name('simpan_kkm');
            Route::post('/pages2','Guru\Walikelas\IsianController@pages2')->name('pages2');
            Route::post('/pages3','Guru\Walikelas\IsianController@pages3')->name('pages3');
            Route::post('/pages6','Guru\Walikelas\IsianController@pages6')->name('pages6');
            Route::post('/simpan_nilai','Guru\Walikelas\IsianController@simpan_nilai')->name('simpan_nilai');
        });

        Route::name('isian_wk-')->prefix('isian_wk')->group(function(){
            Route::get('/wk/{id_rombel}','Guru\Walikelas\IsianwkController@main')->name('main');
            Route::post('/pages','Guru\Walikelas\IsianwkController@pages')->name('page');
            Route::post('/pages2','Guru\Walikelas\IsianwkController@pages2')->name('pages2');
            Route::post('/pages3','Guru\Walikelas\IsianwkController@pages3')->name('pages3');
            Route::post('/simpan_nilai','Guru\Walikelas\IsianwkController@simpan_nilai')->name('simpan_nilai');

            Route::post('/generate_anggota','Guru\Walikelas\IsianwkController@do_generate_anggota')->name('generate_anggota');
            Route::get('/cetak_rapor/{schema}/{id_siswa}','Guru\Walikelas\IsianwkController@cetak_rapor')->name('cetak_rapor_wk');
            Route::get('/cetak_dkn','Guru\Walikelas\IsianwkController@cetak_dkn')->name('cetak_dkn_wk');
            Route::post('/simpan_ekskul','Guru\Walikelas\IsianwkController@simpan_ekskul')->name('simpan_ekskul');
            Route::post('/simpan_absen','Guru\Walikelas\IsianwkController@simpan_absen')->name('simpan_absen');
            Route::post('/simpan_catatan','Guru\Walikelas\IsianwkController@simpan_catatan')->name('simpan_catatan');
            Route::post('/modal_kesehatan','Guru\Walikelas\IsianwkController@modal_kesehatan')->name('modal_kesehatan');
            Route::post('/simpan_kesehatan','Guru\Walikelas\IsianwkController@simpan_kesehatan')->name('simpan_kesehatan');
        });

        Route::name('buku_induk-')->prefix('buku_induk')->group(function(){
            Route::get('/main/{id_siswa}','Guru\Walikelas\BukuindukController@main')->name('data');
            Route::post('/simpan','Guru\Walikelas\BukuindukController@simpan')->name('simpan');
        });
    });

    Route::post('/set_sekolah','Guru\SessionController@set_npsn')->name('set_npsn');
});
