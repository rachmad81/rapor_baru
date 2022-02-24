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

Route::get('/',function(){
    return Redirect::route('ks-dashboard-main');
});

Route::group(['prefix'=>'login'],function(){
    Route::get('/','LoginController@main')->name('login_page');
    Route::post('/do_login','LoginController@do_login')->name('do_login');
    Route::get('/do_logout',function(){
        Session::flush();
        return Redirect::to('/');
    })->name('do_logout');
});

Route::group(['middleware'=>'mid_all'],function(){
    Route::group(['prefix'=>'ubah_password'],function(){
        Route::get('/','PasswordController@main')->name('ubah_password');
        Route::post('/reset_password','PasswordController@reset_password')->name('reset_password');
    });

    Route::post('/set_ta','SessionController@set_tahun_ajaran')->name('set_ta');
    Route::post('/get_rombel','SessionController@get_rombel')->name('get_rombel');
    Route::post('/get_mapel','SessionController@get_mapel_by_kategori')->name('get_mapel_by_kategori');
});
