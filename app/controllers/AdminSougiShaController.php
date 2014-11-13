<?php

class AdminSougiShaController extends BaseController
{
    public function __construct()
    {
        // XXX: IMPORTANT - check user type
        $this->beforeFilter('isAdmin');
        
        // XXX: IMPORTANT - check that this controller is set for the current user
        $this->beforeFilter('isACLSetForSougiShaController');
    }
    
    public function getSougiShaList()
    {
        $res    = SougiSha::countTotalSougiShaData()->get();
        
        $count  = 0;
        
        if (count($res)) $count = $res[0]->count;
        
        if ($count) {
            $paginator = new CPaginator();
        
            $paginator->items_total	= $count;
            $paginator->mid_range	= 9;
            $paginator->paginate();
        
            $sougishaDataByPage = SougiSha::getSougiShaDataByPage($paginator->limit);
        
            return View::make('sougisha.sougishaList', [ 'paginator' => $paginator, 'sougishaDataByPage' => $sougishaDataByPage ]);
        
        } else {
            return View::make('sougisha.sougishaEmpty');
        }
    }
    
    public function getSougiShaSignupRequest($userId)
    {
        $res = SougiSha::sougiShaSignupRequestDataByUserId($userId)->get();
        
        if (!count($res)) {
            return View::make('sougisha.sougishaSignupEmpty');
        }
        
        return View::make('sougisha.sougishaSignupConfirm', [ 'signupData' => $res[0] ]);
    }
}
