<?php
/**
 * Created by PhpStorm
 * Date: 16.10.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| config_env
|--------------------------------------------------------------------------
|
| @dev_tool : show view dev tool (true)
|
*/
$config['config_env'] = [
    'devTool' => false
];
/*
|--------------------------------------------------------------------------
| LANGUAGE
|--------------------------------------------------------------------------
*/
//# DEFAULT PAGELANGUAGE
$config['config_env']['config_lang_default'] = "german";
//# PAGELANGUAGE
$config['config_env']['config_lang'] = [
    'german'=>'de',
    'english'=>'en'
];
/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/
$config['config_env']['config_pagination'] = [
    'limit' => [
        'limitstep' => 0,
        'limitmax' => 10
    ],
    'selecetion' => [5,10,30,50,100],
    'maxItems' => 10
];
/*
|--------------------------------------------------------------------------
| EMAIL
|--------------------------------------------------------------------------
*/
$config['config_env']['email'] = [
    'mail_to_default' => '',
    'mail_from_default' => ''
];
switch (ENVIRONMENT)
{
    case 'development':
        $config['config_env']['email']['mail_to_default'] = 'info@netcodev.de';
        $config['config_env']['email']['mail_from_default'] = 'info@netcodev.de';
    case 'testing':
        $config['config_env']['email']['mail_to_default'] = 'info@netcodev.de';
        $config['config_env']['email']['mail_from_default'] = 'admin@netcoapp.de';
        break;
    case 'production':
        $config['config_env']['email']['mail_to_default'] = '';
        $config['config_env']['email']['mail_from_default'] = '';
        break;
}