<?php

use App\User;
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

Auth::routes();

// Home
Route::get('/', 'HomeController@index');

// Contact
Route::get('/contact', 'ContactController@show');
Route::get('/faq', 'ContactController@show');

// Browse
Route::get('/browse/{brightness?}/{color?}/{sort?}/{page?}', 'ThemeController@index');
Route::post('/browse', 'ThemeController@ajax');

// My Themes
Route::get('/my-themes/{visibility?}/{sort?}/{page?}', 'ThemeController@user')->middleware('auth');
Route::post('/my-themes', 'ThemeController@userAjax')->middleware('auth');

// Edit Details
Route::get('/account', 'UserController@index')->middleware('auth');
Route::post('/account/details', 'UserController@update')->middleware('auth');
Route::post('/account/password', 'UserController@password')->middleware('auth');
Route::post('/account/delete', 'UserController@destroy')->middleware('auth');

// Theme
Route::get('/themes/{slug}/{id}/', 'ThemeController@show');
Route::post('/themes/{slug}/{id}/download', 'ThemeController@download');
Route::get('/themes/{slug}/{id}/remix', 'ThemeController@remix');
Route::get('/themes/{slug}/{id}/feature', 'ThemeController@feature')->middleware('admin');
Route::post('/themes/{slug}/{id}/rate', 'ThemeController@rate')->middleware('auth');
Route::get('/themes/{slug}/{id}/comments', 'ThemeCommentController@index');
Route::post('/themes/{slug}/{id}/comment/store', 'ThemeCommentController@store')->middleware('auth');
Route::post('/themes/{slug}/{id}/comment/delete', 'ThemeCommentController@destroy')->middleware('auth');
Route::post('/theme/{id}/visibility', 'ThemeController@visibility')->middleware('auth');
Route::post('/theme/{id}/delete', 'ThemeController@destroy')->middleware('auth');

// Editor
Route::get('/editor/recent', 'ThemeController@recent')->middleware('auth');
Route::get('/editor/{id?}', 'EditorController@index');
Route::post('/editor/load', 'EditorController@edit')->middleware('auth');
Route::post('/editor/import', 'ThemeController@import')->middleware('auth');
Route::post('/editor/save', 'EditorController@update')->middleware('auth');
Route::post('/editor/colours/store', 'ColourController@store')->middleware('auth');

// Charts
Route::get('/charts', 'ChartController@index');
Route::post('/charts', 'ChartController@show');

// Adverts
Route::get('/products/{id?}', 'AdvertClickController@store');

// Help
Route::get('/help', function(){
	return redirect('/');
});
Route::get('/help/transparency-issues', function(){
	return view('help.transparency-issues');
});
Route::get('/help/ableton-11-compatibility', function(){
	return view('help.ableton-11-compatibility');
});

// Admin
Route::group(['prefix' => '--admin', 'middleware' => ['admin']], function () {

	Route::get('/adverts', 'Admin\AdvertController@index');
	Route::get('/adverts/create', 'Admin\AdvertController@create');
	Route::post('/adverts/store', 'Admin\AdvertController@store');
	Route::post('/adverts/destroy', 'Admin\AdvertController@destroy');

	Route::get('/videos', 'Admin\VideoController@index');
	Route::get('/videos/create', 'Admin\VideoController@create');
	Route::post('/videos/store', 'Admin\VideoController@store');
	Route::post('/videos/destroy', 'Admin\VideoController@destroy');

	Route::get('/elements', 'Admin\ThemeElementController@index');
	Route::post('/elements/import', 'Admin\ThemeElementController@import');

	Route::get('/sitemap', 'Admin\SitemapController@index');

});