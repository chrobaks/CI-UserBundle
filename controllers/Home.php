<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $routeScope = $this->routemanager->getRouteScope();

        if ($routeScope['scope'] === 'public' || $routeScope['scope'] === 'protected' && $routeScope['userRole'] === '')
        {
            $this->viewmanager->editTplTitle('Welcome');
            $this->viewmanager->setViewTpl('home/welcome');
        }
        $this->setView();
    }
}
