<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appmessage extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function auth()
    {
        $this->messagemanager->setError('page_error_app_auth');
        $this->viewmanager->renderView();
    }
}
