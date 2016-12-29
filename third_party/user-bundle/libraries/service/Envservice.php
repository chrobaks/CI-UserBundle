<?php
/*
|--------------------------------------------------------------------------
| CLASS DESCRIPTION
|
| @name	   : MY_Ajax
| @type	   : LIBARY MODULE CLASS
| @version : 0.2
|--------------------------------------------------------------------------
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Envservice
{
    private $CI;
    private $config;
    /**
     * CONSTRUCTOR
     *
     * @access	PUBLIC
     */
    public function __construct ()
    {
        $this->CI =& get_instance();

        $this->config = $this->CI->config->item('config_env');
    }
    public function getConfig ($key)
    {
        return (array_key_exists($key,$this->config)) ? $this->config[$key] : '';
    }
}