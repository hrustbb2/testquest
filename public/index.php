<?php

require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/../app/Config.php';

use app\App;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$app = App::getInstance();
$app->loadConf($config);
$app->sessionContainerInit();
$app->route();