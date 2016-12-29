<?php
/**
 * Created by PhpStorm.
 * Date: 18.09.2016
 */
$config['config_route_secure'] = [
    'roles' => ['user','admin','root'],
    'default_public_controller' => 'home',
    'default_protected_controller' => 'home',
];
$config['config_route_secure']['routes'] = [
    'home' => [
        'scope' => 'public,protected',
        'role' => '',
        'view' => 'home'
    ],
    'settings' => [
        'scope' => 'protected',
        'role' => 'admin,root',
        'view' => 'settings'
    ],
    'user' => [
        'scope' => 'protected',
        'role' => 'user,admin,root',
        'view' => 'user',
        'subroutes' => [
            'edit' => [
                'scope' => 'protected',
                'role' => 'user,admin,root',
                'view' => 'ajaxrequest'
            ],
            'newpass' => [
                'scope' => 'protected',
                'role' => 'user,admin,root',
                'view' => 'ajaxrequest'
            ]
        ]
    ],
    'lang' => [
        'scope' => 'public,protected',
        'role' => '',
        'view' => 'lang'
    ],
    'login' => [
        'scope' => 'public',
        'role' => '',
        'view' => 'login',
        'subroutes' => [
            'passwordforgot' => [
                'scope' => 'public',
                'role' => '',
                'view' => 'ajaxrequest'
            ],
            'confirmation' => [
                'scope' => 'public',
                'role' => '',
                'view' => 'confirmation'
            ]
        ]
    ],
    'signup' => [
        'scope' => 'public',
        'role' => '',
        'view' => 'signup',
        'subroutes' => [
            'confirmation' => [
                'scope' => 'public',
                'role' => '',
                'view' => 'confirmation'
            ]
        ]
    ],
    'users' => [
        'scope' => 'protected',
        'role' => 'admin,root',
        'view' => 'users',
        'default_entry_path' => 'users/add',
        'subroutes' => [
            'add' => [
                'scope' => 'protected',
                'role' => 'admin,root',
                'view' => 'add'
            ],
            'all' => [
                'scope' => 'protected',
                'role' => 'admin,root',
                'view' => 'all'
            ],
            'update' => [
                'scope' => 'protected',
                'role' => 'admin,root',
                'view' => 'ajaxrequest'
            ],
            'delete' => [
                'scope' => 'protected',
                'role' => 'admin,root',
                'view' => 'ajaxrequest'
            ]
        ]
    ],
    'entities' => [
        'scope' => 'protected',
        'role' => 'root',
        'view' => 'entities',
        'default_entry_path' => 'entities/add',
        'subroutes' => [
            'add' => [
                'scope' => 'protected',
                'role' => 'root',
                'view' => 'add'
            ],
            'all' => [
                'scope' => 'protected',
                'role' => 'root',
                'view' => 'all'
            ],
            'sync' => [
                'scope' => 'protected',
                'role' => 'root',
                'view' => 'sync'
            ],
            'help' => [
                'scope' => 'protected',
                'role' => 'root',
                'view' => 'help'
            ],
            'getconfig' => [  'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'getnewconf' => [  'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'gettablecols' => [  'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'tableisdependence' => [  'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'createmasterconf' => [  'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'createconf' => [  'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'updateconf' => [  'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'deleteentitiy' => [ 'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'deleteconfig' => [ 'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'createfileconf' => [ 'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'createdbconf' => [ 'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest' ],
            'updateappdependence' => [ 'scope' => 'protected', 'role' => 'root', 'view' => 'ajaxrequest']
        ]
    ],
    'logout' => [
        'scope' => 'protected',
        'role' => 'user,admin,root',
        'view' => 'logout'
    ],
    'appmessage' => [
        'scope' => 'public',
        'role' => '',
        'view' => 'appmessage',
        'subroutes' => [
            'auth' => [
                'scope' => 'public',
                'role' => '',
                'view' => ''
            ]
        ]
    ],
    'ajax' => [
        'scope' => 'public,protected',
        'role' => '',
        'view' => 'ajaxrequest',
        'subroutes' => [
            'request' => [
                'scope' => 'public,protected',
                'role' => '',
                'view' => 'ajaxrequest'
            ],
            'help' => [
                'scope' => 'public,protected',
                'role' => '',
                'view' => 'ajaxrequest'
            ],
            'contact' => [
                'scope' => 'public,protected',
                'role' => '',
                'view' => 'ajaxrequest'
            ]
        ]
    ]
];