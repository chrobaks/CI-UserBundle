<?php
/**
 * Created by PhpStorm
 * Date: 27.10.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $post;
    protected $view;
    protected $entity;
    protected $entityId;
    protected $modelActionOk;
    protected $modelFilter;
    protected $responseData;
    protected $filterProperty;
    protected $filteredPagination;
    protected $entityObject;
    protected $entryCounts;
    protected $indexKey;
    protected $configPagination;
    private $isPostAction;

    public function __construct($entity = '')
    {
        parent::__construct();

        $this->load->helper(['date','form']);
        $this->load->library('form_validation');

        $this->configPagination = $this->envservice->getConfig('config_pagination');
        $this->modelFilter = $this->routemanager->getRoutePathFilter();
        $this->isPostAction = false;
        $this->post = $this->filterPost();
        $this->entity = null;
        $this->entityId = 0;
        $this->modelActionOk = false;
        $this->responseData = '';
        $this->filterProperty = [];
        $this->filteredPagination = [];
        $this->entityObject = null;
        $this->entryCounts = 0;
        $this->indexKey = null;
        $this->view = [
            'filter' => ''
        ];

        if($entity != '')
        {
            $this->loadEntity($entity);
        }
        $this->filterPagination();
    }
    public function alter ($formPath, $action)
    {
        if ($this->form_validation->run($formPath) === TRUE)
        {

            if (method_exists(get_called_class(), $action))
            {
                $this->{$action}();
            } else {
                $this->messagemanager->setError('page_error_application');
            }
        } else {
            $this->messagemanager->setError('page_error_data_notvalid', validation_errors());
        }
    }
    public function create ()
    {
        $this->entity->create($this->post);
    }
    public function update ()
    {
        $this->entity->update($this->post, ['id'=>$this->post['id']]);
    }
    public function delete ()
    {
        if ($this->entity->delete($this->post))
        {
            $this->messagemanager->setInfo('page_info_delete_success');
        }
    }
    public function actionOk ()
    {
        return $this->modelActionOk;
    }
    public function postEmpty ()
    {
        return !(isset($this->post) && ! empty($this->post));
    }
    public function postValue ($key)
    {
        return (isset($this->post[$key])) ? $this->post[$key] : '';
    }
    public function getView ()
    {
        return $this->view;
    }
    public function getColFormRule ($prefix)
    {
        $res = '';
        foreach($this->post as $k=>$v){
            if ($k!=='id') {
                $res = $prefix.$k;
                break;
            }
        }
        return $res;
    }
    public function getResponseData ()
    {

        if ($this->isPostAction)
        {
            $this->responseData['csrfn'] = $this->security->get_csrf_token_name();
            $this->responseData['csrfv'] = $this->security->get_csrf_hash();
        }
        return $this->responseData;
    }
    protected function getIdArray ($arr, $columnName)
    {
        $res = [];

        if( is_array($arr) && ! empty($arr))
        {
            $columnName = explode(',', $columnName);

            foreach( $arr as $row){
                if (! empty($columnName) && count($columnName) > 1)
                {
                    $res[trim($row[$columnName[0]])] = $row[trim($columnName[1])];
                }
            }
        }
        return $res;
    }
    protected function getMenuItems ($menu, $entityItems)
    {
        $res = [];
        $count = count($menu['menu_items']);

        if ( $count > 1)
        {
            $itemClone = $menu['menu_items'][($count-1)];

            foreach($menu['menu_items'] as $k=>$v){
                if (($count-1) > $k ) { $res[] = $v; }
            }
        } else {
            $itemClone = $menu['menu_items'][0];
        }

        if ($entityItems !== null)
        {
            foreach($entityItems as $item) {

                $itemCopy = $itemClone;
                $itemCopy['path'] .= '/'.$item['path'];
                $itemCopy['label'] = $item['label'];

                if (isset($item['subitems']) && ! empty($item['subitems'])) {

                    foreach($item['subitems'] as $key=>$val){
                        $item['subitems'][$key]['path'] = $itemCopy['path'].'/'.$val['path'];
                    }
                    $itemCopy['subitems'] = $item['subitems'];
                }
                $res[] = $itemCopy;
            }
        }

        return $res;
    }
    protected function setRedirectResponse ($langKey = '')
    {
        if( ! $this->messagemanager->errorLength())
        {
            $langKey = (empty($langKey)) ? 'page_info_last_action_success' : $langKey;

            $this->messagemanager->setRedirectInfo($langKey);
            $this->responseData = ["redirect"=>"redirect"];
        }
    }
    protected function setView ($key, $val)
    {
        $this->view[$key] = $val;
    }
    protected function setViewPagination ($actualLimit='', $limitStep='')
    {
        $actualLimit = ($actualLimit !== '') ? $actualLimit : $this->filteredPagination['actualLimit'];
        $limitStep = (strlen(trim($limitStep))) ? $limitStep : $this->filteredPagination['limitStep'];

        $this->view['entryCount'] = $this->entryCounts;
        $this->view["pagination"] = [
            'selection' => $this->configPagination["selecetion"],
            'actualLimit' => ($this->filteredPagination['actualLimit']) ? $this->filteredPagination['actualLimit'] : 'all',
            'data' => $this->paginationmodule->getPagination( $this->entryCounts, $actualLimit, $limitStep, $this->configPagination["maxItems"])
        ];
    }
    protected function setViewMaxZIndex ($key = 'maxZIndex', $dependenceEntity = '')
    {
        if ($dependenceEntity === '') {
            $query = $this->entity->find('max(zIndex) as maxIndex',[],1);
        } else {
            $query = $this->entity->findDependence($dependenceEntity, 'max(zIndex) as maxIndex',[],1);
        }

        $this->view[$key] = ($query === null ) ? 0 : $query->maxIndex;
    }
    protected function setEntityObject ($data) {
        $res = false;
        if ($data !== null)
        {
            $this->entityObject = $data[0];
            $res = true;
        }
        return $res;
    }
    protected function filterPagination ()
    {
        $res = [
            'limit' => '',
            'actualLimit' => $this->configPagination['limit']['limitmax'],
            'limitStep' => $this->configPagination["limit"]["limitstep"]
        ];

        if (array_key_exists('pages',$this->modelFilter))
        {
            if ( $this->modelFilter['pages'] != 'all')
            {
                $res['limit'] = $this->modelFilter['pages'];
                $res['actualLimit'] = $this->modelFilter['pages'];

            } else {
                $res['limit'] = 0;
                $res['actualLimit'] = 0;
            }
        }
        if (isset($this->modelFilter['page']) && ! empty($this->modelFilter['page']))
        {
            $offsetLimit = (isset($this->modelFilter['pages'])) ? $this->modelFilter['pages'] : $this->configPagination['limit']['limitmax'];

            if ( $this->modelFilter['page'] != 0 && ! empty($offsetLimit))
            {
                $offset = $offsetLimit*$this->modelFilter['page'];
                $res['limit'] = [$res['limit'],$offset];
                $res['limitStep'] = $this->modelFilter['page'];
            }
        }

        $this->filteredPagination = $res;
    }
    private function filterPost ()
    {
        $res = [];

        if ( $this->input->post())
        {
            $csrfName = $this->security->get_csrf_token_name();

            foreach($this->input->post() as $k=>$v){
                if ($k !== $csrfName) {
                    if (is_string($v)) {
                        $v = trim($v);
                    }
                    $res[$k] = $v;
                }
            }

            $res = $this->security->xss_clean($res);
            $this->isPostAction = true;
        }

        return $res;
    }
    private function loadEntity ($entity)
    {
        $this->entity = $this->entitymanager->instance($entity);

        if( $this->entity == null)
        {
            $this->load->library('entity/'.$entity);

            if ( class_exists($entity, false))
            {
                $this->entity = $this->{$entity};
                $this->indexKey = $this->entity->indexKey();
                $dependence = $this->entity->getDependenceEntities();

                if (is_array($dependence) && ! empty($dependence))
                {
                    $this->loadEntityDependence($dependence);
                }
            }
        } else {
            $this->indexKey = $this->entity->indexKey();
        }
    }
    private function loadEntityDependence ($dependence)
    {
        if (is_array($dependence) && ! empty($dependence))
        {
            foreach($dependence as $depentity){

                $this->load->library('entity/'.$depentity);
                $subdependence = $this->{$depentity}->getDependenceEntities();

                if (is_array($subdependence) && ! empty($subdependence))
                {
                    $this->loadEntityDependence($subdependence);
                }
            }
        }
    }
}