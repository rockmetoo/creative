<?php

class IndexController extends BaseController
{
    public function getIndex()
    {
        if (!Auth::check()) {
            return View::make('index');
        }
        return Redirect::to('/dashboard');
    }
}
