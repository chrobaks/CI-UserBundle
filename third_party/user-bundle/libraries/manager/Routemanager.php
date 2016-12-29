<?php
/**
 * Created by PhpStorm.
 * Date: 30.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Routemanager
{
    protected $CI;
    private $routeUser;
    private $configView;
    private $viewRoutes;
    private $routePath;
    private $routePathValues;
    private $routeRole;
    private $routeView;
    private $routeSecureConfig;
    private $routeScope;
    private $defaultEntryPath;
    private $secureRoles;
    private $appMessagePath;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->routeUser = $this->CI->session->userdata();
        $this->routeSecureConfig = $this->CI->config->item('config_route_secure');
        $this->secureRoles = $this->routeSecureConfig['roles'];
        $this->secureRoutes = $this->routeSecureConfig['routes'];
        $this->configView = $this->CI->config->item('config_view');
        $this->viewRoutes = $this->configView['routes'];
        $this->routePath = [];
        $this->routePathValues = [];
        $this->routeRole = '';
        $this->routeView = '';
        $this->routeScope = ['scope'=>'public','userRole'=>''];
        $this->defaultEntryPath = '';
        $this->appMessagePath = 'appmessage/auth';
        // set enviroment params
        $this->setRouteScope();
        $this->setRoutePathValues();
        $this->setRoutePath();
        $this->setRouteView ();
        $this->setRouteRole();
        $this->setDefaultEntryPath();
        // check route authentication
        $this->checkAuth();
    }

    public function getRouteScope ()
    {
        return $this->routeScope;
    }
    public function getPathScope ($path)
    {
        $res = (array_key_exists($path,$this->secureRoutes)) ? $this->secureRoutes[$path]['scope'] : '';
        return $res;
    }
    public function getPathRole ($path)
    {
        $res = (array_key_exists($path,$this->secureRoutes)) ? $this->secureRoutes[$path]['role'] : '';
        return $res;
    }
    public function getRoutePath ()
    {
        return $this->routePath;
    }
    public function getRoutePathValues ()
    {
        return $this->routePathValues;
    }
    public function getRoutePathFilter ()
    {
        $totalSegment = $this->CI->uri->total_segments();
        $filterFound = false;
        $filter = [];

        if ($totalSegment > 3)
        {
            $counter = 3;

            while ($counter <= $totalSegment) {
                if ($this->CI->uri->segment($counter) === 'filter') {
                    $k = $this->CI->uri->segment($counter+1);
                    $v = $this->CI->uri->segment($counter+2);
                    $filter[$k] = $v;
                    $counter+=3;
                    $filterFound = true;

                } else if ($filterFound) {
                    $k = $this->CI->uri->segment($counter);
                    $v = $this->CI->uri->segment($counter+1);
                    $filter[$k] = $v;
                    $counter+=2;

                } else {
                    $counter++;
                }
            }
        }
        return $filter;
    }
    public function getRouteView ()
    {
        return $this->routeView;
    }
    public function getRouteRole ()
    {
        return $this->routeRole;
    }
    public function getDefaultEntryPath ()
    {
        return $this->defaultEntryPath;
    }
    public function getDefaultEntryPathAction ($controller)
    {
        $res = '';
        if (! empty($this->defaultEntryPath))
        {
            $entryPath = explode('/',$this->defaultEntryPath);

            if(count($entryPath)>1){
                if (method_exists($controller,$entryPath[1])) {
                    $res = $entryPath[1];
                }
            }
        }
        return $res;
    }
    public function getSecureRoles ()
    {
        return $this->secureRoles;
    }
    public function getPathAccess ($pathRole,$pathScope)
    {
        $access = false;

        if($pathRole != '') {
            $pathRole = explode(',',$pathRole);
        }
        if($pathScope != '') {
            $pathScope = explode(',',$pathScope);
        }
        if ( empty($pathRole) && ! isset($this->routeUser['role'])) {
            if(is_array($pathScope) && in_array('public',$pathScope)) {
                $access = true;
            }
        }
        if ( empty($pathRole) && isset($this->routeUser['role'])) {
            if(in_array('protected',$pathScope)) {
                $access = true;
            }
        }
        if ( ! empty($pathRole) && isset($this->routeUser['role'])) {
            $access = (in_array($this->routeUser['role'],$pathRole)) ? true : false;
        }

        return $access;
    }
    private function setRouteScope ()
    {
        $scope = 'public';
        $userRole = '';

        if (isset($this->routeUser['username']) && isset($this->routeUser['logged_in'])) {
            if ($this->routeUser['logged_in'] === true) {
                $scope = 'protected';
                $userRole = $this->routeUser['role'];
            }
        }
        $this->routeScope = ['scope'=>$scope,'userRole'=>$userRole];
    }
    private function setRoutePath ()
    {
        if ($this->CI->uri->total_segments() > 0) {
            $this->routePath[] = $this->CI->uri->segment(1);
        } else {
            $scope = $this->routeScope['scope'];
            $controller = 'default_'.$scope.'_controller';
            $this->routePath[] = $this->routeSecureConfig[$controller];
        }
        if ($this->CI->uri->total_segments() > 1) {
            $this->routePath[] = $this->CI->uri->segment(2);
        }
    }
    private function setRoutePathValues ()
    {
        $this->routePathValues = explode('/',uri_string());
    }
    private function setRouteView ()
    {
        $routePath = $this->routePath;
        // set route view
        if (count($routePath) > 0 && isset($this->secureRoutes[$routePath[0]])) {
            $this->routeView = $this->secureRoutes[$routePath[0]]["view"];
        }
        // set route view with subpath if exsits
        if (count($routePath) > 1 && isset($this->secureRoutes[$routePath[0]]['subroutes'][$routePath[1]])) {
            if( ! empty($this->secureRoutes[$routePath[0]]['subroutes'][$routePath[1]]["view"])){
                $this->routeView .= '/'.$this->secureRoutes[$routePath[0]]['subroutes'][$routePath[1]]["view"];
            }
        }
        // set default_entry_path view if exsits
        // and subpath not exsits
        if (count($routePath) == 1 && array_key_exists('default_entry_path',$this->secureRoutes[$routePath[0]])) {
            $this->routeView = $this->secureRoutes[$routePath[0]]['default_entry_path'];
        }
        // set default view if route view empty
        if ($this->routeView === '') {
            $scope = $this->routeScope['scope'];
            $controller = 'default_'.$scope.'_controller';
            $defaultController = $this->configView[$controller];
            $this->routeView = $this->secureRoutes[$defaultController]["view"];
        }
        // set index view if exists
        $this->setIndexRouteView();
    }
    private function setIndexRouteView ()
    {
        $this->CI->load->helper('directory',1);
        $viewargs = explode('/',$this->routeView);
        $result = '';

        if (count($viewargs) == 1)
        {
            $dir = directory_map('./application/views/'.$viewargs[0]);

            if (is_array($dir) && ! empty($dir)) {
                foreach($dir as $data){
                    if (is_string($data) && $data == 'index.php'){
                        $result = $viewargs[0].'/index';
                        break;
                    }
                }
            }
        }
        if ( ! empty($result))
        {
            $this->routeView = $result;
        }
    }
    private function setRouteRole ()
    {
        if (count($this->routePath) === 1 && isset($this->secureRoutes[$this->routePath[0]])) {
            $this->routeRole = $this->secureRoutes[$this->routePath[0]]["role"];
        }
        if (count($this->routePath) === 2 && isset($this->secureRoutes[$this->routePath[0]]['subroutes'][$this->routePath[1]])) {
            $this->routeRole = $this->secureRoutes[$this->routePath[0]]['subroutes'][$this->routePath[1]]["role"];
        }
    }
    private function setDefaultEntryPath ()
    {
        if (count($this->routePath) > 0 && array_key_exists('default_entry_path',$this->secureRoutes[$this->routePath[0]])) {
            $this->defaultEntryPath = $this->secureRoutes[$this->routePath[0]]['default_entry_path'];
        }
    }
    private function checkAuth ()
    {
        if ($this->checkPathSecure() === false) {
            $this->CI->session->sess_destroy();
            redirect($this->appMessagePath);
        }
    }
    private function checkPathIncluded ()
    {
        $routePath = $this->routePath;
        $res = false;
        // check path find in config_route_secure
        if (count($routePath) == 1 && isset($this->secureRoutes[$routePath[0]])) {
            $res = true;
        } else if (count($routePath) > 1) {
            if (isset($this->secureRoutes[$routePath[0]]) && isset($this->secureRoutes[$routePath[0]]['subroutes'])) {
                if (isset($this->secureRoutes[$routePath[0]]['subroutes'][$routePath[1]])) {
                    $res = true;
                }
            }
        }
        return $res;
    }
    private function checkPathSecure ()
    {
        if ( ! $this->checkPathIncluded()) {
            return false;
        }

        $routeRole = $this->routeRole;
        $result = true;

        if ( ! isset($this->routeUser['username']) && $routeRole !== '') {
            $result = false;
        } else if ( isset($this->routeUser['username']) && $routeRole !== '') {
            $routeRole = explode(',',$routeRole);
            $result = (in_array($this->routeUser['role'],$routeRole)) ? true : false;
        } else if ( isset($this->routeUser['username']) && $routeRole == '' && $this->routeScope['scope'] == 'protected' ) {
            $viewScope = explode(',',$this->secureRoutes[$this->routePath[0]]["scope"]);
            $result = (in_array($this->routeScope['scope'],$viewScope)) ? true : false;
        }

        return $result;
    }
}