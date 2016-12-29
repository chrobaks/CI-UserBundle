<?php
/**
 * Created by PhpStorm
 * Date: 19.10.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Languagemodule
{
    protected $CI;
    private $langShortCut;
    private $langDefault;
    private $langConfig;
    private $sessLang;
    private $defaultLang;
    private $defaultItem;
    private $routeLanguagePath;
    private $routeLanguageFile;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->langShortCut = '';
        $this->langDefault = $this->CI->envservice->getConfig('config_lang_default');
        $this->langConfig = $this->CI->envservice->getConfig('config_lang');
        $this->sessLang = '';
        $this->routeLanguagePath = '';
        $this->routeLanguageFile = '';
        $this->defaultLang = [
            'label',
            'page_error',
            'page_info',
            'page_title'
        ];
        $this->defaultItem = [
            'type'=>'ajax',
            'path'=>'lang/change',
            'label'=>'',
            'icon'=>'flag',
            'scope' => 'public,protected',
            'role' => '',
            'data'=>[
                'space'=>'ajax',
                'act'=>'request',
                'mod'=>'lang',
                'key'=>'lang',
                'val'=>'',
                'csrf-key' => '',
                'csrf-val' => ''
            ]
        ];
        $this->init();
    }
    public function changeLang ($shortcut)
    {
        $actionOk = false;

        foreach($this->langConfig as $k=>$v){
            if($v === $shortcut) {
                $this->sessLang = $k;
                $this->CI->session->set_userdata('view_lang',$k);
                $actionOk = true;
                break;
            }
        }
        return $actionOk;
    }
    public function loadRouteLang ($route)
    {
        if ( ! empty($this->sessLang)) {
            $this->CI->lang->load($route, $this->sessLang);
        }
    }
    public function getConfig ()
    {
        return $this->langConfig;
    }
    public function getLangShortCut ()
    {
        return $this->langConfig[$this->sessLang];
    }
    public function getData ()
    {
        $res = [];

        foreach($this->langConfig as $k=>$v){
            if ($this->sessLang != $k)
            {
                $copy = $this->defaultItem;
                $copy['label'] = 'label_'.$k;
                $copy['data']['val'] = $v;
                $copy['data']['csrf-key'] = $this->CI->security->get_csrf_token_name();
                $copy['data']['csrf-val'] = $this->CI->security->get_csrf_hash();
                $res[] = $copy;
            }
        }
        return $res;
    }
    private function init ()
    {
        // check url has filter lang
        // rewrite $this->langDefault if filter found
        $this->filterLang();

        if ( ! $this->CI->session->userdata('view_lang')) {
            $this->CI->session->set_userdata('view_lang',$this->langDefault);
            $this->sessLang = $this->langDefault;
        } else {
            $this->sessLang = $this->CI->session->userdata('view_lang');
        }

        $this->loadLang();
    }
    private function loadLang ()
    {
        $routePath = $this->CI->routemanager->getRoutePath();

        if ( ! empty($routePath))
        {
            if ($this->setRouteLanguagePath($routePath) && $this->setRouteLanguageFile($routePath))
            {
                $this->CI->lang->load($this->routeLanguageFile, $this->sessLang);
            }
        }
        if ( ! empty($this->sessLang)) {
            $this->CI->lang->load($this->defaultLang, $this->sessLang);
        }
    }
    private function filterLang ()
    {
        $filter = $this->CI->routemanager->getRoutePathFilter();

        if (is_array($filter) && array_key_exists('lang',$filter))
        {
            $filter['lang'] = trim($filter['lang']);

            foreach($this->langConfig as $lang => $shortcut){
                if ($shortcut === $filter['lang'])
                {
                    $this->CI->session->set_userdata('view_lang',$lang);
                    $this->langDefault = $lang;
                    break;
                }
            }
        }
    }
    private function setRouteLanguagePath ($routePath)
    {
        $path = APPPATH.'language'.DIRECTORY_SEPARATOR.$this->sessLang.DIRECTORY_SEPARATOR.$routePath[0];

        if (is_dir($path))
        {
            $this->routeLanguagePath = $path;
            return true;
        }
        return false;
    }
    private function setRouteLanguageFile ($routePath)
    {
        $fileName = 'index_lang.php';
        $langName = 'index';

        if ( count($routePath) > 1 && is_file($this->routeLanguagePath.DIRECTORY_SEPARATOR.$routePath[1].'_lang.php'))
        {
            $fileName = $routePath[1].'_lang.php';
            $langName = $routePath[1];
        }
        if ($fileName != 'index_lang.php' || is_file($this->routeLanguagePath.DIRECTORY_SEPARATOR.$fileName))
        {
            $this->routeLanguageFile = $routePath[0].DIRECTORY_SEPARATOR.$langName;
            return true;
        }
        return false;
    }
}