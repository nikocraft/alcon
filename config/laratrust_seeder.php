<?php

return [
    'role_structure' => [
        // super is super admin user, usually this is developer who sets up the site
        'super' => [
            'permissions' => 'all',
        ],
        // admin user who does not necessarly have access to all the stuff super does
        'admin' => [
            'admin' => 'access',
            'acl' => 'access',
            'media' => 'access',
            'design' => 'access',
            'plugins' => 'access',
            'settings' => 'access',
            'content' => 'manage',
            'images' => 'manage',
            'comments' => 'manage',
            'menus' => 'manage',
            'themes' => 'manage',
            'widgets' => 'manage',
            'plugins' => 'manage',
            'settings' => 'manage',
            'users' => 'manage',
            'roles' => 'manage',
            'profile' => 'manage'
        ],
        'writer' => [
            'admin' => 'access',
            'media' => 'access',
            'content' => 'manage',
            'images' => 'manage',
            'profile' => 'manage'
        ],
        // a role you can assign to your client, and limit what they can access and see
        'client' => [
            'admin' => 'access',
            'media' => 'access',
            'design' => 'access',
            'content' => 'manage',
            'images' => 'manage',
            'comments' => 'manage',
            'menus' => 'manage',
            'widgets' => 'manage',
            'profile' => 'manage'
        ],
        // visitors of your website, in case you allow them to register
        'member' => [
            'profile' => 'manage',
            'comments' => 'p',
        ],
    ],
    'permission_structure' => [
        // 'cru_user' => [
        //     'profile' => 'c,r,u'
        // ],
    ],
    'permissions_map' => [
        'all' => 'all',
        'access' => 'access',
        'manage' => 'manage',
        'p' => 'post',
        'l' => 'list',
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
