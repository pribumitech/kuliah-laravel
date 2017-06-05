<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::group(array('middleware' => ['apirate:50,1'], 'namespace' => 'Api'), function () {
Route::group(array('namespace' => 'Api'), function () {
	
	Route::get('kuliah/mahasiswa', '\App\Http\Controllers\Api\MahasiswaController@getIndex')->name('mahasiswa.index');
	Route::get('kuliah/jurusan', '\App\Http\Controllers\Api\JurusanController@getIndex')->name('jurusan.index');

	/* limit post 2x dalam 1/2 hari (720 minute) */
	/*Route::post('kuliah/mahasiswa/insert',[
		'middleware' => [
			'apirate:2,720'
		],
		'uses' => '\App\Http\Controllers\Api\MahasiswaController@postInsert'
	])->name('mahasiswa.insert');*/

	Route::post('kuliah/mahasiswa/insert',[
		'uses' => '\App\Http\Controllers\Api\MahasiswaController@postInsert'
	])->name('mahasiswa.insert');

});