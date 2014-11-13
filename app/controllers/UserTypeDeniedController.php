<?php

class UserTypeDeniedController extends BaseController
{
    public function getDenied()
    {
        return View::make('errors.userDenied');
    }
}
