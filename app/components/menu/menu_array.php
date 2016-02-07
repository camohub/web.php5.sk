<?php

namespace App\Controls;

use	Nette;
use	App;
use	Nette\Application\UI\Control;
use	Tracy\Debugger;
use Nette\Caching\Storages\FileStorage;
use Nette\Caching\Cache;


class MenuArray extends Control
{

	/** @var App\Model\Categories */
	protected $categories;

	/** @var  Nette\Caching\Cache */
	protected $cache;



	public function __construct( App\Model\Categories $categories, Cache $cache )
	{
		parent::__construct();

		$this->categories = $categories;
		$this->cache = $cache;

	}


	/**
	 * returns Nette\Application\UI\ITemplate
	 */
	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/menu.latte' );

		if( ! $this->cache->load( 'is_in_cache' ) )
		{
			$template->menuArr = $arr = $this->categories->getArray();
			$template->section = $arr[0];
			//  Cache identifier, that menu has been cached to avoid db query.
			$this->cache->save( 'is_in_cache', true, [ Cache::TAGS => [ 'is_in_cache' ] ] );
		}

		$template->render();
	}
	
}
