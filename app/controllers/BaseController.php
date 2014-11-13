<?php

class BaseController extends Controller
{
    protected $ACL_SETTINGS;
    
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        // CSRF protection for all POST requests
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

}
