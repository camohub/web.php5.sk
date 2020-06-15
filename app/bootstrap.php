<?php

use Nette\Application\Routers\Route;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

#$configurator->setDebugMode(false); // disable debugger on localhost
$configurator->setDebugMode('217.31.33.34'); // enable for your remote IP
$configurator->enableDebugger(__DIR__ . '/../log');

// Because of Nette redirects to http which creates endless loop with .htaccess rules.
Route::$defaultFlags = Route::SECURED;

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

$application = $container->getService('application');
#$application->errorPresenter = 'Error'; // I dont know what is it

return $container;
