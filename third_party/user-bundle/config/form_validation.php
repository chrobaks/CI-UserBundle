<?php
/**
 * Created by PhpStorm.
 * Date: 23.09.2016
 */
$config['singleRules'] = [];
$config['singleRules']['id'] = [
    'field' => 'id',
    'label' => '',
    'rules' => 'trim|required|is_natural_no_zero|max_length[11]'
];
$config['singleRules']['password'] = [
    'field' => 'password',
    'label' => 'Password',
    'rules' => 'trim|required|min_length[8]|max_length[20]'
];
$config['singleRules']['passconf'] = [
    'field' => 'passconf',
    'label' => 'Password Confirmation',
    'rules' => 'trim|required|matches[password]'
];
$config['singleRules']['active'] = [
    'field' => 'active',
    'label' => 'Active',
    'rules' => 'trim|required|in_list[0,1]'
];
$config['singleRules']['zIndex'] = [
    'field' => 'zIndex',
    'label' => 'zIndex',
    'rules' => 'trim|required|is_natural_no_zero'
];
$config['singleRules']['content'] = [
    'field' => 'content',
    'label' => 'Content',
    'rules' => 'trim|required|min_length[3]'
];
$config['singleRules']['awaiting-data'] = [
    'field' => 'awaiting-data',
    'label' => 'awaiting-data',
    'rules' => 'trim|required|min_length[3]'
];

