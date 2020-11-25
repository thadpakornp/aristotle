<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['api','activity']], function () {
    Route::post('/register', 'Api\RegisterApiController@register')->name('register');
    Route::post('/login', 'Api\LoginApiController@login')->name('login');

    //HomeController
    Route::post('/home', 'Api\HomeApiController@index')->name('home');
    Route::post('/loadmore', 'Api\HomeApiController@loadmore')->name('loadmore');

    Route::group(['middleware' => ['auth:api'], 'prefix' => 'backend', 'as' => 'backend.'], function () {
        Route::post('/logout', 'Api\LoginApiController@logout')->name('logout');

        //HomeControllerWithLogin
        Route::post('/homewithlogin', 'Api\HomeApiController@index')->name('homewithlogin');
        Route::post('/loadmorewithlogin', 'Api\HomeApiController@loadmore')->name('loadmorewithlogin');

        Route::post('/followandunfollow','Api\HomeApiController@followandunfollow')->name('followandunfollow');
        Route::post('/likeandunlike','Api\HomeApiController@likeandinlike')->name('likeandunlike');

        //PostController
        Route::post('/newpost', 'Api\PostApiController@newpost')->name('newpost');
        Route::post('/deleted', 'Api\PostApiController@deleted')->name('deleted');

        //CommentController
        Route::get('/getcomment/{id}', 'Api\CommentApiController@getComment')->name('getcomment');
        Route::post('/comment', 'Api\CommentApiController@comment')->name('comment');
        Route::post('/comment/deleted', 'Api\CommentApiController@commentdeleted')->name('deleted');

        //UserController
        Route::get('/user', 'Api\UserApiController@getUser')->name('user');
        Route::post('/changepassword', 'Api\UserApiController@changepassword')->name('changepassword');
        Route::post('/changeprofile', 'Api\UserApiController@changeprofile')->name('changeprofile');
    });
});
