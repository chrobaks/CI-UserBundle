<?php
/**
 * Created by PhpStorm.
 * Date: 19.09.2016
 */
$config['config_view'] = [
    'base_view' => 'base_view',
    'title' => 'Codeigniter Skeleton'
];
$config['config_view']['routes'] = [
    'home' => [
        'label' => 'page_title_home',
        'menu_icon' => 'home',
        'show_in_menu' => true,
        'menu_float' => 'left'
    ],
    'settings' => [
        'label' => 'Settings',
        'show_in_menu' => true,
        'menu_float' => 'right',
        'menu_element' => [
            'type' => ['dropdown'],
            'path' => 'settings',
            'menu_icon' => 'cog',
            'menu_items' => [
                ['type'=>'link','path'=>'users/all','label'=>'page_title_users','icon'=>'user','scope'=>'protected','role'=>'admin,root'],
                ['type'=>'link','path'=>'entities/all','label'=>'page_title_entity','icon'=>'object-align-left','scope'=>'protected','role'=>'root']
            ]
        ]
    ],
    'user' => [
        'label' => 'page_title_profil',
        'show_in_menu' => true,
        'menu_float' => 'right',
        'menu_element' => [
            'type' => ['dropdown'],
            'path' => 'user',
            'menu_icon' => 'user',
            'menu_items' => [
                ['type'=>'link','path'=>'user','label'=>'page_title_profil','icon'=>'user','scope'=>'protected','role'=>'user,admin,root'],
                ['type'=>'divider'],
                ['type'=>'link','path'=>'logout','label'=>'page_title_logout','icon'=>'log-out','scope'=>'protected','role'=>'user,admin,root']
            ]
        ]
    ],
    'lang' => [
        'label' => 'page_title_lang',
        'show_in_menu' => true,
        'menu_float' => 'right',
        'menu_element' => [
            'type' => ['dropdown'],
            'module' => 'language',
            'path' => 'lang',
            'menu_icon' => 'flag',
            'menu_items' => []
        ]
    ],
    'login' => [
        'label' => 'page_title_login',
        'menu_icon' => 'log-in',
        'show_navigation' => true,
        'show_in_menu' => true,
        'menu_float' => 'right'
    ],
    'signup' => [
        'label' => 'page_title_signup',
        'menu_icon' => 'registration-mark',
        'show_navigation' => true,
        'show_in_menu' => true,
        'menu_float' => 'right'
    ],
    'users' => [
        'label' => 'page_title_users',
        'show_in_menu' => false,
        'menu_element_path' => 'settings',
        'menu_element' => [
            'type' => ['sidebar'],
            'path' => 'users',
            'menu_icon' => 'user',
            'menu_items' => [
                ['type'=>'link','path'=>'users/all','label'=>'page_title_users_all','icon'=>'wrench','scope' => 'protected','role'=>'admin,root'],
                ['type'=>'link','path'=>'users/add','label'=>'page_title_users_new','icon'=>'wrench','scope' => 'protected','role'=>'admin,root']
            ]
        ]
    ],
    'entities' => [
        'label' => 'page_title_entity',
        'show_in_menu' => false,
        'menu_element_path' => 'settings',
        'menu_element' => [
            'type' => ['sidebar'],
            'path' => 'entities',
            'menu_icon' => 'cog',
            'menu_items' => [
                ['type'=>'link','path'=>'entities/all','label'=>'page_title_entity_all','icon'=>'wrench','scope' => 'protected','role'=>'root'],
                ['type'=>'link','path'=>'entities/add','label'=>'page_title_entity_new','icon'=>'wrench','scope' => 'protected','role'=>'root'],
                ['type'=>'link','path'=>'entities/sync','label'=>'page_title_entity_sync','icon'=>'wrench','scope' => 'protected','role'=>'root'],
                ['type'=>'link','path'=>'entities/help','label'=>'page_title_entity_help','icon'=>'info-sign','scope' => 'protected','role'=>'root']
            ]
        ]
    ],
    'logout' => [
        'label' => 'page_title_logout',
        'show_in_menu' => false
    ],
    'appmessage' => [
        'label' => 'page_title_apperror',
        'show_navigation' => false,
        'show_in_menu' => false
    ],
    'ajax' => [
        'label' => '',
        'show_in_menu' => false
    ]
];