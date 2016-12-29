<?php
/**
 * Created by PhpStorm
 * Date: 19.10.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Navigationmodule
{
    protected $CI;
    private $config;
    private $sideBarController;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->config = [
            'left' => [
                'items' => [],
                'dropdown' => []
            ],
            'right' => [
                'items' => [],
                'dropdown' => []
            ],
            'sidebar' => []
        ];
        $this->sideBarController = [];
    }
    public function getPropertyValue ($property)
    {
        if(property_exists(get_called_class(),$property)) {
            return $this->{$property};
        }
        return null;
    }
    public function getData ($args)
    {
        $routes = $args['routes'];
        $scope = $args['viewScope']['scope'];

        foreach($routes as $key => $val) {

            $pathRole = $this->CI->routemanager->getPathRole($key);
            $pathScope = $this->CI->routemanager->getPathScope($key);
            $routeScope = explode(',',$pathScope);
            $menuElementExists = (array_key_exists('menu_element',$val)) ? true : false;

            if ($this->CI->routemanager->getPathAccess($pathRole,$pathScope)) {
                // get menu items from db
                if ( $menuElementExists && isset($val['menu_element']['model'])) {
                    if (in_array('sidebar',$val['menu_element']['type']) || in_array('dropdown',$val['menu_element']['type'])) {
                        $val['menu_element']['menu_items'] = $this->getItemsFormDb ($val['menu_element']);
                    }
                }
                // set main navigation
                if (in_array($scope,$routeScope) && $val['show_in_menu'] === true)
                {
                    $this->setMainNavigation($key, $val, $menuElementExists);
                }
                // set sidebar navigation
                if ( $menuElementExists === true )
                {
                    $this->setSideBar($args, $val);
                }
            }
        }
        return $this->config;
    }
    private function setMainNavigation ($key, $val, $menuElementExists)
    {
        if (array_key_exists('menu_float',$val) && array_key_exists($val['menu_float'],$this->config)) {

            if (array_key_exists('label',$val)) {
                $val['label'] = $this->getLangLabel($val['label']);
            }

            if ($menuElementExists === true) {

                if (array_key_exists('menu_label',$val['menu_element'])) {
                    $val['menu_element']['menu_label'] = $this->getLangLabel($val['menu_element']['menu_label']);
                }

                if (array_key_exists('module',$val['menu_element'])) {
                    if (in_array('dropdown',$val['menu_element']['type'])) {
                        $val['menu_element']['menu_items'] = $this->CI->modulemanager->getModuleData($val['menu_element']['module']);
                        $val['menu_element']['menu_items'] = $this->setSubConfig($val['menu_element']['menu_items']);
                        $this->config[$val['menu_float']]['dropdown'][] = $val['menu_element'];
                    }
                } else if (in_array('dropdown',$val['menu_element']['type'])) {
                    $val['menu_element']['menu_items'] = $this->setSubConfig($val['menu_element']['menu_items']);
                    $this->config[$val['menu_float']]['dropdown'][] = $val['menu_element'];

                }
            } else {

                $item = ['path'=>$key,'label'=>$val['label']];

                if (array_key_exists('menu_icon',$val)) {
                    $item['menu_icon'] = $val['menu_icon'];
                }

                $this->config[$val['menu_float']]['items'][] = $item;
            }
        }
    }
    private function setSideBar ($args, $val)
    {
        if (in_array('sidebar',$val['menu_element']['type']) && $args['activeView'] == $val['menu_element']['path'])
        {
            $val['menu_element']['menu_items'] = $this->setSubConfig($val['menu_element']['menu_items']);
            $this->config['sidebar'] = $val['menu_element'];
            $this->sideBarController[] = $val['menu_element']['path'];
        }
    }
    private function setSubConfig ($data)
    {
        $res = [];
        foreach($data as $key => $val) {
            if (array_key_exists('label',$val)) {
                $val['label'] = $this->getLangLabel($val['label']);
            }
            if (array_key_exists('role',$val) && array_key_exists('scope',$val)) {
                if ($this->CI->routemanager->getPathAccess($val['role'],$val['scope'])) {
                    $res[] = $val;
                }
            } else {
                $res[] = $val;
            }
        }
        return $res;
    }
    private function getLangLabel ($str)
    {
        $label = lang($str);
        if ($label == '') {
            $label = $str;
        }
        return $label;
    }
    private function getItemsFormDb ($menu)
    {
        $res = [];

        if( ! method_exists($this->CI, $menu['model'])){
            $this->CI->load->model($menu['model']);
            $res = $this->CI->{$menu['model']}->menuItems($menu);
        }

        return $res;
    }
}