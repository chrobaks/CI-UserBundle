<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entities extends MY_Controller
{
    function __construct()
    {
        parent::__construct('entities');
    }
    public function all()
    {
        $this->model->all();
        parent::all();
    }
    public function add()
    {
        $this->model->add();
        parent::add();
    }
    public function sync()
    {
        $this->model->sync();
        $this->setView();
    }
    public function getconfig()
    {
        $this->model->alter('entities/getconfig','getIntegrationConf');
        $this->setResponse();
    }
    public function gettablecols ()
    {
        $this->model->alter('entities/gettablecols','getTableConf');
        $this->setResponse();
    }
    public function tableisdependence ()
    {
        $this->model->alter('entities/gettablecols','tableIsDependence');
        $this->setResponse();
    }
    public function getnewconf ()
    {
        $this->model->alter('entities/gettablecols','getNewConf');
        $this->setResponse();
    }
    public function createconf ()
    {
        $this->model->alter('entities/masterconf','createConf');
        $this->setResponse();
    }
    public function updateconf ()
    {
        $this->model->alter('entities/masterconf','updateConf');
        $this->setResponse();
    }
    public function deleteentitiy ()
    {
        $this->model->alter('delete','deleteEntitiy');
        $this->setResponse();
    }
    public function deleteconfig ()
    {
        $this->model->alter('delete','deleteConfig');
        $this->setResponse();
    }
    public function createfileconf ()
    {
        $this->model->alter('entities/syncname','createFileConf');
        $this->setResponse();
    }
    public function createdbconf ()
    {
        $this->model->alter('entities/syncname','createDbConf');
        $this->setResponse();
    }
    public function updateappdependence ()
    {
        $this->model->alter('entities/syncname','updateAppDependence');
        $this->setResponse();
    }
}
