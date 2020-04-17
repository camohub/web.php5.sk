<?php

$username = 'skweb';
$password = 'kukurukukuIQ65';
$server = 'web.php5.sk';
$domain = 'http://web.php5.sk';

return [
	'my site' => [
		'remote' => 'ftp://' . $username . ':' . $password . '@' . $server,
		'passivemode' => TRUE,
		'local' => 'C:\wamp64-3-2-0\www\web-php5-sk',
		'test' => FALSE,
		'ignore' => '
			.git*
			.composer*
			project.pp[jx]
			/.idea
			/nbproject
			/deployment
			/www/images/app/*
			/doc
			/files
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
		'purge' => [
			'temp/cache',
			'temp/deployment',
			'tmp/'
		],
		'preprocess' => FALSE,
	],

	'tempdir' => __DIR__ . '/../temp',
	'colors' => TRUE,
];

