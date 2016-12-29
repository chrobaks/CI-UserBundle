<?php
/**
 * Created by PhpStorm
 * Date: 19.10.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Modulemanager
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }
    public function getModuleData ($modId,$args = [])
    {
        $res = [];
        $mod = $this->getModule($modId);

        if ($mod != null)
        {
            if ( ! empty($args)) {
                $res = $mod->getData($args);
            } else {
                $res = $mod->getData();
            }
        }
        return $res;
    }
    public function getPropertyValue ($modId, $property)
    {
        $res = null;
        $mod = $this->getModule($modId);

        if ($mod != null)
        {
            $res = $mod->getPropertyValue($property);
        }
        return $res;
    }
    private function getModule ($modId) {
        $mod = $modId.'module';
        return (class_exists($mod)) ? $this->CI->{$mod} : null;
    }
}