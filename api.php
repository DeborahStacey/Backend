<?php

//FIXME: remove the next 3 lines in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once __DIR__.'/vendor/autoload.php';

use WellCat\Providers\ApiControllerProvider;
use WellCat\Providers\UserControllerProvider;

$app = new Silex\Application();
$app['env'] = 'dev';
require_once __DIR__.'/config/configFile.php';
require_once __DIR__.'/src/wellCat.php';

$apiConProv = new ApiControllerProvider();
$app->register($apiConProv);
$app->mount('/', $apiConProv);

$userConProv = new UserControllerProvider();
$app->register($userConProv);
$app->mount('/user', $userConProv);

$app->run();
