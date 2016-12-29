<?php
/**
 * Created by PhpStorm
 * Date: 19.10.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Entitymanager
{
    protected $CI;
    private $config;
    private $instances;
    private $pathArgs;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->library('entity/entity');
        $this->CI->config->load('config_entities', true);

        $this->config = $this->CI->config->item('config_entities','config_entities');
        $this->config['entities'] = [];
        $this->instances = [];
        $this->pathArgs = ['third_party','user-bundle','config','entity'];

        $this->autoLoad();

    }
    public function instance ($entity)
    {
        $res = null;

        if ( ! array_key_exists($entity,$this->instances))
        {
            if ($this->loadConfig ($entity)) {
                $this->loadInstance($entity);
                $res = $this->instances[$entity];
            }
        } else {
            $res = $this->instances[$entity];
        }

        return $res;
    }
    private function loadInstance ($entity)
    {
        if (array_key_exists($entity,$this->config['entities']) && ! array_key_exists($entity,$this->instances))
        {
            $this->instances[$entity] = new $this->CI->entity($this->config['entities'][$entity]);

            if (array_key_exists('dependenceEntities', $this->config['entities'][$entity]) && ! empty($this->config['entities'][$entity]['dependenceEntities']))
            {
                foreach($this->config['entities'][$entity]['dependenceEntities'] as $dependence){
                    $this->loadInstance($dependence);
                }
            }
        }
    }
    private function loadConfig ($entity)
    {
        $res = false;

        if ($this->configExists($entity))
        {
            $this->CI->config->load('entity'.DIRECTORY_SEPARATOR.'config_'.$entity,true);
            $args = $this->CI->config->item($entity,'entity'.DIRECTORY_SEPARATOR.'config_'.$entity);

            $this->config['entities'][$entity] = $args;

            if(array_key_exists('dependenceEntities', $args) && ! empty($args['dependenceEntities'])) {
                $this->loadDependencies($entity, $args['dependenceEntities']);
            }

            $res = true;
        }
        return $res;
    }
    private function loadDependencies ($entity, $dependencies)
    {
        foreach ($dependencies as $dependence)
        {
            $args = $this->CI->config->item($dependence,'entity'.DIRECTORY_SEPARATOR.'config_'.$entity);
            $this->config['entities'][$dependence] = $args;

            if(array_key_exists('dependenceEntities', $args) && ! empty($args['dependenceEntities'])) {
                $this->loadDependencies($entity, $args['dependenceEntities']);
            }
        }
    }
    private function autoLoad ()
    {
        if ( ! empty($this->config['autoload']))
        {
            foreach($this->config['autoload'] as $entity){
                if ($this->loadConfig ($entity)) {
                    $this->loadInstance($entity);
                }
            }
        }
    }
    private function configExists ($entity)
    {
        $path = APPPATH.implode(DIRECTORY_SEPARATOR, $this->pathArgs);
        return (is_file($path.DIRECTORY_SEPARATOR.'config_'.$entity.'.php'));
    }
}