<?php
/**
 * Created by PhpStorm.
 * Date: 22.09.2016
 */
$config['config_public'] = [
    'path' => [
        'public' => 'public/',
        'app' => 'app/',
        'javascript' => 'js/',
        'stylesheet' => 'css/',
        'image' => 'image/'
    ],
    'libaries' => [
        'app' => 'app,app.csrfToken,app.stageScope,app.appRequestCallback,app.locationHrefFilter,app.appMessage,app.regexValitator,app.ajaxRequest,app.appEvents,appService,app.appReady',
        'javascript' => 'jquery-3.1.1.min,bootstrap.min,bootstrap-confirmation-2.4.min',
        'stylesheet' => 'bootstrap.min,bootstrap-theme.min,font-awesome.min,base'
    ]
];