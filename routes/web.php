<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'AdminInfoHMTCController@index')->name('admin');
Route::get('/admin/lomba/stop/{id}', 'AdminInfoLombaController@stop')->name('lomba.stop');
Route::get('/admin/lomba/start/{id}', 'AdminInfoLombaController@start')->name('lomba.start');
Route::get('/admin/lomba/foto/{id}', 'AdminInfoLombaController@foto')->name('lomba.foto');
Route::get('/admin/lomba/delete/{id}', 'AdminInfoLombaController@destroy')->name('lomba.delete');
Route::resource('lomba', 'AdminInfoLombaController');

Route::get('/admin/infohmtc', 'AdminInfoHMTCController@index')->name('admininfohmtc');
Route::get('/admin/infohmtc/foto/{id}', 'AdminInfoHMTCController@foto')->name('admininfohmtc.foto');
Route::get('/admin/infohmtc/stop/{id}', 'AdminInfoHMTCController@stop')->name('admininfohmtc.stop');
Route::get('/admin/infohmtc/start/{id}', 'AdminInfoHMTCController@start')->name('admininfohmtc.start');
Route::get('/admin/infohmtc/addtag/{post_id}/{tag_id}', 'AdminInfoHMTCController@addTag')->name('admininfohmtc.addtag');
Route::get('/admin/infohmtc/tag/{tag}', 'AdminInfoHMTCController@index')->name('admininfohmtc.tag');
Route::resource('admininfohmtc', 'AdminInfoHMTCController');

Route::resource('tag', 'TagController');

Route::get('/infolomba', 'InfoLombaController@index')->name('infolomba');