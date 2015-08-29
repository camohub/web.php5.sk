<?php

namespace App;


use	Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter,
	Nette\Utils\Strings;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList();

		$router[] = new Route('<presenter>[/<action>[/<id>]]',
			array(
				'presenter' => array(
					Route::PATTERN => 'admin[^/]*|user|sign|helper|register|drom'
				),
			)
		);

		// Hranaté zátvorky sú povinné ak nepovinné parametre nenasledujú hneď po sebe
		$router[] = new Route('[<presenter articles>/]<title>[/<action>/<vp-page \d+>]',
			array(
				'presenter' => array(
					Route::VALUE => 'Articles',
					Route::FILTER_TABLE => array(
						'clanky' => 'Articles',
					),
				),
				'action' => array(
					Route::VALUE => 'show',
					Route::FILTER_TABLE => array(
						'strana' => 'show',
					)
				),
				'title' => array(
					Route::VALUE => 'najnovsie',
				),
				'vp-page' => array(
					Route::VALUE => '1',
				),
			)
		);

		$router[] = new Route('<presenter>/<action>',
			array(
				'presenter' => array(
					Route::VALUE => 'Default',
				),
				'action' => array(
					Route::VALUE => 'default',
				),
			)
		);


		return $router;
	}

}
