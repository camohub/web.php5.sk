<?php

namespace App\AdminModule\BlogModule\Presenters;


use Nette;
use App;
use Tracy\Debugger;


class GaleryPresenter extends App\AdminModule\Presenters\BaseAdminPresenter
{

	/** @var  App\Model\Images @inject */
	public $images;



	public function renderDefault()
	{
		$images = $this->images->imagesResultSet( [ 'module.id =' => 1 ] );
		$this->template->images = $this->setPaginator( $images );
		$this->template->page = $this['vp']->getPaginator()->page;

	}


///////Protected/////////////////////////////////////////////////////////////


	protected function setPaginator( $images )
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 3;

		$images->applyPaginator( $paginator );

		return $images;

	}


}
