<?php
/**
 * Created by PhpStorm.
 * Date: 24.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Entity extends EntityService
{
    public function __construct($config='')
    {
        if (is_array($config))
        {
            parent::__construct();

            // table name
            $this->table = (array_key_exists('table', $config)) ? $config['table'] : '';
            // table columns
            $this->columns = (array_key_exists('columns', $config)) ? $config['columns'] : [];
            // default query columns
            $this->queryCols = (array_key_exists('queryCols', $config)) ? $config['queryCols'] : '';
            // default query order by
            $this->queryOrderBy = (array_key_exists('queryOrderBy', $config)) ? $config['queryOrderBy'] : [];
            // defined queries
            $this->queryDefined = (array_key_exists('queryDefined', $config)) ? $config['queryDefined'] : [];
            // dependence entities
            $this->dependenceEntities = (array_key_exists('dependenceEntities', $config)) ? $config['dependenceEntities'] : [];
        }
    }
}