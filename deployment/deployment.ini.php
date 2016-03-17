<?php

$username = 'skweb';
$password = 'kukurukukuIQ65';
$server = 'web.php5.sk';
$domain = 'http://web.php5.sk';

return array(
	'my site' => array(
		'remote'      => 'ftp://' . $username . ':' . $password . '@' . $server,
		'passivemode' => TRUE,
		'local'       => 'c://Apache24/htdocs/web.php5.sk',
		'test'        => FALSE,
		'ignore'      => '
			.git*
			.composer*
			project.pp[jx]
			/.idea
			/nbproject
			/deployment
			/www/images/app/*
			config.local.neon
			log/*
			!log/.htaccess
			temp/*
			!temp/.htaccess
			tests/
			bin/
			composer.lock
			composer.json
			.bowerrc
			/vendor/dg/ftp-deployment
			*.rst
		',
		'allowdelete' => FALSE,  // Because of config.local.neon
		/*'before' => array(
				'local:composer install --no-dev -d ./../'
		),
		'after' => array(
				$domain . '/install?printHtml=0',
				'local:composer install --dev -d ./../'
		),*/
		'purge'       => array(
			'temp/cache',
			'temp/deployment',
			'tmp/'
		),
		'preprocess'  => FALSE,
	),

	'tempdir' => __DIR__ . '/../temp',
	'colors' => TRUE,
);

