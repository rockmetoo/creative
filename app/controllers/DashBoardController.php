<?php

class DashBoardController extends BaseController
{
    // XXX: IMPORTANT - check user auth status
    public function __construct()
    {
        $this->beforeFilter('isUser');
    }
    
    public function getDashBoard()
    {
        $user = Auth::user();
        
        // XXX: IMPORTANT - sign in as student
        if ($user->userStatus == 1) {
            return View::make('dashboard.student');
        }
    }
}
