<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'IndexController@getIndex');

// XXX: IMPORTANT - for server health monitoring daemon
Route::get('/status', function() {
    return Response::json('ok', 200);
});

// route to show the signin form
Route::get('/signin', 'AlohaController@getSignin');

// route to process the signin form
Route::post('/signin', 'AlohaController@postSignin');

Route::get('/signout', 'AlohaController@getSignout');

// signup routing
Route::get('/signup', 'AlohaController@getSignup');
Route::post('/signup', 'AlohaController@postSignup');
Route::get('/signup/verify', 'AlohaController@getSignupVerify');

// Forgot password routing
Route::get('/forgot/password', 'ForgotPasswordController@getForgotPassword');
Route::post('/forgot/password', 'ForgotPasswordController@postForgotPassword');
Route::get('/forgot/password/verify', 'ForgotPasswordController@getVerify');
Route::post('/forgot/password/verify', 'ForgotPasswordController@postVerify');

// XXX: IMPORTANT - pages which require authentication
Route::group(array('before' => 'auth'), function()
{
    Route::get('/dashboard', 'DashBoardController@getDashBoard');
    
    Route::get('/profile', 'ProfileController@getProfile');
    Route::post('/profile', 'ProfileController@postProfile');
    Route::post('/profile/picture/upload', 'ProfileController@postTemporaryProfilePicture');
    
    Route::get('/change/password', 'PasswordController@getPassword');
    Route::post('/change/password', 'PasswordController@postPassword');
    
    // XXX: IMPORTANT - profile picture1 routing
    Route::get('/pf/p0', 'PictureController@getProfilePic0');
    Route::get('/pf/p1', 'PictureController@getProfilePic1');
    Route::get('/pf/p2', 'PictureController@getProfilePic2');
    Route::get('/pf/tp1/{rand}', 'PictureController@getProfileTmpPic')
    ->where('rand', '[0-9]+');
});
