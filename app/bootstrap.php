<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

#$configurator->setDebugMode(false); // disable debugger on localhost
#$configurator->setDebugMode('78.98.20.153'); // enable for your remote IP
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../vendor/others')
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

$application = $container->getService('application');
#$application->errorPresenter = 'Error'; // I dont know what is it

return $container;
