<?php

namespace App\Presenters;

use Nette;
use	App;
use Kdyby;
use Nette\Caching\Cache;
use	Tracy\Debugger;
use    Kdyby\Doctrine\EntityManager;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	/** @var Kdyby\Doctrine\EntityManager @inject */
	public $em;

	/** @var Nette\Database\Context @inject */
	public $database;

	/** @var App\Model\Categories @inject */
	public $categories;

	/** @var Nette\Caching\IStorage @inject */
	//public $storage;

	/** @var  @var Nette\Caching\Cache */
	//protected $categories_cache;

	/** @var  Nette\Security\IAuthorizator */
	public $authorizator;

	/** @var null int */
	public $category_id = null;



	public function startup()
	{
		parent::startup();

		// $categories_cache is used in Menu component in every request and in MenuPresenter sometimes
		//$this->categories_cache = new Cache( $this->storage, 'categories' );

		$this['breadcrumbs']->add( 'Home', ':Articles:show' );

	}



	public function afterRender()
	{
		if ( $this->isAjax() && $this->hasFlashSession() )
		{
			$this->redrawControl( 'flash' );
		}
	}



	/**
	 * @desc Used in menu detects name of module  section == module. Method is still used for admin section of menu.
	 * @param $url
	 * @return bool
	 */
	public function isSectionCurrent( $url )
	{
		$url = ltrim( $url, ':' );
		$section = explode( ':', $this->getName() )[0];
		return stripos( $url, $section ) === 0;
		//or return \Nette\Utils\Strings::startsWith($this->getName(), $url);
	}



	/**
	 * @desc This method sets section id for javascript which opens/closes menu items.
	 */
	public function setCategoryId( $id )
	{
		$this['menu']->setCategory( $id );

	}



	/**
	 * @param null $desc
	 * @param null $title
	 * @param null $robots ( ie. 'noindex, nofollow')
	 */
	protected function setHeaderTags( $desc = NULL, $title = NULL, $robots = NULL )
	{
		if ( $desc )
		{
			$this->template->metaDesc = $desc;
		}
		if ( $title )
		{
			$this->template->title = $title;
		}
		if ( $robots )
		{
			$this->template->metaRobots = $robots;
		}
	}


	/**
	 * @desc Sets referrer url as string or '' to unique $id section for every page
	 * @param string $id
	 */
	protected function setReferer( $id = '' )
	{
		if ( ! $id )
		{
			return;
		}

		$url = '';

		if ( $referer = $this->getHttpRequest()->getReferer() )
		{
			$url = $referer->getScheme() . '://' . $referer->getHost() . '/' . $referer->getPath();

			if ( $qsArr = $referer->getQueryParameters() ) // returns array
			{
				foreach ( $qsArr as $key => $val )
				{
					if ( $key == self::FLASH_KEY )
					{
						continue;
					}

					$url .= isset( $i ) ? '&' . $key . '=' . $val : '?' . $key . '=' . $val;
					$i = 1;
				}
			}
		}

		$this->getSession( $id )->url = $url;
	}


	/**
	 * @desc Returns referrer url or false
	 * @param string $id
	 * @return bool|mixed|string
	 */
	protected function getReferer( $id = '' )
	{
		$refSes = $this->getSession( $id );

		if ( ! $id || ! $url = $refSes->url )
		{
			return FALSE;
		}

		$url = $this->getSession( $id )->url;
		$url .= ( parse_url( $url, PHP_URL_QUERY ) ? '&' : '?' );
		$url .= self::FLASH_KEY . '=' . $this->getParameter( self::FLASH_KEY );

		unset( $refSes->url );

		return $url;
	}


/////////helpers//////////////////////////////////////////////////////

	/**
	 * @desc Helpers
	 * @desc 1. To translates of moths and days names.
	 * @desc 2. Adds prettyprint class to the pre tags.
	 * @param null $class
	 * @return Nette\Application\UI\ITemplate
	 */
	protected function createTemplate( $class = NULL )
	{
		$template = parent::createTemplate( $class );
		$template->addFilter( 'datum', function ( $s, $lang = 'sk' )
		{
			$needles = array( 'Monday', 'Tuesday', 'Wensday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Mon', 'Tue', 'Wen', 'Thu', 'Fri', 'Sat', 'Sun' );
			$sk = array( 'pondelok', 'utorok', 'streda', 'štvrtok', 'piatok', 'sobota', 'nedeľa', 'január', 'február', 'marec', 'apríl', 'máj', 'jún', 'júl', 'august', 'september', 'október', 'november', 'december', 'jan.', 'feb.', 'mar.', 'apr.', 'máj', 'jún', 'júl', 'aug.', 'sep.', 'okt.', 'nov.', 'dec.', 'Po', 'Ut', 'St', 'Št', 'Pi', 'So', 'Ne' );

			return str_replace( $needles, $$lang, $s );
		}
		);

		return $template;
	}


////////components/////////////////////////////////////////////////////

	/**
	 * @return \App\Controls\Menu
	 */
	public function createComponentMenu()
	{
		return new App\Controls\Menu( $this->categories/*, $this->categories_cache*/ );
	}


	/**
	 * @return App\Controls\Breadcrumbs
	 */
	public function createComponentBreadcrumbs()
	{
		return new App\Controls\Breadcrumbs();
	}


	/**
	 * @param $name
	 * @return \NasExt\Controls\VisualPaginator
	 */
	protected function createComponentVp( $name )
	{
		$control = new \NasExt\Controls\VisualPaginator( $this, $name );
		// enable ajax request, default is false
		/*$control->setAjaxRequest();

		$that = $this;
		$control->onShowPage[] = function ($component, $page) use ($that) {
		if($that->isAjax()){
		$that->invalidateControl();
		}
		};*/
		return $control;
	}


	/**
	 * @return Nette\Application\UI\Multiplier
	 */
	protected function createComponentPoll()
	{
		return new Nette\Application\UI\Multiplier( function ( $name )
		{
			return new App\Controls\Poll( $this->database, $this, $name );
		} );
	}


}
