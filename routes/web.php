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

Route::group(['middleware' => ['web','activity']], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes(['verify' => true]);

    Route::group(['middleware' => ['auth', 'verified'], 'as' => 'backend.', 'prefix' => 'backend'], function () {
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/profile', 'UserController@profile')->name('profile');
        Route::post('/edited', 'UserController@profileedited')->name('edited');
        Route::get('/password', 'UserController@password')->name('password');
        Route::post('/password', 'UserController@passwordchange')->name('password.update');

        //Users
        Route::group(['middleware' => ['role:admin'], 'as' => 'users.', 'prefix' => 'users'], function () {
            Route::get('/index', 'UserController@index')->name('index')->middleware('permission:view.users');
            Route::get('/{id}/show', 'UserController@show')->name('show')->middleware(['permission:view.users', 'permission:edit.users']);
            Route::post('/edited', 'UserController@edited')->name('edited')->middleware(['permission:edit.users']);
            Route::post('/deleted', 'UserController@deleted')->name('deleted')->middleware(['permission:delete.users']);
            Route::get('/create', 'UserController@create')->name('create')->middleware(['permission:create.users']);
            Route::post('/created', 'UserController@created')->name('created')->middleware(['permission:create.users']);
        });

        Route::group(['middleware' => ['role:admin|store|user']], function (){
            Route::get('/create_channel', 'StoreController@create_channel')->name('create_channel')->middleware(['permission:create.store']);
            Route::post('/getAddress', 'StoreController@getAddress')->name('getAddress');
            Route::post('/post', 'StoreController@post')->name('post')->middleware(['permission:create.store']);
            Route::post('/deleted/file', 'StoreController@deletedfile')->name('file.deleted')->middleware(['permission:create.store','permission:edit.store']);
            Route::post('/post/save', 'StoreController@stroed')->name('stroed')->middleware(['permission:create.store']);
        });

        Route::group(['middleware' => ['role:admin|store'], 'as' => 'store.', 'prefix'=>'store'], function (){
            Route::get('/index', 'StoreController@index')->name('index')->middleware(['permission:view.store']);
            Route::get('/{id}/show', 'StoreController@show')->name('show')->middleware(['permission:view.store', 'permission:edit.store']);
            Route::post('/store/edit', 'StoreController@edit')->name('edit')->middleware(['permission:edit.store']);
            Route::post('/file/delete', 'StoreController@filedeleted')->name('file.deleted')->middleware(['permission:edit.store']);
            Route::post('/delete', 'StoreController@deleted')->name('deleted')->middleware(['permission:delete.store']);

            Route::group(['middleware' => ['role:admin']], function () {
                Route::post('/approve', 'StoreController@approve')->name('approve')->middleware(['permission:edit.store']);
                Route::post('/noapprove', 'StoreController@noapprove')->name('noapprove')->middleware(['permission:edit.store']);
            });

            Route::group(['as' => 'course.', 'prefix'=>'course'], function (){
                Route::get('/{id}/create', 'CourseController@create')->name('create')->middleware(['permission:create.course']);
                Route::post('/store', 'CourseController@store')->name('store')->middleware(['permission:create.course']);
                Route::post('/post', 'CourseController@post')->name('post')->middleware(['permission:create.course']);
                Route::post('/deleted/file', 'CourseController@deletedfile')->name('file.deleted')->middleware(['permission:edit.store']);

                Route::get('/{id}/edit', 'CourseController@edit')->name('edit')->middleware(['permission:edit.course']);
                Route::post('/edited', 'CourseController@edited')->name('edited')->middleware(['permission:edit.course']);
                Route::post('/file/delete', 'CourseController@filedeleted')->name('delete.file')->middleware(['permission:edit.store']);
                Route::post('/cover/delete', 'CourseController@coverdeleted')->name('delete.cover')->middleware(['permission:edit.store']);
                Route::post('/delete', 'CourseController@deleted')->name('deleted')->middleware(['permission:delete.store']);
            });
        });

        Route::group(['as' => 'posts.', 'prefix'=>'posts'], function (){
            Route::post('/index', 'PostController@index')->name('index')->middleware(['permission:view.post']);
            Route::get('/create', 'PostController@create')->name('create')->middleware(['permission:create.post']);
            Route::post('/post', 'PostController@post')->name('post')->middleware(['permission:create.post']);
            Route::post('/deleted/file', 'PostController@deletedfile')->name('file.deleted')->middleware(['permission:create.post']);
            Route::post('/post/save', 'PostController@stroed')->name('stroed')->middleware(['permission:create.post']);
        });
    });
});
