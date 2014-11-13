<?php
/**
 * @author rockmetoo <rockmetoo@gmail.com>
 */

use Illuminate\Support\Facades\Auth;

class AlohaController extends BaseController
{
    public function getSignin()
    {
        return View::make('signin.signin');
    }
    
    public function postSignin()
    {
        // XXX: IMPORTANT - get all post data in one variable to reduce the call for Input::get
        $postData = Input::all();
        
        // validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|email',
            'password' => 'required|min:6|max:32'
        );
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($postData, $rules);
        
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // XXX: IMPORTANT - send back the input (not the password) so that we can repopulate the form
            return Redirect::to('/signin')->withErrors($validator)->withInput();
        } else {
            // create our user data for the authentication
            $data = array(
                'email' 	=> $postData['email'],
                'password' 	=> $postData['password']
            );
            
            $remember = false;

            if (isset($postData['remember'])) $remember = true;
            
            $userInfo = Aloha::getUserByEmail($postData['email'])->get();
            
            if (count($userInfo)) {
                // XXX: IMPORTANT - user freezed or registration not confirmed yet
                if ($userInfo[0]->userStatus != 1) return Redirect::back()->withInput()->with('loginFailure', '1');
            } else {
                // XXX: IMPORTANT - email address does not exist
                return Redirect::back()->withInput()->with('loginFailure', '1');
            }
            
            // attempt to do the login
            if (Auth::attempt($data, $remember)) {
                Session::forget('loginFailure');
                // XXX: IMPORTANT - validation successful and we move to intended url or default dashboard
                return Redirect::intended('/dashboard');
            
            } else {
                // validation not successful, send back to form
                return Redirect::back()->withInput()->with('loginFailure', '1');
            }
        }
    }
    
    public function getSignout()
    {
        // XXX: IMPORTANT - log the user out of our application
        Auth::logout();
        return Redirect::to('/');
    }
    
    public function getSignup()
    {
        return View::make('signup.signup');
    }
    
    public function postSignup()
    {
        // XXX: IMPORTANT - get all post data in one variable to reduce the call for Input::get
        $postData = Input::all();
        
        // validate the info, create rules for the inputs
        $rules = array(
            'email'       => 'required|email|unique:users',
            'password'    => 'required|max:32|min:6',
            'repassword'  => 'same:password',
            'agree'       => 'required'
        );
        
        Validator::getPresenceVerifier()->setConnection('schoolerUsers');
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($postData, $rules);
        
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // XXX: IMPORTANT - send back the input so that we can repopulate the form
            return Redirect::to('/signup')->withErrors($validator)->withInput();
        } else {
            $code    = bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
            $userId  = Aloha::createUserId($postData, $code);
            
            if ($userId) {
                if (App::environment('production')) {
                    $data = array(
                        'email' => $postData['email'],
                        'confirmationLink' => 'https://schooler.com/signup/verify?c=' . $code
                    );
                    $mailSubject = 'Schooler - Verify your email address';
                } else {
                    $data = array(
                        'email' => $postData['email'],
                        'confirmationLink' => 'http://local.schooler.com/signup/verify?c=' . $code
                    );
                    $mailSubject = 'DEVELOPMENT: Schooler - Verify your email address';
                }
                
                // XXX: IMPORTANT - send an email for verification
                Mail::queue('emails.signupVerification', $data, function ($message) use($mailSubject, $postData) {
                    $message->from('schooler.noreply@gmail.com', 'Noreply');
                    $message->to($postData['email'])
                    ->subject($mailSubject);
                });
                
                return View::make('signup.verifySignupMailSuccess');
            }
            
            // XXX: IMPORTANT - unknown system/database error
            return Redirect::to('/signup')->with('error', 'A system error has occurred. Please try again.');
        }
    }
    
    public function getSignupVerify()
    {
        $code = Input::get('c');
        
        $codeInfo = Aloha::getVerifyCodeInfo($code)->get();
        
        // XXX: IMPORTANT - wrong verification code redirect to signup page again
        if (!count($codeInfo)) {
            return Redirect::to('/signup');
        }
        
        Aloha::completeUserSignup($codeInfo[0]->userId, $codeInfo[0]->email, $codeInfo[0]->code);
        
        return View::make('signup.signupSuccess');
    }
}
