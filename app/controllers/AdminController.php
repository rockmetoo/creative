<?php

class AdminController extends BaseController
{
    public function __construct()
    {
        // XXX: IMPORTANT - check user type
        $this->beforeFilter('isAdmin');
        
        // XXX: IMPORTANT - check that this controller is set for the current user
        $this->beforeFilter('isACLSetForAdminController');
    }
    
    public function getAdminList()
    {
        $res    = Admin::countTotalAdminData()->get();
        
        $count  = 0;
        
        if (count($res)) $count = $res[0]->count;
        
        if ($count) {
            $paginator = new CPaginator();
        
            $paginator->items_total	= $count;
            $paginator->mid_range	= 9;
            $paginator->paginate();
        
            $adminDataByPage = Admin::getAdminDataByPage($paginator->limit);
            
            return View::make('admin.adminList', [ 'paginator' => $paginator, 'adminDataByPage' => $adminDataByPage ]);
        
        } else {
            return View::make('admin.adminEmpty');
        }
    }
    
    public function getAdminAdd()
    {
        return View::make('admin.adminAdd');
    }
    
    public function postAdminAdd()
    {
        // XXX: IMPORTANT - get all post data in one variable to reduce the call for Input::get
        $postData = Input::all();
        
        $yesterday = date('Y-m-d', strtotime('yesterday'));
        
        // validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|email|unique:users',
            'password' => 'required|max:32|min:5',
        );
        
        Validator::getPresenceVerifier()->setConnection('aloha');
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($postData, $rules);
        
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // send back the input so that we can repopulate the form
            return Redirect::to('/admin/add')->withErrors($validator)->withInput();
        } else {
            Admin::insertAdmin($postData);
            return View::make('admin.addSuccess');
        }
    }
    
    public function getUserPasswordUpdate($userId)
    {
        return View::make('admin.userPassword', [ 'userId' => $userId ]);
    }
    
    public function postUserPasswordUpdate($userId)
    {
        // XXX: IMPORTANT - get all post data in one variable to reduce the call for Input::get
        $postData = Input::all();
        
        $yesterday = date('Y-m-d', strtotime('yesterday'));
        
        // validate the info, create rules for the inputs
        $rules = array(
            'password'    => 'required|max:32|min:5',
            'repassword'  => 'same:password'
        );
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($postData, $rules);
        
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // send back the input so that we can repopulate the form
            return Redirect::to('/admin/user/update/password/' . $userId)->withErrors($validator)->withInput();
        } else {
            Admin::updateUserPassword($userId, $postData);
            return View::make('admin.updatePasswordSuccess');
        }
    }
    
    public function getUserAclUpdate($userId)
    {
        $userAclSettings = Admin::getUserAcl($userId)->get();
        
        if (count($userAclSettings)) $userAclSettings = json_decode($userAclSettings[0]->acl);
        else {
            // XXX: IMPORTANT - just add userId in profile table
            Admin::insertJustUserIdInAcl($userId);
        
            $userAclSettings = Admin::getUserAcl($userId)->get();
            $userAclSettings = json_decode($userAclSettings[0]->acl);
        }
        
        return View::make('admin.userAcl', [ 'userId' => $userId, 'userAclSettings' => $userAclSettings ]);
    }
    
    public function postUserAclUpdate($userId)
    {
        // XXX: IMPORTANT - get all post data in one variable to reduce the call for Input::get
        $postData = Input::all();
    
        $yesterday = date('Y-m-d', strtotime('yesterday'));
    
        // validate the info, create rules for the inputs
        $rules = array('acl'    => 'required');
    
        // run the validation rules on the inputs from the form
        $validator = Validator::make($postData, $rules);
    
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // send back the input so that we can repopulate the form
            return Redirect::to('/admin/user/update/acl/' . $userId)->withErrors($validator)->withInput();
        } else {
            Admin::updateUserAcl($userId, $postData);
            return View::make('admin.updateAclSuccess');
        }
    }
}
