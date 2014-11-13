<?php

use Illuminate\Support\Facades\Auth;

class PictureController extends BaseController
{
    public function getProfilePic0()
    {
        $USER_PROFILE = App::make('getUserProfile');
    
        // open the file in a binary mode
        $picName = public_path() . '/uploadFiles/realProfilePic/' . $USER_PROFILE['p0'];
        $fp      = fopen($picName, 'rb');
    
        // send the right headers
        header('Content-Type: ' . mime_content_type($picName));
        header('Content-Length: ' . filesize($picName));
    
        // dump the picture and stop the script
        fpassthru($fp);
        exit;
    }
    
    public function getProfilePic1()
    {
        $USER_PROFILE = App::make('getUserProfile');
        
        // open the file in a binary mode
        $picName = public_path() . '/uploadFiles/realProfilePic/' . $USER_PROFILE['p1'];
        $fp      = fopen($picName, 'rb');
        
        // send the right headers
        header('Content-Type: ' . mime_content_type($picName));
        header('Content-Length: ' . filesize($picName));
        
        // dump the picture and stop the script
        fpassthru($fp);
        exit;
    }
    
    public function getProfilePic2()
    {
        $USER_PROFILE = App::make('getUserProfile');
    
        // open the file in a binary mode
        $picName = public_path() . '/uploadFiles/realProfilePic/' . $USER_PROFILE['p2'];
        $fp      = fopen($picName, 'rb');
    
        // send the right headers
        header('Content-Type: ' . mime_content_type($picName));
        header('Content-Length: ' . filesize($picName));
    
        // dump the picture and stop the script
        fpassthru($fp);
        exit;
    }
    
    public function getProfileTmpPic()
    {
        // open the file in a binary mode
        $picName = public_path() . '/uploadFiles/tmpProfilePic/' . md5(Auth::user()->email);
        $fp      = fopen($picName, 'rb');
    
        // send the right headers
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Pragma: no-cache');
        header('Content-Type: ' . mime_content_type($picName));
        header('Content-Length: ' . filesize($picName));
    
        // dump the picture and stop the script
        fpassthru($fp);
        exit;
    }
}
