<?php

class ProfileController extends BaseController
{
    // XXX: IMPORTANT - check user auth status
    public function __construct()
    {
        $this->beforeFilter('isUser');
    }
    
    public function getProfile()
    {
        // XXX: IMPORTANT - get user profile info
        $profile = Profile::getProfile(Auth::user()->userId)->get();
        
        if (!count($profile)) {
            // XXX: IMPORTANT - just add userId in profile table
            Profile::insertJustUserId();
            
            $profile = Profile::getProfile(Auth::user()->userId)->get();
        }
        
        return View::make('profile.index', ['profile' => $profile]);
    }
    
    public function postProfile()
    {
        // XXX: IMPORTANT - get all post data in one variable to reduce the call for Input::get
        $postData = Input::all();
        $postData['profilePictureUpload'] = Input::file('profilePictureUpload');
        
        // validate the info, create rules for the inputs
        $rules = array(
            'firstName'    => 'max:128',
            'lastName'     => 'max:128',
            'postCode'     => 'max:12',
            'address'      => 'max:255',
            'profilePictureUpload' => 'image|max:10000' // 10MB max
        );
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($postData, $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // send back the input so that we can repopulate the form
            return Redirect::to('/profile')->withErrors($validator)->withInput();
        } else {
            Profile::saveProfile(Auth::user()->userId, $postData);
            return Redirect::to('/profile')->with('success', 'Profile has been updated successfully ');
        }
    }
    
    public function postTemporaryProfilePicture()
    {
        $profilePictureFile = Input::file('profilePictureFile');
        
        //$filename = $profilePictureFile->getClientOriginalName();
        //$extension =$profilePictureFile->getClientOriginalExtension();
        
        $destinationPath = public_path() . '/uploadFiles/tmpProfilePic/';
        
        $md5Name = md5(Auth::user()->email);
        $uploadSuccess = Input::file('profilePictureFile')->move($destinationPath, $md5Name);
        
        if ($uploadSuccess) {
            // XXX: IMPORTANT - update pTmp in profile table
            Profile::updateTemporaryPicture($md5Name);
            
            $jsonData = array('error' => 0, 'message' => '');
            return Response::json(json_encode($jsonData, JSON_HEX_QUOT | JSON_HEX_APOS));
        } else {
            $jsonData = array('error' => 1, 'message' => 'Oops! some error happened. Please try again.');
            return Response::json(json_encode($jsonData, JSON_HEX_QUOT | JSON_HEX_APOS));
        }
    }
}
