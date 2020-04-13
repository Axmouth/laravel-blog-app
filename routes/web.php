<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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


Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');

Route::resource('posts', 'PostsController');
Route::delete('/posts/{id}/cover_image', 'PostsController@removeCoverImage');
Route::post('/posts/{id}', 'BlogCommentsController@store');
Route::get('/comments/{id}/edit', 'BlogCommentsController@edit')->name('editComment');
Route::delete('/comments/{id}', 'BlogCommentsController@destroy')->name('removeComment');
Route::put('/comments/{id}', 'BlogCommentsController@update')->name('updateComment');
Route::post('ckeditor/upload', 'PostsController@ckupload')->name('ckeditor.upload');

Auth::routes();

Route::get('/dashboard', 'MyprofileController@dashboard')->name('dashboard');
Route::get('/myprofile', 'MyprofileController@show');
Route::put('/myprofile', 'MyprofileController@profileUpdate');
Route::delete('/myprofile/pfp', 'MyprofileController@removeProfileImage');
Route::get('/mysecurity', 'MyprofileController@security');

Route::get('/users/{id}', 'UserprofilesController@show');
Route::get('/users/{id}/posts', 'UserprofilesController@showPosts');
Route::get('/users/{id}/comments', 'UserprofilesController@showComments');
