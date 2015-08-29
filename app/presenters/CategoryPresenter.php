<?php
namespace App\Presenters;

use	Nette,
	App,
	Tracy\Debugger,
	App\Model;


class CategoryPresenter extends \App\Presenters\BasePresenter
{

	/** @var Nette\Caching\IStorage @inject */
	public $storage;

	/** @var  App\Model\BlogArticles */
	protected $blogArticles;

	/** @var  Array */
	protected $optCompArray;



	public function startup()
	{
		parent::startup();
		$this->blogArticles = new Model\BlogArticles($this->database);

		$this->optCompArray = $this->getOptionalComponents($this->name);
	}



	public function renderShow($title)
	{
		$articles = $this->blogArticles->findAll();

		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 7;
		$paginator->itemCount = count($articles);

		$this->template->articles = $articles->limit($paginator->itemsPerPage, $paginator->offset);

		$this->setHeaderTags($metaDesc = 'web.php5.sk - najnovšie články', $title = ' Najnovšie články');
		$this->template->optCompArray = $this->getOptionalComponents($this->name) ? $this->getOptionalComponents($this->name) : $this->optCompArray;
	}

/////component/////////////////////////////////////////////////////////////////////////


}
