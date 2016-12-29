<?php
/**
 * Created by PhpStorm.
 * Date: 17.10.2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Userbundle
{
    protected $CI;
    private $config;

    public function __construct()
    {
        $this->CI = & get_instance();

        $this->CI->load->add_package_path(APPPATH.'third_party/user-bundle/');
        $this->CI->config->load('config_package_autoload',true);
        $this->config = $this->CI->config->item('config_package_autoload','config_package_autoload');

        $this->autoload();
    }
    private function autoload ()
    {
        if ( ! empty($this->config['config'])) {
            foreach($this->config['config'] as $config){
                $this->CI->config->load($config);
            }
        }
        if ( ! empty($this->config['libraries'])) {
            $this->CI->load->library($this->config['libraries']);
        }
        if ( ! empty($this->config['helpers'])) {
            $this->CI->load->helper($this->config['helpers']);
        }
    }
}
