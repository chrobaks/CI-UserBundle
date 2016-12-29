<?php
/**
 * Created by PhpStorm.
 * Date: 19.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewmanager
{
    protected $CI;
    private $config;
    private $configDevTool;
    private $configPathPublic;
    private $entryPath;
    private $menuElementPath;
    private $viewController;
    private $viewSubController;
    private $viewData;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->config->load('config_public');
        $this->configPathPublic = $this->CI->config->item('config_public');
        $this->config = $this->CI->config->item('config_view');
        $this->configDevTool = $this->CI->envservice->getConfig('devTool');
        $this->entryPath = $this->CI->routemanager->getDefaultEntryPath();
        $this->menuElementPath = '';
        $this->viewData = [];
        $this->viewController = '';
        $this->viewSubController = '';

        $this->initViewData ();
    }
    public function setViewData ($viewData)
    {
        if (is_array($viewData) && ! empty($viewData)) {
            foreach ($viewData as $k => $v) {
                $this->viewData['tplData'][$k] = $v;
            }
        }
    }
    public function setViewTpl ($tplName)
    {
        $this->viewData['tpl'] = $tplName;
    }
    public function editTplTitle ($tplTitle)
    {
        $this->viewData['tplTitle'] = $tplTitle;
    }
    public function renderView ()
    {
        $this->setViewNavigation();
        $this->setViewMessage();
        $this->devTool();

        $this->CI->load->view($this->config['base_view'],$this->viewData);
    }
    public function property ($key)
    {
        if(array_key_exists($key,$this->viewData)) {
            return $this->viewData[$key];
        }
        return '';
    }
    public function setProperty ($key, $val)
    {
        $this->viewData[$key] = $val;
    }
    private function initViewData ()
    {
        // set enviroment vars
        $this->setViewController();
        $this->setMenuElementPath();
        // set view vars
        $this->setDefaultView();
        // set template title
        $this->setTplTitle();
        // set boolean filter navigation visibilty
        $this->setShowNavigation();
        // set actual navigation path
        $this->setActiveNavigationPath();
    }
    private function setViewController ()
    {
        $routePath = $this->CI->routemanager->getRoutePath();

        if (is_array($routePath) && ! empty($routePath))
        {
            $this->viewController = $routePath[0];

            if (count($routePath) > 1)
            {
                $this->viewSubController = $routePath[1];
            }
        }
    }
    private function setMenuElementPath ()
    {
        if(array_key_exists('menu_element_path',$this->config['routes'][$this->viewController]) && ! empty($this->config['routes'][$this->viewController]['menu_element_path'])) {
            $this->menuElementPath = $this->config['routes'][$this->viewController]['menu_element_path'];
        }
    }
    private function setDefaultView ()
    {
        $this->viewData = [
            'sessionUser' => $this->CI->session->userdata(),
            'viewController' => $this->viewController,
            'viewSubController' => $this->viewSubController,
            'routeScope' => $this->CI->routemanager->getRouteScope(),
            'csrf_token_name' => $this->CI->security->get_csrf_token_name(),
            'csrf_hash' => $this->CI->security->get_csrf_hash(),
            'title' => $this->config['title'],
            'tpl' => $this->CI->routemanager->getRouteView(),
            'tplTitle' => '',
            'tplData' => [],
            'tplServices' => '',
            'navigation' => [],
            'activeNavigationPath' => '',
            'showNavigation' => true,
            'javascript' => $this->renderJavascript(),
            'stylesheet' => $this->renderStylesheets(),
            'devTool' => $this->configDevTool
        ];
    }
    private function setTplTitle ()
    {
        $this->viewData['tplTitle'] = $this->CI->lang->line($this->config['routes'][$this->viewController]['label']);
        $subController = $this->viewSubController;

        if ($this->entryPath != '' && $subController == '')
        {
            $defaultEntryPath = explode('/',$this->entryPath);
            $subController = (count($defaultEntryPath)>1) ? $defaultEntryPath[1]:'';
        }
        if ($subController != '' && array_key_exists('menu_element',$this->config['routes'][$this->viewController]) )
        {
            if(array_key_exists('menu_items',$this->config['routes'][$this->viewController]['menu_element'])) {
                if(array_key_exists($subController,$this->config['routes'][$this->viewController]['menu_element']['menu_items'])) {
                    $label = $this->config['routes'][$this->viewController]['menu_element']['menu_items'][$subController]['label'];
                    $this->viewData['tplTitle'] = $this->CI->lang->line($label);
                }
            }
        }
    }
    private function setActiveNavigationPath ()
    {
        $path = $this->CI->routemanager->getRoutePathValues();
        $entryPath = explode('/',$this->entryPath);
        $this->viewData['activeNavigationPath'] = [
            'menuActive' => '',
            'menuItemActive' => implode('/',$path),
            'sidebarItemActive' => implode('/',$path)
        ];

        if ($this->menuElementPath != '') {

            $this->viewData['activeNavigationPath']['menuActive'] = $this->menuElementPath;
            $this->viewData['activeNavigationPath']['menuItemActive'] = $this->viewController;

            if (count($entryPath) > 1 && $this->viewSubController == '') {
                $this->viewData['activeNavigationPath']['sidebarItemActive'] = $this->viewController.'/'.$entryPath[1];
            } else {
                $this->viewData['activeNavigationPath']['sidebarItemActive'] = $this->viewController.'/'.$this->viewSubController;
            }
        } else {
            if ($this->entryPath != '' && count($path) < 3) {
                $this->viewData['activeNavigationPath']['menuActive'] = $this->entryPath;
            } else {
                if ( count($path) > 2) {
                    $this->viewData['activeNavigationPath']['menuActive'] = $path[0];
                } else {
                    $this->viewData['activeNavigationPath']['menuActive'] = $this->viewController;
                }
            }
        }
    }
    private function setShowNavigation ()
    {
        if(array_key_exists('show_navigation',$this->config['routes'][$this->viewController])) {
            $this->viewData['showNavigation'] = $this->config['routes'][$this->viewController]['show_navigation'];
        }
    }
    private function setViewMessage ()
    {
        $error = $this->CI->messagemanager->getError();
        $info = $this->CI->messagemanager->getInfo();
        $this->viewData['tplData']['msg'] = ($info != '') ? $info : '';
        $this->viewData['tplData']['error'] = ($error != '') ? $error : '';
        $this->viewData['readyMessage'] = $this->CI->sessionservice->get('readyMessage');
        $this->CI->sessionservice->set(['readyMessage'=>'']);
    }
    private function devTool ()
    {
        if ($this->configDevTool == true) {
            $this->viewData['envData'] = [];
            $filter = ['javascript', 'stylesheet', 'devTool', 'envData'];

            foreach ($this->viewData as $k => $v) {
                if (!in_array($k, $filter)) {
                    $this->viewData['envData'][$k] = $v;
                }
            }
        }
    }
    private function setViewNavigation ()
    {
        if ($this->viewData['showNavigation'] === true)
        {
            $args = [
                'activeView'=>$this->viewController,
                'routes'=>$this->config['routes'],
                'viewScope'=>$this->CI->routemanager->getRouteScope()
            ];
            $this->viewData['navigation'] = $this->CI->modulemanager->getModuleData('navigation',$args);
        }
    }
    private function renderStylesheets ()
    {
        $tpl = [];
        $path = $this->configPathPublic['path'];
        $cssPath = $path['public'].$path['stylesheet'];

        if (isset($this->configPathPublic['libaries']['stylesheet'])) {

            $libs = explode(',',$this->configPathPublic['libaries']['stylesheet']);

            foreach($libs as $lib){
                $tpl[] = '<link href="'.site_url().$cssPath.trim($lib).'.css" rel="stylesheet">';
            }
        }
        return $tpl;
    }
    private function renderJavascript ()
    {
        $tpl = [];
        $path = $this->configPathPublic['path'];
        $jsPath = $path['public'].$path['javascript'];
        $appPath = $path['public'].$path['app'];
        // store third party libaries
        if (isset($this->configPathPublic['libaries']['javascript'])) {
            $libs = explode(',',$this->configPathPublic['libaries']['javascript']);
            foreach($libs as $lib){
                $tpl[] = '<script src="'.site_url().$jsPath.trim($lib).'.js" type="text/javascript"></script>';
            }
        }
        // store app libaries
        if (isset($this->configPathPublic['libaries']['app'])) {
            $libs = explode(',',$this->configPathPublic['libaries']['app']);
            foreach($libs as $lib){
                $tpl[] = '<script src="'.site_url().$appPath.trim($lib).'.js" type="text/javascript"></script>';
            }
        }
        return $tpl;
    }
}