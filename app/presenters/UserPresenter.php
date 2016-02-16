<?php

namespace App\Presenters;

use Nette,
	App,
	App\Model\Users,
	Tracy\Debugger;


/**
 * Facebook Sign presenter.
 */
class UserPresenter extends BasePresenter
{

	/** @var  App\Model\Users */
	protected $usersModel;

	/** @var  App\Model\Users */
	protected $userRow;


	public function __construct( Users $usersModel )
	{
		$this->usersModel = $usersModel;
	}


	public function startup()
	{
		parent::startup();

		// Method actionEmail must be available form confirmation email.
		// So in startup must not be any identity control like isLoggedIn
		// and do not use $this->user->identity->user_name.

		$this->userRow = $this->usersModel->findOneBy( array( 'id' => $this->user->id ) );

		$this['breadcrumbs']->add( 'Užívateľský účet', ':User:default' );
		$this->setHeaderTags( NULL, 'Profil uživateľa', 'noindex, nofollow' );

	}


	public function renderDefault()
	{
		if ( ! $this->user->isLoggedIn() )
		{
			$this->flashMessage( 'Nie ste prihlásený. Ak chcete pristupovať k Vašemu účtu musíte sa prihlásiť.' );
			$this->redirect( ':Sign:in' );
		}
		$this->template->userRow = $this->userRow;

	}


	public function actionEmail( $id )
	{
		// Do not use any identity controls like isLoggedIn().
		// This method must be available from confirmation email.

		$code = $this->getParameter( 'code' );
		$set = array( 'active' => 1, 'confirmation_code' => NULL );
		$cond = array( 'id' => (int) $id, 'confirmation_code' => $code );
		$count = $this->usersModel->updateUserBy( $set, $cond );

		if ( ! $count )
		{
			$userRow = $this->usersModel->findOneBy( array( 'id' => (int) $id ), 'admin' );

			if ( ! $userRow )
			{
				$this->flashMessage( 'Odkaz bol neplatný. Daný užívateľ neexistuje. Ak máte pochybnosti o prebehnutej transakcii, kontaktujte prosím administrátora.', 'error' );
			}
			elseif ( $userRow->active == 0 && $userRow->confirmation_code )
			{
				$this->flashMessage( 'Odkaz bol neplatný. Email sa nepodarilo potvdiť. Skontrolujte, či nemáte ešte jeden takýto email v schránke, alebo kontaktujte administrátora.', 'error' );
			}
			elseif ( $userRow->active == 1 && ! $userRow->confirmation_code )
			{
				$this->flashMessage( 'Váš email bol už potvrdený.' );
			}
		}
		else
		{
			$this->flashMessage( 'Váš email bol potvrdený.' );
		}

		$this->redirect( ':Articles:show' );

	}


	public function actionPassword()
	{
		if ( ! $this->user->isLoggedIn() )
		{
			$this->flashMessage( 'Nie ste prihlásený. Ak chcete pristupovať ku Vašemu účtu, musíte sa prihlásiť.' );
			$this->redirect( ':Sign:in' );
		}
		$this['breadcrumbs']->add( 'Zmeniť heslo', ':User:password' );

	}


	public function actionName()
	{
		if ( ! $this->user->isLoggedIn() )
		{
			$this->flashMessage( 'Nie ste prihlásený. Ak chcete pristupovať ku Vašemu účtu, musíte sa prihlásiť.' );
			$this->redirect( ':Sign:in' );
		}
		$this['breadcrumbs']->add( 'Zmeniť meno', ':User:name' );
	}


//////component//////////////////////////////////////////////////////////////


	public function createComponentPasswordChangeForm()
	{
		$form = new Nette\Application\UI\Form();

		$form->addProtection( 'Vypršal časový limit. Kôli riziku útoku CSRF bola požiadavka zamietnutá.' );

		$form->addPassword( 'password', 'Doterajšie heslo:' )
			->setRequired( 'Pole doterajšie heslo je povinné. Vyplňte ho prosím.' );

		$form->addPassword( 'newPassword', 'Nové heslo:' )
			->setRequired( 'Pole nové heslo je povinné. Vyplňte ho prosím.' )
			->addRule( $form::MIN_LENGTH, 'Zadajte prosím heslo s minimálne %d znakmi', 3 );

		$form->addPassword( 'newPassword2', 'Zopakujte nové heslo:' )
			->setRequired( 'Pole nové heslo je povinné. Vyplňte ho prosím.' )
			->addRule( $form::EQUAL, 'Heslá sa nezhodujú.', $form['newPassword'] );

		$form->addSubmit( 'sbmt', 'Uložiť' );

		$form->onSuccess[] = $this->passwordChangeFormSucceeded;

		return $form;

	}


	public function passwordChangeFormSucceeded( $form )
	{
		$values = $form->getValues();
		$id = $this->user->id;

		$credentials = array(
			'password'    => $values['password'],
			'newPassword' => $values['newPassword'],
		);

		$this->database->beginTransaction();

		try
		{
			$this->usersModel->updatePassword( $id, $credentials );
		}
		catch ( App\Exceptions\AccessDeniedException $e )
		{
			$this->database->rollBack();

			$this->flashMessage( 'Zadali ste neplatné údaje. Skúste to prosím ešte raz.', 'error' );
			return;
		}
		catch ( \Exception $e )
		{
			$this->database->rollBack();

			Debugger::log( $e->getMessage(), 'error' );
			$this->flashMessage( 'Pri pokuse zmeniť heslo došlo k chybe. Otestujte prosím svoje údaje.', 'error' );
			return;
		}

		$this->database->commit();

		$this->flashMessage( 'Vaše heslo bolo úspešne zmenené.' );
		$this->redirect( ':User:default' );

	}


//////component///////////////////////////////////////////////////////////////////


	public function createComponentNameChangeForm()
	{
		$form = new Nette\Application\UI\Form();

		$form->addProtection( 'Vypršal časový limit. Kôli riziku útoku CSRF bola požiadavka zamietnutá.' );

		$form->addText( 'user_name', 'Meno' )
			->setRequired( 'Pole %label nesmie byť prázdne. Vyplňte ho prosím.' )
			->setDefaultValue( $this->userRow->user_name );

		$form->addSubmit( 'sbmt', 'Uložiť' );

		$form->onSuccess[] = $this->nameChangeFormSucceeded;

		return $form;
	}


	public function nameChangeFormSucceeded( $form )
	{
		$values = $form->getValues();

		try
		{
			$this->usersModel->updateUserBy( array( 'user_name' => $values->user_name ), array( 'id' => $this->user->id ) );
		}
		catch ( App\Exceptions\DuplicateEntryException $e )
		{
			$this->flashMessage( 'Užívateľské meno ' . $values->user_name . ' je už obsadené. Zvoľte si prosím iné.', 'error' );
			return;
		}

		// Must be here. It is part of possible error message from next try block
		$this->flashMessage( 'Vaše užívateľské meno bolo zmenené na ' . $values->user_name );

		try
		{
			$this->user->identity->user_name = $values->user_name;
			$this->user->login( $this->user->identity );
		}
		catch ( Nette\Security\AuthenticationException $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			$this->flashMessage( 'Vaše užívateľské meno bolo zmené, ale pri pokuse prihlásiť Vás pod novým menom došlo k chybe. Skúste sa prosím prihlásiť cez formulár.', 'error' );
			$this->redirect( ':Sign:in' );
		}

		$this->redirect( ':User:default' );

	}


}
