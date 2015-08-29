<?php

namespace App\Presenters;

use Nette,
	App,
	Tracy\Debugger;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	/** @var Nette\Database\Context @inject */
	public $database;

	/** @var  Nette\Security\IAuthorizator */
	public $authorizator;



	public function startup()
	{
		parent::startup();
		$this['breadcrumbs']->add('Home', ':Articles:show');
	}



	public function afterRender()
	{
		if ($this->isAjax() && $this->hasFlashSession())
			$this->redrawControl('flash');
	}



	/**
	 * @desc Used in menu detects name of module  section == module
	 * @param $url
	 * @return bool
	 */
	public function isSectionCurrent($url)
	{
		$url = ltrim($url, ':');
		$section = explode(':', $this->getName())[0];
		return stripos($url, $section) === 0;
		//or return \Nette\Utils\Strings::startsWith($this->getName(), $url);
	}



	/**
	 * @param $owner_id
	 * @return Nette\Database\Table\Selection
	 */
	protected function getOptionalComponents($owner_id)
	{
		$optCompArray = array();
		if($sel = $this->database->table('optional_components')->where(array('owner_id' => $owner_id)))
		{
			foreach($sel as $row)
			{
				// explode name cause it consists from name + identifier like "poll_50"
				$optCompArray[explode('_', $row->component_name)[0]] = $row;
			}
		}
		return $optCompArray;
	}



	/**
	 * @param null $desc
	 * @param null $title
	 * @param null $robots ( ie. 'noindex, nofollow')
	 */
	protected function setHeaderTags($desc = NULL, $title = NULL, $robots = NULL)
	{
		if($desc) $this->template->metaDesc = $desc;
		if($title) $this->template->title = $title;
		if($robots) $this->template->metaRobots = $robots;
	}


	/**
	 * @desc Sets referrer url as string or '' to unique $id section for every page
	 * @param string $id
	 */
	protected function setReferer($id = '')
	{
		if(!$id) return;

		$url = '';

		if($referer = $this->getHttpRequest()->getReferer())
		{
			$url = $referer->getScheme().'://'.$referer->getHost().'/'.$referer->getPath();

			if($qsArr = $referer->getQueryParameters()) // returns array
			{
				foreach($qsArr as $key => $val)
				{
					if($key == self::FLASH_KEY) continue;

					$url .= isset($i) ? '&'.$key.'='.$val : '?'.$key.'='.$val;
					$i = 1;
				}
			}
		}

		$this->getSession($id)->url = $url;
	}


	/**
	 * @desc Returns referrer url or false
	 * @param string $id
	 * @return bool|mixed|string
	 */
	protected function getReferer($id = '')
	{
		$refSes = $this->getSession($id);

		if(!$id || !$url = $refSes->url) return false;

		$url = $this->getSession($id)->url;
		$url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?');
		$url .= self::FLASH_KEY.'='.$this->getParameter(self::FLASH_KEY);

		unset($refSes->url);

		return $url;
	}


/////////helper//////////////////////////////////////////////////////

	/**
	 * @desc Helpers
	 * @desc 1. To translates of moths and days names.
	 * @desc 2. Adds prettyprint class to the pre tags.
	 * @param null $class
	 * @return Nette\Application\UI\ITemplate
	 */
	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		$template->addFilter('datum', function ($s, $lang = 'sk')
			{
				$needles = array('Monday', 'Tuesday', 'Wensday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Mon', 'Tue', 'Wen', 'Thu', 'Fri', 'Sat', 'Sun');
				$sk = array('pondelok', 'utorok', 'streda', 'štvrtok', 'piatok', 'sobota', 'nedeľa', 'január','február','marec', 'apríl', 'máj', 'jún', 'júl', 'august', 'september', 'október', 'november', 'december', 'jan.', 'feb.', 'mar.', 'apr.', 'máj', 'jún', 'júl', 'aug.', 'sep.', 'okt.', 'nov.', 'dec.', 'Po', 'Ut', 'St', 'Št', 'Pi', 'So', 'Ne');

				return str_replace($needles, $$lang, $s);
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
		$categories = new App\Model\Categories($this->database);
		return  new App\Controls\Menu($categories);
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
	protected function createComponentVp($name)
	{
		$control = new \NasExt\Controls\VisualPaginator($this, $name);
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
		return new Nette\Application\UI\Multiplier( function($name)
		{
			return new App\Controls\Poll($this->database, $this, $name);
		});
	}


}
