<?php

namespace App\Controls;

use	Nette;
use	App;
use	Nette\Application\UI\Control;
use	Tracy\Debugger;
use Nette\Caching\Storages\FileStorage;
use Nette\Caching\Cache;


class Menu extends Control
{

	/** @var App\Model\Categories */
	protected $categories;

	/** @var  Nette\Caching\Cache */
	protected $cache;

	/** @var Nette\Database\Table\ActiveRow */
	protected $onlyActiveSection;



	public function __construct( $categories, $cache )
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
		$template->setFile( __DIR__ . '/cache_menu.latte' );

		$arr = $this->categories->getArray();

		// template parameters
		$template->menuArr = $arr;

		//$this->cache->clean( [ Nette\Caching\Cache::TAGS => [ "menuTag2" ] ] );
		$template->test = time();

		// if we render only active section other sections are removed from menu
		// set it in presenter by $menuControl->setSection(Categories->findOneByUrl())
		$template->section = empty( $this->onlyActiveSection ) ? $arr[0] : $arr[$this->onlyActiveSection->id];
		if ( ! empty( $this->onlyActiveSection ) )
		{
			$template->sectionTitle = $this->onlyActiveSection->title;
		}

		$template->render();
	}



	/**
	 * @desc Displays only active section. Other sections are removed from menu.
	 * @param Nette\Database\Table\ActiveRow $s
	 */
	public function onlyActiveSection( Nette\Database\Table\ActiveRow $s )
	{
		$this->onlyActiveSection = $s;
	}


/////protected/private section//////////////////////////////////////////////////

	/**
	 * @return int
	 */
	protected function getSection()
	{
		return $this->onlyActiveSection ? $this->onlyActiveSection : $this->onlyActiveSection;
	}
	
	
	
}
