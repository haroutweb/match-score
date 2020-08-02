<?php
return [
    // homepage
    '/^\/$/i' => [
        'type'       => 'RegExp',
        'module'     => 'App',
        'controller' => 'home',
        'action'     => 'index'
    ],
    // Upload employee list
    '/^\/(employee)\/(upload)$/i' => [
        'type'       => 'RegExp',
        'module'     => 'App',
        'controller' => 'employee',
        'action'     => 'upload',
    ],
    // not error page
    '/^\/(404)$/i' => [
        'type'       => 'RegExp',
        'module'     => 'App',
        'controller' => 'error',
        'action'     => 'error404',
    ],
];