$config = [
    'delete' => [
        $config['singleRules']['id']
    ],
    'chapterNewMaxZIndex' => [
        $config['singleRules']['id'],
        $config['singleRules']['awaiting-data']
    ],
    'update_zIndex' => [
        $config['singleRules']['id'],
        $config['singleRules']['zIndex']
    ],
    'update_active' => [
        $config['singleRules']['id'],
        $config['singleRules']['active']
    ],
    'update_content' => [
        $config['singleRules']['id'],
        $config['singleRules']['content']
    ],
    'ajax/contact' => [
        [
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|required|min_length[5]|max_length[20]'
        ],
        [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email'
        ],
        [
            'field' => 'message',
            'label' => 'message',
            'rules' => 'trim|required|min_length[5]|max_length[1024]'
        ]
    ],
    'login' => [
        [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|min_length[5]|max_length[20]'
        ],
        $config['singleRules']['password']
    ],
    'signup' => [
        [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|min_length[5]|max_length[20]|is_unique[user.username]'
        ],
        $config['singleRules']['password'],
        $config['singleRules']['passconf'],
        [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|is_unique[user.email]'
        ]
    ],
    'confirmation' => [
        [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|min_length[5]|max_length[20]'
        ],
        $config['singleRules']['password'],
        [
            'field' => 'cnfhsh',
            'label' => 'cnfhsh',
            'rules' => 'trim|required|min_length[8]'
        ]
    ],
    'passreset' => [
        [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|min_length[5]|max_length[20]'
        ],
        $config['singleRules']['password'],
        $config['singleRules']['passconf'],
        [
            'field' => 'cnfhsh',
            'label' => 'cnfhsh',
            'rules' => 'trim|required|min_length[8]'
        ]
    ],
    'passwordforgot' => [
        [
            'field' => 'email',
            'rules' => 'trim|required|valid_email'
        ]
    ],
    'user/update' => [
        [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|min_length[5]|max_length[20]'
        ],
        [
            'field' => 'email',
            'rules' => 'trim|required|valid_email'
        ]
    ],
    'user/newpass' => [
        $config['singleRules']['password'],
        $config['singleRules']['passconf']
    ],
    'users/create' => [
        [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|min_length[5]|max_length[20]|is_unique[user.username]'
        ],
        [
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'trim|required|in_list[user,admin,root]'
        ],
        [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|is_unique[user.email]'
        ]
    ],
    'users/update' => [
        $config['singleRules']['id'],
        [
            'field' => 'role',
            'label' => '',
            'rules' => 'trim|required|in_list[user,admin,root]'
        ],
        [
            'field' => 'confirmation',
            'label' => '',
            'rules' => 'trim|required|in_list[0,1]'
        ]
    ],
    'entities/add' => [
        [
            'field' => 'entities_config_id',
            'label' => 'entities_config_id',
            'rules' => 'trim|min_length[0]|max_length[11]'
        ],
        [
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'trim|required|min_length[1]|max_length[200]'
        ],
        [
            'field' => 'tablename',
            'label' => 'Tablename',
            'rules' => 'trim|required|min_length[1]|max_length[200]'
        ],
        [
            'field' => 'query_cols',
            'label' => 'query_cols',
            'rules' => 'trim|required|min_length[1]|max_length[200]'
        ],
        [
            'field' => 'query_order_by',
            'label' => 'Columns',
            'rules' => 'trim|min_length[1]|max_length[200]'
        ],
        [
            'field' => 'dependence_entities_master',
            'label' => 'dependence_entities_master',
            'rules' => 'trim|min_length[0]|max_length[200]'
        ],
        [
            'field' => 'dependence_entities_child',
            'label' => 'dependence_entities_child',
            'rules' => 'trim|min_length[0]|max_length[200]'
        ]
    ],
    'entities/getconfig' => [
        [
            'field' => 'entities_config_id',
            'label' => 'entities_config_id',
            'rules' => 'trim|required|min_length[1]|max_length[11]'
        ]
    ],
    'entities/gettablecols' => [
        [
            'field' => 'tablename',
            'label' => 'tablename',
            'rules' => 'trim|required|min_length[1]|max_length[50]'
        ]
    ],
    'entities/masterconf' => [
        [
            'field' => 'entityconf',
            'label' => 'masterconf',
            'rules' => 'trim|required|min_length[1]'
        ]
    ],
    'entities/syncname' => [
        [
            'field' => 'syncname',
            'label' => 'syncname',
            'rules' => 'trim|required|min_length[8]'
        ]
    ],
    'documentation/createcat' => [
        [
            'field' => 'title_de',
            'label' => 'Title german',
            'rules' => 'trim|required|min_length[3]|max_length[200]|is_unique[documentation_category.title_de]'
        ],
        [
            'field' => 'title_en',
            'label' => 'Title english',
            'rules' => 'trim|required|min_length[3]|max_length[200]|is_unique[documentation_category.title_en]'
        ],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ],
    'documentation/updatecat' => [
        $config['singleRules']['id'],
        [
            'field' => 'url_link',
            'label' => 'Urllink',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        [
            'field' => 'title_de',
            'label' => 'Title german',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        [
            'field' => 'title_en',
            'label' => 'Title english',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ],
    'documentation/createchapter' => [
        [
            'field' => 'documentation_category_id',
            'label' => '',
            'rules' => 'trim|required|is_natural_no_zero|max_length[11]'
        ],
        [
            'field' => 'title_de',
            'label' => 'Title german',
            'rules' => 'trim|required|min_length[3]|max_length[200]|is_unique[documentation_chapter.title_de]'
        ],
        [
            'field' => 'title_en',
            'label' => 'Title english',
            'rules' => 'trim|required|min_length[3]|max_length[200]|is_unique[documentation_chapter.title_en]'
        ],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ],
    'documentation/updatechapter' => [
        $config['singleRules']['id'],
        [
            'field' => 'url_link',
            'label' => 'Urllink',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        [
            'field' => 'title_de',
            'label' => 'Title german',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        [
            'field' => 'title_en',
            'label' => 'Title english',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ],
    'documentation/createcontent' => [
        [
            'field' => 'documentation_chapter_id',
            'label' => '',
            'rules' => 'trim|required|is_natural_no_zero|max_length[32]'
        ],
        [
            'field' => 'is_pre_tag',
            'label' => '',
            'rules' => 'trim|required|in_list[0,1]'
        ],
        $config['singleRules']['content'],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ],
    'tutorial/createcat' => [
        [
            'field' => 'title_de',
            'label' => 'Title german',
            'rules' => 'trim|required|min_length[3]|max_length[200]|is_unique[tutorial_category.title_de]'
        ],
        [
            'field' => 'title_en',
            'label' => 'Title english',
            'rules' => 'trim|required|min_length[3]|max_length[200]|is_unique[tutorial_category.title_en]'
        ],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ],
    'tutorial/updatecat' => [
        $config['singleRules']['id'],
        [
            'field' => 'url_link',
            'label' => 'Urllink',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        [
            'field' => 'title_de',
            'label' => 'Title german',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        [
            'field' => 'title_en',
            'label' => 'Title english',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ],
    'tutorial/createchapter' => [
        [
            'field' => 'tutorial_category_id',
            'label' => '',
            'rules' => 'trim|required|is_natural_no_zero|max_length[11]'
        ],
        [
            'field' => 'title_de',
            'label' => 'Title german',
            'rules' => 'trim|required|min_length[3]|max_length[200]|is_unique[tutorial_chapter.title_de]'
        ],
        [
            'field' => 'title_en',
            'label' => 'Title english',
            'rules' => 'trim|required|min_length[3]|max_length[200]|is_unique[tutorial_chapter.title_en]'
        ],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ],
    'tutorial/updatechapter' => [
        $config['singleRules']['id'],
        [
            'field' => 'url_link',
            'label' => 'Urllink',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        [
            'field' => 'title_de',
            'label' => 'Title german',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        [
            'field' => 'title_en',
            'label' => 'Title english',
            'rules' => 'trim|required|min_length[3]|max_length[200]'
        ],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ],
    'tutorial/createcontent' => [
        [
            'field' => 'tutorial_chapter_id',
            'label' => '',
            'rules' => 'trim|required|is_natural_no_zero|max_length[32]'
        ],
        $config['singleRules']['content'],
        $config['singleRules']['active'],
        $config['singleRules']['zIndex']
    ]
];