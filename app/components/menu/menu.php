<?php

namespace App\Controls;

use	Nette;
use	App\Model\Categories;
use	Nette\Application\UI\Control;
use Nette\Caching\Cache;
use	Tracy\Debugger;


class Menu extends Control
{

	/** @var Categories */
	protected $categories;

	/** @var  Cache */
	protected $cache;

	/** @var  int */
	public $category_id = -1;  // null can fail.


	public function __construct( Categories $categories/*, Cache $cache*/ )
	{
		parent::__construct();

		$this->categories = $categories;
		//$this->cache = $cache;

	}


	/**
	 * returns Nette\Application\UI\ITemplate
	 */
	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/menu.latte' );

		// Farmework automaticaly invalidate latte cache if latte was changed.
		// Then you will need uncomment next line. This is the reasom why whole cache is in comment.
		//$this->cache->clean( [ Cache::TAGS => [ 'menu_tag', 'is_in_cache' ] ] );

		/*if( ! $this->cache->load( 'is_in_cache' ) )
		{
			//  Is called only if cache is invalid
			$template->section = $this->categories->getMenu();
			//  This avoids db query
			$this->cache->save( 'is_in_cache', true, [ Cache::TAGS => [ 'is_in_cache' ] ] );
		}*/

		$template->section = $this->categories->getMenu();

		$template->category_id = $this->category_id;

		$template->render();
	}



	/**
	 * @desc This sets the category
	 * @param $id
	 */
	public function setCategory( $id )
	{
		$this->category_id = $id;
	}

}

