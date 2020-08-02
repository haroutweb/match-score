<?php

chdir(dirname(__DIR__));
define('DS', DIRECTORY_SEPARATOR);
define('APP_BASE_PATH', dirname(__DIR__) . DS . 'application' . DS);
define('INTERFACE_TYPE', 'http');

include APP_BASE_PATH . DS . '..' . DS . 'vendor' . DS .'autoload.php';

\Framework\Bootstrap::init(include(APP_BASE_PATH . 'config'. DS . 'application.config.php'))->run();
