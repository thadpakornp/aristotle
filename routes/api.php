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
    Route::post('/home', 'Api\HomeApiController@indexposts')->name('home');
    Route::post('/home/channel', 'Api\HomeApiController@indexchannel')->name('homechannel');
    Route::post('/home/course', 'Api\HomeApiController@indexcourses')->name('homecourse');

    Route::post('/course/all', 'Api\HomeApiController@indexcoursesall')->name('homecourseall');
    Route::post('/channel/all', 'Api\HomeApiController@indexchannelall')->name('homechannelall');

    Route::post('/loadmore', 'Api\HomeApiController@loadmore')->name('loadmore');

    Route::group(['middleware' => ['auth:api'], 'prefix' => 'backend', 'as' => 'backend.'], function () {
        Route::post('/logout', 'Api\LoginApiController@logout')->name('logout');

        //HomeControllerWithLogin
        Route::post('/homewithlogin', 'Api\HomeApiController@indexposts')->name('homewithlogin');
        Route::post('/loadmorewithlogin', 'Api\HomeApiController@loadmore')->name('loadmorewithlogin');

        Route::post('/homechannelwithlogin', 'Api\HomeApiController@indexchannel')->name('homechannelwithlogin');

        Route::post('/channel/allwithlogin', 'Api\HomeApiController@indexchannelall')->name('homechannelallwithlogin');
        Route::post('/course/allwithlogin', 'Api\HomeApiController@indexcoursesall')->name('homecourseallwithlogin');

        Route::post('/homecoursewithlogin', 'Api\HomeApiController@indexcourses')->name('homecoursewithlogin');

        Route::post('/followandunfollow','Api\HomeApiController@followandunfollow')->name('followandunfollow');
        Route::post('/likeandunlike','Api\HomeApiController@likeandinlike')->name('likeandunlike');

        //PostController
        Route::post('/newpost', 'Api\PostApiController@newpost')->name('newpost');
        Route::post('/deleted', 'Api\PostApiController@deleted')->name('deleted');
        Route::post('/post/likeandunlike', 'Api\PostApiController@likeandunlike')->name('postlikeandunlike');


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
