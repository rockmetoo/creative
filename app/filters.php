<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
    // profile Singleton (global) object
    App::singleton('getUserProfile', function() {
    
        $app = new stdClass;
    
        if (Auth::check()) {
            $app->USER_PROFILE = array();
    
            $userId = Auth::user()->userId;
    
            // XXX: IMPORTANT - Fetch the profile. TODO: need to get from redis cache in future
            $res = Profile::getProfile($userId)->get();
    
            if(count($res)) {
                $app->USER_PROFILE = $res[0];
                
                if(!$app->USER_PROFILE['firstName'] && !$app->USER_PROFILE['lastName']) $app->USER_PROFILE['fullName'] = 'No Name';
                else $app->USER_PROFILE['fullName'] = $app->USER_PROFILE['firstName'].' '.$app->USER_PROFILE['lastName'];
                
                if(strlen($app->USER_PROFILE['fullName']) > 13) $app->USER_PROFILE['fullName'] = substr($app->USER_PROFILE['fullName'], 0, 11) . '..';
            }
    
        } else {
            $app->USER_PROFILE = array();
        }
    
        return $app->USER_PROFILE;
    });
    
    $USER_PROFILE = App::make('getUserProfile');
    
    View::share('USER_PROFILE', $USER_PROFILE);
});

App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) {
		if (Request::ajax()) {
			return Response::make('Unauthorized', 401);
		} else {
			return Redirect::guest('/signin');
		}
	}
});

Route::filter('isUser', function()
{
    // Get authenticated user
    $user =  Auth::user();

    if ($user->userType != 1 && $user->userStatus != 1) {
        return Redirect::guest('/access/denied');
    }
});

Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token')) {
		throw new Illuminate\Session\TokenMismatchException;
	}
});
