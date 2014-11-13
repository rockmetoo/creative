<?php

class PasswordController extends BaseController
{
    public function getPassword()
    {
        return View::make('password.index');
    }
    
    public function postPassword()
    {
        // XXX: IMPORTANT - get all post data in one variable to reduce the call for Input::get
        $postData = Input::all();
        
        $user = Auth::user();
        
        // validate the info, create rules for the inputs
        $rules = array(
            'currentPassword'   => 'required|max:32|min:6',
            'newPassword'       => 'required|max:32|min:6',
            'renewPassword'     => 'required|same:newPassword'
        );
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($postData, $rules);
        
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // send back the input (not the password) so that we can repopulate the form
            return Redirect::to('/change/password')->withErrors($validator);
        } else {
            if (!Hash::check($postData['currentPassword'], $user->password))
            {
                return Redirect::to('/change/password')->with('error', 'Your old password does not match');
            }
            else
            {
                $user->password = Hash::make($postData['newPassword']);
                $user->lastChangedPassword = date('Y-m-d H:i:s');
                
                if ($user->save()) return View::make('password.thanks');
            }
            
            return Redirect::to('/change/password')->with('error', 'Your password could not be changed');
        }
    }
}
