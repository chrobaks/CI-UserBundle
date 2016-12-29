<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->sessionservice->destroy();
        redirect('/' . $this->config->item('default_public_controller','config_view'));
    }
}
