<?php

namespace App\Presenters;

use    Nette;
use    App;
use    Kdyby;
use    App\Exceptions;
use    Nette\Mail\Message;
use    Nette\Mail\SendmailMailer;
use    Tracy\Debugger;


/**
 * Registration presenter.
 */
class RegisterPresenter extends App\Presenters\BasePresenter
{
	/** @var App\Model\UserManager */
	protected $userManager;

	/** @var App\Model\Users */
	protected $users;

	/** @var  Nette\Mail\SendmailMailer */
	protected $mailer;

	/** @var  Nette\Mail\Message */
	protected $mail;



	public function __construct( App\Model\UserManager $userManager, App\Model\Users $users )
	{
		parent::__construct();

		$this->userManager = $userManager;
		$this->users = $users;
	}



	public function startup()
	{
		parent::startup();
		$this['breadcrumbs']->add( 'Registrácia', ':Register:default' );

		$this->getComponent( 'breadcrumbs' )->add( 'Registrácia2', 'Register:default' );

	}



	public function renderDefault()
	{
		$this->setHeaderTags( NULL, NULL, $robots = 'noindex, nofolow' );
	}


////components//////////////////////////////////////////////////////////////////////////////

	protected function createComponentRegistForm()
	{
		$form = new Nette\Application\UI\Form;

		$form->addProtection( 'Vypršal čas vyhradený pre odoslanie formulára. Z dôvodu rizika útoku CSRF bola požiadavka na server zamietnutá.' );

		$form->addText( 'user_name', 'Meno:' )
			->setRequired( 'Vyplňte prosím meno.' )
			->setAttribute( 'class', 'formEl' );

		// Password in DB can be NULL cause Facebook. So be careful.
		// Ofcourse empty string is not evaluete to NULL. So don't be paranoid!
		$form->addPassword( 'password', 'Heslo:' )
			->setRequired( 'Zadajte prosím heslo.' )
			->addRule( $form::MIN_LENGTH, 'Zadajte prosím heslo s minimálne %d znakmi', 3 )
			->setAttribute( 'class', 'formEl' );
		
		$form->addPassword( 'password2', 'Zopakujte heslo:' )
			->setRequired( 'Zadajte prosím heslo.' )
			->addRule( $form::EQUAL, 'Heslá sa nezhodujú. Zopakujte prosím kontrolu.', $form['password'] )
			->setAttribute( 'class', 'formEl' );

		$form->addText( 'email', 'Email:' )
			->setRequired( 'Zadajte prosím emailovú adresu. Email je povinný. Aktivujete ním svoj účet.' )
			->addRule( $form::EMAIL, 'Nezadali ste platnú mailovú adresu. Skontrolujte si ju prosím.', $form['password'] )
			->setAttribute( 'class', 'formEl' );

		$form->addSubmit( 'send', 'Registrovať' )
			->setAttribute( 'class', 'formElB' );

		$form->onSuccess[] = $this->registFormSucceeded;
		return $form;
	}


	public function registFormSucceeded( $form )
	{
		$values = $form->getValues();

		$this->em->beginTransaction();

		try
		{
			$user = $this->userManager->add( $values );
		}
		catch ( Exceptions\DuplicateEntryException $e )
		{
			$this->em->rollBack();

			if ( $e->getMessage() == 'user_name' )
			{
				$form->addError( 'Meno ' . $values['user_name'] . ' je už obsadené. Vyberte si prosím iné.' );
			}
			elseif ( $e->getMessage() == 'email' )
			{
				$form->addError( 'Email ' . $values['email'] . ' je už zaregistrovaný. Musíte uviesť unikátny email.' );
			}

			return;
		}
		catch ( \Exception $e )
		{
			$this->em->rollBack();
			Debugger::log( $e->getMessage(), 'error' );
			$this->flashMessage( 'Pri zakladní nového účtu došlo k chybe. Skúste to prosím ešte raz.', 'error' );

			return;
		}

		// Sending of confirmation email
		try
		{
			$template = $this->createTemplate()->setFile( __DIR__ . '/../templates/Register/email.latte' );
			$this->users->sendConfirmEmail( $template, $user );
		}
		catch ( \Exception $e )
		{
			$this->em->rollBack();
			Debugger::log( $e->getMessage(), 'error' );
			$this->flashMessage( 'Počas odosielania confirmačného emailu došlo k chybe. Účet nemohol byť vytvorený.', 'error' );

			return;
		}

		$this->em->commit();

		$this->flashMessage( 'Vitajte ' . $values['user_name'] . '. Vaša registrácia bola úspešná. Váš účet bude aktivovaný po potvrdení emailovej adresy. Konfirmačný email bol poslaný na adresu ' . $values['email'] );
		$this->redirect( ':Articles:show' );

	}

} 



