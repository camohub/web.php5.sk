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

	/**
	 * @desc Is id of current category.
	 * @var  int
	 */
	public $current_id = -1;  // null can fail.

	/**
	 * @desc Displays only visible categories if FALSE.
	 * @var  bool
	 */
	public $admin = FALSE;

	/**
	 * @desc Is used if we want to display only one section.
	 * @var null
	 */
	public $only_section = NULL;


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

		$template->section = $this->categories->findBy( [ 'parent_id =' => $this->only_section ], NULL, $this->admin );

		$template->current_id = $this->current_id;
		$template->categories = $this->categories;
		$template->admin = $this->admin;

		$template->render();
	}



	/**
	 * @desc This sets the current category
	 * @param $id
	 */
	public function setCategory( $id )
	{
		$this->current_id = (int) $id;
	}


	/**
	 * @desc If is TRUE invisible categories will be displayed.
	 */
	public function setAdmin()
	{
		$this->admin = TRUE;
	}


	/**
	 * @desc This is used if we want to show only one category.
	 * @param $id
	 */
	public function setSection( $id )
	{
		$this->only_section = (int) $id;
	}

}

