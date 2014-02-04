<?php
namespace Blog;

Class Controller
{
    public $app;
    public $data = array();

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function isLogged()
    {
    	$user = $this->app['session']->get('user');

    	return empty($user) ? false : $user;
    }
}
