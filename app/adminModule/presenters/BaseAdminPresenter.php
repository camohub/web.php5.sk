<?php


namespace App\AdminModule\Presenters;


use    Nette,
	App,
	Nextras,
	Tracy\Debugger;


class BaseAdminPresenter extends \App\Presenters\BasePresenter
{

	use Nextras\Application\UI\SecuredLinksPresenterTrait;



	public function startup()
	{
		parent::startup();

		if ( ! $this->user->isLoggedIn() )
		{
			$this->flashMessage( 'Pred vstupom do administrácie sa musíte prihlásiť.' );
			$this->redirect( ':Sign:in' );
		}

		if ( ! $this->user->isAllowed( 'administration', 'view' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte požadované oprávnenie pre vstup do administrácie.' );
		}

		//$this->template->endless_session = TRUE;

		$this['breadcrumbs']->remove( 0 );  // parent BasePresenter adds Default:default in startup
		$this['breadcrumbs']->add( 'Administrácia', ':Admin:Default:default' );
	}


}
