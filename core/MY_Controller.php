<?php
/**
 * Created by PhpStorm
 * Date: 27.10.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $model;
    protected $controllerName;

    function __construct($controllerName = '')
    {
        parent::__construct();

        $this->model = null;

        if ($controllerName !== '')
        {
            $this->load->model($controllerName.'model');
            $this->model = $this->{$controllerName.'model'};
            $this->controllerName = $controllerName;
        }
    }
    public function index()
    {
        $entryPath = $this->routemanager->getDefaultEntryPathAction(get_called_class());

        if (! empty($entryPath)) {
            $this->{$entryPath}();
        } else {
            $this->setView();
        }
    }
    public function all()
    {
        $this->setView();
    }
    public function add()
    {
        $this->setView();
    }
    public function create ()
    {
        $this->model->alter($this->controllerName.'/create','create');
        $this->setResponse();
    }
    public function update ()
    {
        $this->model->alter($this->controllerName.'/update','update');
        $this->setResponse();
    }
    public function delete ()
    {
        $this->model->alter('delete','delete');
        $this->setResponse();
    }
    public function help()
    {
        $this->setView();
    }
    protected function setView ()
    {
        if ($this->model !== null)
        {
            $this->viewmanager->setViewData($this->model->getView());
        }
        $this->viewmanager->renderView();
    }
    protected function setResponse ($message = '', $error = '', $responseData = '')
    {
        if ($this->model !== null) {
            $responseData = $this->model->getResponseData();
        }

        $this->load->library('service/ajaxservice');
        $this->ajaxservice->response($message, $error, $responseData);
    }
}