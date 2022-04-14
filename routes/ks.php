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

Route::middleware('mid_ks')->group(function(){
	Route::name('ks-')->prefix('ks')->group(function(){
		Route::name('dashboard-')->prefix('dashboard')->group(function(){
			Route::get('/','Ks\DashboardController@main')->name('main');
		});

		Route::name('data-master-')->prefix('data_master')->group(function(){
			Route::name('siswa-')->prefix('siswa')->group(function(){
				Route::get('/','Ks\Datamaster\SiswaController@main')->name('main');
				Route::get('/data','Ks\Datamaster\SiswaController@get_data')->name('get_data');
			});

			Route::name('guru-')->prefix('guru')->group(function(){
				Route::get('/','Ks\Datamaster\GuruController@main')->name('main');
				Route::get('/data','Ks\Datamaster\GuruController@get_data')->name('get_data');
				Route::post('/form','Ks\Datamaster\GuruController@get_form')->name('form');
				Route::post('/simpan','Ks\Datamaster\GuruController@simpan')->name('simpan');
			});

			Route::name('mapel-')->prefix('mapel')->group(function(){
				Route::get('/','Ks\Datamaster\MapelController@main')->name('main');
				Route::get('/data','Ks\Datamaster\MapelController@get_data')->name('get_data');
			});

			Route::name('walikelas-')->prefix('walikelas')->group(function(){
				Route::get('/','Ks\Datamaster\WalikelasController@main')->name('main');
				Route::post('/form','Ks\Datamaster\WalikelasController@form')->name('form');
				Route::post('/simpan','Ks\Datamaster\WalikelasController@simpan')->name('simpan');
				Route::get('/get_data','Ks\Datamaster\WalikelasController@get_data')->name('get_data');
				Route::post('/hapus','Ks\Datamaster\WalikelasController@hapus')->name('hapus');
			});

			Route::name('guru_mengajar-')->prefix('guru_mengajar')->group(function(){
				Route::get('/','Ks\Datamaster\GurumengajarController@main')->name('main');
				Route::post('/form','Ks\Datamaster\GurumengajarController@form')->name('form');
				Route::post('/simpan','Ks\Datamaster\GurumengajarController@simpan')->name('simpan');
				Route::get('/get_data','Ks\Datamaster\GurumengajarController@get_data')->name('get_data');
				Route::post('/hapus','Ks\Datamaster\GurumengajarController@hapus')->name('hapus');
			});

			Route::name('upload_nilai-')->prefix('upload_nilai')->group(function(){
				Route::get('/','Ks\Datamaster\UploadController@main')->name('main');
				Route::post('/mapel','Ks\Datamaster\UploadController@mapel')->name('mapel');
				Route::get('/template','Ks\Datamaster\UploadController@template')->name('template');
				Route::post('/upload','Ks\Datamaster\UploadController@upload')->name('upload');
			});

			Route::name('rapor-')->prefix('rapor')->group(function(){
				Route::name('kunci-')->prefix('kunci')->group(function(){
					Route::get('/','Ks\Rapor\KunciController@main')->name('main');
				});
				Route::name('buka-')->prefix('buka')->group(function(){
					Route::get('/','Ks\Rapor\BukaController@main')->name('main');
				});
			});

			Route::name('user-')->prefix('user')->group(function(){
				Route::get('/','Ks\UserController@main')->name('main');
			});
		});

		Route::name('reset_password-')->prefix('reset_password')->group(function(){
			Route::get('/','Ks\ResetController@main')->name('main');
			Route::post('/reset','Ks\ResetController@reset')->name('reset');
		});
	});
});
