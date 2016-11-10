<?php

//FIXME: remove the next 3 lines in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once __DIR__.'/vendor/autoload.php';

use WellCat\Providers\ApiControllerProvider;
use WellCat\Providers\UserControllerProvider;
use WellCat\Providers\PetControllerProvider;
use WellCat\Providers\AddressControllerProvider;
use WellCat\Providers\AnimalControllerProvider;
use WellCat\Providers\AdminPMControllerProvider;

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

$petConProv = new PetControllerProvider();
$app->register($petConProv);
$app->mount('/pet', $petConProv);

$addrConProv = new AddressControllerProvider();
$app->register($addrConProv);
$app->mount('/address', $addrConProv);

$animalConProv = new AnimalControllerProvider();
$app->register($animalConProv);
$app->mount('/animal', $animalConProv);

$adminPMConProv = new AdminPMControllerProvider();
$app->register($adminPMConProv);
$app->mount('/PM', $adminPMConProv);

$app->run();
