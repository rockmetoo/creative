<?php


class ForgotPasswordController extends BaseController
{
    public function getForgotPassword()
    {
        return View::make('forgot.password');
    }
    
    public function postForgotPassword()
    {
        // XXX: IMPORTANT - get all post data in one variable to reduce the call for Input::get
        $postData = Input::all();
        
        // validate the info, create rules for the inputs
        $rules = array('email' => 'required|email|exists:users,email');
        
        Validator::getPresenceVerifier()->setConnection('schoolerUsers');
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($postData, $rules);
        
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // XXX: IMPORTANT - send back the input so that we can repopulate the form
            return Redirect::to('/forgot/password')->withErrors($validator)->withInput();
        } else {
            
            $code = bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));

            // XXX: IMPORTANT - check if there is an entry already exist regarding this user
            $verifyCode = Aloha::checkAnyVerificationCodeByEmail($postData['email'])->get();
            
            $userInfo = Aloha::getUserByEmail($postData['email'])->get();
            
            if (!count($verifyCode)) {
                // XXX: IMPORTANT - insert the verification code
                Aloha::insertPasswordVerificationCode($userInfo[0]->userId, $postData['email'], $code);
            } else {
                // XXX: IMPORTANT - update the verification code
                Aloha::updatePasswordVerificationCode($userInfo[0]->userId, $postData['email'], $code);
            }
            
            // XXX: IMPORTANT - send mail to admin
            if (App::environment('production')) {
            
                $data = array(
                    'email' => $postData['email'],
                    'verifyLink' => 'https://schooler.com/forgot/password/verify?code=' . $code
                );
            
                $mailSubject = 'Schooler - Forgot password email verification';
            } else {
            
                $data = array(
                    'email' => $postData['email'],
                    'verifyLink' => 'http://local.schooler.com/forgot/password/verify?code=' . $code
                );
            
                $mailSubject = 'DEVELOPMENT: Schooler - Forgot password email verification';
            }
            
            // XXX: IMPORTANT - send an email to info@simpleso.jp
            Mail::queue('emails.verifyPasswordResetRequest', $data, function ($message) use($mailSubject, $postData) {
                $message->from('schooler.noreply@gmail.com', 'Noreply');
                $message->to($postData['email'])
                ->subject($mailSubject);
            });
            
            return View::make('forgot.verifyEmailSent', [ 'email' => $postData['email'] ]);
        }
    }
    
    public function getVerify()
    {
        $code = Input::get('code');
        
        $codeInfo = Aloha::getForgotPasswordVerifyCodeInfo($code)->get();
        
        if (!count($codeInfo)) {
            return View::make('forgot.invalidVerificationCode');
        }
        
        return View::make('forgot.resetPassword');
    }
    
    public function postVerify()
    {
        // XXX: IMPORTANT - get all post data in one variable to reduce the call for Input::get
        $postData = Input::all();
        
        // validate the info, create rules for the inputs
        $rules = array(
            'newPassword'       => 'required|max:32|min:6',
            'renewPassword'     => 'required|same:newPassword',
            'code'              => 'required'
        );
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($postData, $rules);
        
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // XXX: IMPORTANT - send back the input so that we can repopulate the form
            return Redirect::to('/forgot/password/verify?code=' . $postData['code'])->withErrors($validator)->withInput();
        } else {

            $codeInfo = Aloha::getForgotPasswordVerifyCodeInfo($postData['code'])->get();
            
            if (!count($codeInfo)) {
                return View::make('forgot.invalidVerificationCode');
            }
            
            $password = Hash::make($postData['newPassword']);
            
            Aloha::forgotPasswordUpdate($codeInfo[0]->userId, $codeInfo[0]->email, $password);
            
            // XXX: IMPORTANT - update the verification code to empty
            Aloha::updatePasswordVerificationCode($codeInfo[0]->userId, $codeInfo[0]->email, null);
            
            return View::make('forgot.thanks');
        }
    }
}
