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
    Route::group(['prefix'=>'guru'],function(){
        Route::group(['prefix'=>'dashboard'],function(){
            Route::get('/','Guru\DashboardController@main')->name('dashboard_guru');
        });

        Route::group(['prefix'=>'walikelas'],function(){
            Route::get('/','Guru\Walikelas\WalikelasController@main')->name('walikelas');
            Route::post('/get_sekolah','Guru\Walikelas\WalikelasController@get_rombel')->name('get_rombel_by_ta');
        });

        Route::group(['prefix'=>'isian'],function(){
            Route::get('/mengajar/{kelas}/{rombel}/{mapel_id}','Guru\Walikelas\IsianController@main')->name('isian_nilai');
            Route::post('/pages','Guru\Walikelas\IsianController@pages')->name('page_isian');
            Route::post('/simpan_kkm','Guru\Walikelas\IsianController@simpan_kkm')->name('simpan_kkm');
            Route::post('/pages2','Guru\Walikelas\IsianController@pages2')->name('pages2_isian');
            Route::post('/pages3','Guru\Walikelas\IsianController@pages3')->name('pages3_isian');
            Route::post('/pages6','Guru\Walikelas\IsianController@pages6')->name('pages6_isian');
            Route::post('/simpan_nilai','Guru\Walikelas\IsianController@simpan_nilai')->name('simpan_nilai_guru');
        });

        Route::group(['prefix'=>'isian_wk'],function(){
            Route::get('/wk/{kelas}/{rombel}','Guru\Walikelas\IsianwkController@main')->name('isian_wk');
            Route::post('/pages','Guru\Walikelas\IsianwkController@pages')->name('page_isian_wk');
            Route::post('/pages2','Guru\Walikelas\IsianwkController@pages2')->name('pages2_isian_wk');
            Route::post('/pages3','Guru\Walikelas\IsianwkController@pages3')->name('pages3_isian_wk');
            Route::post('/simpan_nilai','Guru\Walikelas\IsianwkController@simpan_nilai')->name('simpan_nilai_wk_guru');

            Route::get('/cetak_rapor/{schema}/{id_siswa}','Guru\Walikelas\IsianwkController@cetak_rapor')->name('cetak_rapor_wk');
            Route::get('/cetak_dkn','Guru\Walikelas\IsianwkController@cetak_dkn')->name('cetak_dkn_wk');
            Route::post('/simpan_ekskul','Guru\Walikelas\IsianwkController@simpan_ekskul')->name('simpan_ekskul_wk');
            Route::post('/simpan_absen','Guru\Walikelas\IsianwkController@simpan_absen')->name('simpan_absen_wk');
            Route::post('/simpan_catatan','Guru\Walikelas\IsianwkController@simpan_catatan')->name('simpan_catatan_wk');
            Route::post('/modal_kesehatan','Guru\Walikelas\IsianwkController@modal_kesehatan')->name('modal_kesehatan_wk');
            Route::post('/simpan_kesehatan','Guru\Walikelas\IsianwkController@simpan_kesehatan')->name('simpan_kesehatan_wk');
        });

        Route::name('buku_induk-')->prefix('buku_induk')->group(function(){
            Route::get('/main/{id_siswa}','Guru\Walikelas\BukuindukController@main')->name('data');
            Route::post('/simpan','Guru\Walikelas\BukuindukController@simpan')->name('simpan');
        });
    });

    Route::post('/set_sekolah','Guru\SessionController@set_npsn')->name('set_npsn');
});
