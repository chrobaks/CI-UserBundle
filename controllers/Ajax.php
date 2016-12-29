<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

    function __construct()
    {
        parent::__construct('ajax');
    }
    public function index() {}
    public function request ()
    {
        $this->model->module();
        $this->setResponse();
    }
    public function help ()
    {
        $this->model->help();
        $this->setResponse();
    }
    public function contact ()
    {
        $this->model->alter('ajax/contact','setContact');
        $this->setResponse();
    }
}