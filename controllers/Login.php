<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    function __construct()
    {
        parent::__construct('login');
    }

    public function index()
    {
        if ( ! $this->model->postEmpty())
        {
            $this->model->alter('login','login');
        }
        if ($this->model->actionOk() === true)
        {
            redirect('/home');
        }
        $this->setView();
    }
    public function passwordforgot()
    {
        $this->model->alter('passwordforgot','passwordforgot');
        $this->setResponse();
    }
    public function confirmation()
    {
        if ( ! $this->model->postEmpty())
        {
            $this->model->alter('passreset','confirmation');
        } else {
            $this->model->confirmation();
        }
        if ($this->model->actionOk() === true)
        {
            redirect('/home');
        }

        $this->setView();
    }
}
