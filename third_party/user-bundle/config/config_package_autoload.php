<?php
/**
 * Created by PhpStorm.
 * Date: 18.09.2016
 */
$config['config_package_autoload'] = [
    'libraries' => [
        'service/entityservice',
        'manager/entitymanager',
        'service/sessionservice',
        'service/envservice',
        'manager/messagemanager',
        'manager/routemanager',
        'service/emailservice',
        'module/paginationmodule',
        'module/languagemodule',
        'module/navigationmodule',
        'manager/modulemanager',
        'service/userservice',
        'manager/viewmanager'
    ],
    'helpers' => [
        'bootstrap_helper',
        'user_access_helper'
    ],
    'config' => [
        'config_view',
        'config_env',
        'config_route_secure'
    ]
];