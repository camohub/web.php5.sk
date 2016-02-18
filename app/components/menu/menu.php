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

