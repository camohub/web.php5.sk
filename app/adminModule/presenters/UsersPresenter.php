<?php


namespace App\AdminModule\Presenters;


use Nette;
use App;
use Nette\Utils\Random;
use Tracy\Debugger;


class UsersPresenter extends App\AdminModule\Presenters\BaseAdminPresenter
{

	/** @var App\Model\Users @inject */
	public $usersModel;

	/** @var  array */
	protected $users;

	/** @var  App\Model\Entity\User */
	protected $userEnt;



	public function startup()
	{
		parent::startup();

		if ( ! $this->user->isAllowed( 'user', 'edit' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte oprávnenie editovať účty užívateľov.' );
		}

		$this['breadcrumbs']->add( 'Užívatelia', ':Admin:Users:default' );

	}



	public function renderDefault()
	{
		$users = $this->usersModel->usersResultSet( [ ], 'admin' );
		$this->template->users = $this->setPaginator( $users );
	}



	public function actionEdit( $id )
	{
		$this['breadcrumbs']->add( 'Editovať', ':Admin:Users:edit' );

		$this->userEnt = $this->template->userEnt = $this->usersModel->findOneBy( [ 'id =' => (int) $id ], 'admin' );

	}



	/**
	 * @secured
	 * @param $id
	 * @throws App\Exceptions\DuplicateEntryException
	 */
	public function handleActive( $id )
	{
		$user = $this->usersModel->findOneBy( [ 'id' => (int) $id ], 'admin' );

		$status = $user->getActive() == 1 ? 0 : 1;
		$this->usersModel->updateUser( [ 'active' => $status ], (int) $id );

		$this->flashMessage( 'Zmenili ste vyditeľnosť užívateľského účtu.' );
		$this->redirect( 'this' );
	}



	/**
	 * @secured
	 * @param $id
	 * @throws App\Exceptions\DuplicateEntryException
	 */
	public function handleEmail( $id )
	{
		$this->em->beginTransaction();

		try
		{
			$template = $this->createTemplate()->setFile( __DIR__ . '/../templates/Users/email.latte' );
			$this->usersModel->sendConfirmEmail( $template, $id );
		}
		catch ( \Exception $e )
		{
			$this->em->rollback();
			$this->flashMessage( 'Pri odosielaní emailu došlo k chybe. Email pravdepodobne nebol odoslaný.', 'error' );
			return;
		}

		$this->em->commit();
		$this->flashMessage( 'Bol odoslaný konfirmačný email.' );
		$this->redirect( 'this' );
	}


///////protected////////////////////////////////////////////////////////////////////////////

	protected function setPaginator( $users )
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 3;

		$users->applyPaginator( $paginator );

		return $users;

	}



//////component/////////////////////////////////////////////////////////////////////////////


	protected function createComponentEditForm()
	{
		$form = new Nette\Application\UI\Form;
		$id = (int) $this->getParameter( 'id' );

		$form->addProtection( 'Vypršal čas vyhradený pre odoslanie formulára. Z dôvodu rizika útoku CSRF bola požiadavka na server zamietnutá.', 'error' );

		$rolesSel = $this->usersModel->rolesToSelect();
		$rolesDefaults = $this->usersModel->setRolesDefaults( $id );
		$form->addMultiSelect( 'roles', 'Uživatľské role', $rolesSel, '5' )
			->setRequired( 'Musíte vybrať jedného uživateľa.' )
			->setDefaultValue( $rolesDefaults )
			->setAttribute( 'class', 'w150 b7' );

		$form->addCheckbox( 'confirmEmail', ' Overiť emailovú adresu.' );

		$form->addSubmit( 'send', 'Uložiť' )
			->setAttribute( 'class', 'formElB' );

		$form->onSuccess[] = $this->editFormSucceeded;
		return $form;
	}


	/**
	 * @param $form
	 */
	public function editFormSucceeded( $form )
	{
		$values = $form->getValues();
		$id = (int) $this->getParameter( 'id' );

		try
		{
			$this->usersModel->setUserRoles( $id, $values->roles );
		}
		catch ( \Exception $e )
		{
			$this->flashMessage( 'Pri nastavovaní užívateľských rolí došlo k chybe. Skontrolujte prosím aké práva má užívateľ nastavené.', 'error' );
			return;
		}

		if ( $values->confirmEmail )
		{
			$this->em->beginTransaction();

			try
			{
				$template = $this->createTemplate()->setFile( __DIR__ . '/../templates/Users/email.latte' );
				$this->usersModel->sendConfirmEmail( $template, $id );
			}
			catch ( \Exception $e )
			{
				$this->em->rollback();
				$this->flashMessage( 'Pri odosielaní emailu došlo k chybe. Email pravdepodobne nebol odoslaný.', 'error' );
				return;
			}

			$this->em->commit();
			$this->flashMessage( 'Bol odoslaný konfirmačný email.' );

		}

		$this->flashMessage( 'Nastavenia boli zmenené.' );
		$this->redirect( 'this' );

	}

}
