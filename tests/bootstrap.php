<?php

require __DIR__ . '/../vendor/autoload.php';

\Testbench\Bootstrap::setup( __DIR__ . '/temp', function ( \Nette\Configurator $configurator )
{
	$configurator->createRobotLoader()->addDirectory( [
		__DIR__ . '/../app',
		//__DIR__ . '/tests',
	] )->register();

	$configurator->addParameters( [
		'appDir'   => __DIR__ . '/../app',
		'testsDir' => __DIR__,
	] );

	$configurator->addConfig( __DIR__ . '/../app/config/config.neon' );
	$configurator->addConfig( __DIR__ . '/config/config.test.neon' );
	//$configurator->addConfig( __DIR__ . '/../app/config/config.local.neon' );
} );

//\Tester\Environment::setup();  // Is in Testbench\Bootstrap::setup().
\Tracy\Debugger::$logDirectory = __DIR__ . '/log';
//date_default_timezone_set( 'Europe/Bratislava' );

//$configurator = new Nette\Configurator;
//$configurator->setDebugMode( FALSE );
//$configurator->setTempDirectory( __DIR__ . '/temp' );
//$configurator->createRobotLoader()
//->setCacheStorage( new \Nette\Caching\Storages\MemoryStorage() )
//->addDirectory( __DIR__ . '/../app' )
//->addDirectory( __DIR__ . '/../tests' )
//->register();

//$configurator->addConfig( __DIR__ . '/../app/config/config.neon' );
//$configurator->addConfig( __DIR__ . '/../app/config/config.local.neon' );

//return $configurator->createContainer();
