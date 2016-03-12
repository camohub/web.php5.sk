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
	protected $users;

	/** @var  App\Model\Users */
	protected $userEnt;


	public function __construct( Users $users )
	{
		$this->users = $users;
	}


	public function startup()
	{
		parent::startup();

		// Method actionEmail must be available form confirmation email.
		// Do not use $this->user->identity->user_name.
		if ( $this->getParameter( 'action' ) !== 'email' )
		{
			if ( ! $this->user->isLoggedIn() )
			{
				$this->flashMessage( 'Nie ste prihlásený. Ak chcete pristupovať k Vašemu účtu musíte sa prihlásiť.' );
				$this->redirect( ':Sign:in' );
			}
		};

		$this->userEnt = $this->users->findOneBy( [ 'id =' => $this->user->id ] );

		$this['breadcrumbs']->add( 'Užívateľský účet', ':User:default' );
		$this->setHeaderTags( NULL, 'Profil uživateľa', 'noindex, nofollow' );

	}


	public function renderDefault()
	{
		$this->template->userEnt = $this->userEnt;
	}


	public function actionEmail( $id )
	{
		// Do not use any identity controls like isLoggedIn().
		// This method must be available from confirmation email.

		$code = $this->getParameter( 'code' );

		try
		{
			$user = $this->users->confirmEmail( $id, $code );
			$this->flashMessage( 'Váš email bol potvrdený.' );
		}
		catch ( App\Exceptions\ConfirmationEmailException $e )
		{
			$this->flashMessage( 'Odkaz bol neplatný. Skontrolujte, či nemáte ešte jeden takýto email v schránke, alebo či váš účet už nieje aktívny.', 'error' );
			return;
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage() );
			$this->flashMessage( 'Pri potvrdzovaní emailu došlo k chybe. Skúste sa prihlásiť, prípadne kontaktujte administrátora.', 'error' );
			return;
		}

		$this->redirect( ':Sign:in' );

	}


	public function actionPassword()
	{
		$this['breadcrumbs']->add( 'Zmeniť heslo', ':User:password' );
	}


	public function actionName()
	{
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

		$credentials = [
			'password'    => $values['password'],
			'newPassword' => $values['newPassword'],
		];

		try
		{
			$this->users->updatePassword( $this->userEnt, $credentials );
		}
		catch ( App\Exceptions\AccessDeniedException $e )
		{
			$this->flashMessage( 'Zadali ste neplatné údaje. Skúste to prosím ešte raz.', 'error' );
			return;
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			$this->flashMessage( 'Pri pokuse zmeniť heslo došlo k chybe. Otestujte prosím svoje údaje.', 'error' );
			return;
		}

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
			->setDefaultValue( $this->userEnt->getUserName() );

		$form->addSubmit( 'sbmt', 'Uložiť' );

		$form->onSuccess[] = $this->nameChangeFormSucceeded;

		return $form;
	}


	public function nameChangeFormSucceeded( $form )
	{
		$values = $form->getValues();

		try
		{
			$this->users->updateUser( [ 'user_name' => $values->user_name ], $this->userEnt );
		}
		catch ( App\Exceptions\DuplicateEntryException $e )
		{
			$this->flashMessage( 'Užívateľské meno ' . $values->user_name . ' je už obsadené. Zvoľte si prosím iné.', 'error' );
			return;
		}
		catch ( \Exception $e )
		{
			$this->flashMessage( $e->getMessage(), 'error' );
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
