<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User  extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('user');
    }
    public function newpass ()
    {
        $this->model->alter('user/newpass','newpass');
        $this->setResponse();
    }
}