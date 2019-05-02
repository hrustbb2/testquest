<?php

return [
    'db' => [
        'driver'    => 'mysql',
        'host'      => '127.0.0.1',
        'database'  => 'beejee2',
        'username'  => 'beejee2',
        'password'  => 'beejeebeejee',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ],
    'session' => [
        'sessionNameSpace' => 'mySuperCoolApp'
    ],
    'layer' => 'index.php',
    'routes' => [
        'index' => [
            'controller' => 'app\controllers\Site',
            'action' => 'index',
            'url' => '/',
            'method' => 'get',
        ],
        'index.page' => [
            'controller' => 'app\controllers\Site',
            'action' => 'index',
            'url' => '/page/{page}',
            'method' => 'get',
        ],
        'addTask' => [
            'controller' => 'app\controllers\Site',
            'action' => 'addTask',
            'url' => '/add',
            'method' => 'get',
            'allows' => ['POST'],
        ],
        'adminLogin' => [
            'controller' => 'app\controllers\Admin',
            'action' => 'login',
            'url' => '/login',
            'method' => 'get',
            'allows' => ['POST'],
        ],
        'adminLogout' => [
            'controller' => 'app\controllers\Admin',
            'action' => 'logout',
            'url' => '/logout',
            'method' => 'get',
            'allows' => ['POST'],
        ],
        'editTask' => [
            'controller' => 'app\controllers\Site',
            'action' => 'editTask',
            'url' => '/edit/{taskId}',
            'method' => 'get',
            'allows' => ['POST'],
        ],
        'setStatus' => [
            'controller' => 'app\controllers\Site',
            'action' => 'setStatus',
            'url' => '/setstatus/{taskId}',
            'method' => 'get'
        ]
    ]
];
