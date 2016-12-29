<?php
/**
 * Created by PhpStorm.
 * Date: 03.10.2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends MY_Controller
{
    function __construct()
    {
        parent::__construct('signup');
    }
    public function index()
    {
        if ( ! $this->model->postEmpty())
        {
            $this->model->alter('signup','create');
        }
        if ($this->model->actionOk() === true)
        {
            $this->viewmanager->setViewTpl('signup/confirmation');
        }
        $this->setView();
    }
    public function confirmation()
    {
        if ( ! $this->model->postEmpty())
        {
            $this->model->alter('confirmation','confirmation');
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
