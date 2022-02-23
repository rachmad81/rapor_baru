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

Route::group(['middleware'=>'mid_ks'],function(){
	Route::group(['prefix'=>'ks'],function(){
		Route::group(['prefix'=>'dashboard'],function(){
			Route::get('/','Ks\DashboardController@main')->name('dashboard_ks');
		});

		Route::group(['prefix'=>'data_master'],function(){
			Route::group(['prefix'=>'siswa'],function(){
				Route::get('/','Ks\Datamaster\SiswaController@main')->name('data_master_siswa');
				Route::get('/data','Ks\Datamaster\SiswaController@get_data')->name('get_data_data_master_siswa');
			});

			Route::group(['prefix'=>'guru'],function(){
				Route::get('/','Ks\Datamaster\GuruController@main')->name('data_master_guru');
				Route::get('/data','Ks\Datamaster\GuruController@get_data')->name('get_data_data_master_guru');
				Route::post('/form','Ks\Datamaster\GuruController@get_form')->name('form_master_guru');
				Route::post('/simpan','Ks\Datamaster\GuruController@simpan')->name('simpan_master_guru');
			});

			Route::group(['prefix'=>'mapel'],function(){
				Route::get('/','Ks\Datamaster\MapelController@main')->name('mapel_ks');
				Route::get('/data','Ks\Datamaster\MapelController@get_data')->name('get_data_mapel_ks');
			});

			Route::group(['prefix'=>'walikelas'],function(){
				Route::get('/','Ks\Datamaster\WalikelasController@main')->name('walikelas_ks');
				Route::post('/form','Ks\Datamaster\WalikelasController@form')->name('form_walikelas_ks');
				Route::post('/simpan','Ks\Datamaster\WalikelasController@simpan')->name('simpan_walikelas_ks');
				Route::get('/get_data','Ks\Datamaster\WalikelasController@get_data')->name('get_data_walikelas_ks');
				Route::post('/hapus','Ks\Datamaster\WalikelasController@hapus')->name('hapus_walikelas_ks');
			});

			Route::group(['prefix'=>'guru_mengajar'],function(){
				Route::get('/','Ks\Datamaster\GurumengajarController@main')->name('guru_mengajar_ks');
				Route::post('/form','Ks\Datamaster\GurumengajarController@form')->name('form_guru_mengajar_ks');
				Route::post('/simpan','Ks\Datamaster\GurumengajarController@simpan')->name('simpan_guru_mengajar_ks');
				Route::get('/get_data','Ks\Datamaster\GurumengajarController@get_data')->name('get_data_guru_mengajar_ks');
				Route::post('/hapus','Ks\Datamaster\GurumengajarController@hapus')->name('hapus_guru_mengajar_ks');
			});

			Route::group(['prefix'=>'rapor'],function(){
				Route::group(['prefix'=>'kunci'],function(){
					Route::get('/','Ks\Rapor\KunciController@main')->name('kunci_rapor');
				});
				Route::group(['prefix'=>'buka'],function(){
					Route::get('/','Ks\Rapor\BukaController@main')->name('buka_rapor');
				});
			});

			Route::group(['prefix'=>'user'],function(){
				Route::get('/','Ks\UserController@main')->name('user_ks');
			});
		});

		Route::group(['prefix'=>'reset_password'],function(){
			Route::get('/','Ks\ResetController@main')->name('reset_password_guru');
			Route::post('/reset','Ks\ResetController@reset')->name('proses_reset_password_guru');
		});
	});
});
