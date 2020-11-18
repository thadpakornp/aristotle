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

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes(['verify' => true]);

    Route::group(['middleware' => ['auth', 'verified'], 'as' => 'backend.', 'prefix' => 'backend'], function () {
        Route::get('/home', 'HomeController@index')->name('home');

        //Users
        Route::group(['middleware' => ['role:admin'], 'as' => 'users.', 'prefix' => 'users'], function () {
            Route::get('/index', 'UserController@index')->name('index')->middleware('permission:view.users');
            Route::get('/{id}/show', 'UserController@show')->name('show')->middleware(['permission:view.users', 'permission:edit.users']);
            Route::post('/edited', 'UserController@edited')->name('edited')->middleware(['permission:edit.users']);
            Route::post('/deleted', 'UserController@deleted')->name('deleted')->middleware(['permission:delete.users']);
            Route::get('/create', 'UserController@create')->name('create')->middleware(['permission:create.users']);
            Route::post('/created', 'UserController@created')->name('created')->middleware(['permission:create.users']);
        });

        Route::group(['middleware' => ['role:admin'], 'as' => 'store.', 'prefix'=>'store'], function (){
            Route::get('/index', 'StoreController@index')->name('index')->middleware(['permission:view.store']);
        });
    });
});
