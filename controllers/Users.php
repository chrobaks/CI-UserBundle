<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
    function __construct()
    {
        parent::__construct('users');
    }
    public function add()
    {
        $this->model->add();
        parent::add();
    }
    public function all()
    {
        $this->model->all();
        parent::all();
    }
}
