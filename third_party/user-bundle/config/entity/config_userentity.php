<?php
/**
 * Created by PhpStorm.
 * Date: 24.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// user entity
$config['userentity'] = [
    'table' => 'user',
    'columns' => [
        'id'=>['PRIMARY_KEY'],
        'username'=>['VARCHAR','UNIQUE'],
        'password'=>['VARCHAR'],
        'email'=>['VARCHAR','UNIQUE'],
        'role'=>['VARCHAR'],
        'confirmation'=>['VARCHAR'],
        'last_login'=>['DATETIME'],
        'salt'=>['VARCHAR','UNIQUE']
    ],
    'queryCols' => "id,username,email,role,confirmation,DATE_FORMAT(createAt,'%d.%m.%Y %H:%i') as createAt,DATE_FORMAT(last_login,'%d.%m.%Y %H:%i') as last_login",
    'queryOrderBy' => ['username'=>'asc'],
    'queryDefined' => [],
    'dependenceEntities' => ['userconfirmation']
];

// userconfirmation entity
$config['userconfirmation'] = [
    'table' => 'user_confirmation',
    'columns' => [
        'id'=>['PRIMARY_KEY'],
        'user_id'=>['INTEGER','INDEX'],
        'confirmation_type'=>['VARCHAR'],
        'confirmation_hash'=>['VARCHAR'],
        'pass_hash'=>['VARCHAR']
    ],
    'queryCols' => "id,user_id,confirmation_hash,pass_hash",
    'queryOrderBy' => [],
    'queryDefined' => [],
    'dependenceEntities' => []
];