<?php

namespace App\Controls;

use	Nette;
use	App;
use	Nette\Application\UI\Control;
use Nette\Caching\Cache;
use	Tracy\Debugger;


class MenuArray extends Control
{

	/** @var App\Model\Categories */
	protected $categories;

	/** @var  Cache */
	protected $cache;

	/** @var  int */
	public $category_id = -1;  // null can fail.



	public function __construct( App\Model\Categories $categories/*, Cache $cache*/ )
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
			$template->menuArr = $arr = $this->categories->getArray();
			$template->section = $arr[0];
			//  Cache identifier, that menu has been cached to avoid db query.
			$this->cache->save( 'is_in_cache', true, [ Cache::TAGS => [ 'is_in_cache' ] ] );
		}*/

		$template->menuArr = $arr = $this->categories->getArray();
		$template->section = $arr[0];

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
