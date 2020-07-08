<?php

namespace App\Presenters;


use Google_Client;
use Google_Service_Plus;
use Nette;
use    App;
use    Facebook;
use    Tracy\Debugger;


/**
 * Google Sign presenter.
 */
class SigngooglePresenter extends BasePresenter
{
	/** @var  App\Model\UserManagerGoogle @inject */
	public $userManagerGoogle;

	/** Google credentials */
	const
		SERVER_KEY = 'AIzaSyDpnjlDbqLWfkCHl-0BGHwBquz4Dm8tSCE',
		APP_ID = '811214467813-v3fmui55m0kmohsf6dbg1jjl11ori3tg.apps.googleusercontent.com',
		APP_SECRET = 'GYH9LOsTwnhC2b3PLMYH930C',
		APP_NAME = 'web-php5-sk',
		REDIRECT_URI = 'postmessage';



	/**
	 * @desc This method is called via ajax from google js sdk (in BasePresenter)
	 */
	public function actionIn()
	{
		$code = $_POST['code'];

		try
		{
			$client = new Google_Client();
			$client->setAuthConfig(__DIR__ . '/../model/JsonData/GoogleCredentials.json');
			//$client->addScope([\Google_Service_PeopleService::USERINFO_PROFILE]);  // Prev version
			//$client->setClientId( self::APP_ID );
			//$client->setClientSecret( self::APP_SECRET );
			$client->setRedirectUri( self::REDIRECT_URI );

			$accessToken = $client->fetchAccessTokenWithAuthCode( $code );
			$client->setAccessToken( $accessToken );

			$peopleService = new \Google_Service_PeopleService( $client );
			$me = $peopleService->people->get('people/me', ['personFields' => 'emailAddresses,names']);
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e );
			$this->sendJson( array( 'errCode' => 1, 'error' => 'Prihlasovanie cez Google+ API zlyhalo.' ) );
			// No need to call terminate(). SendJson() already calls it.
		}

		// To understand which $me->getMethod returns string or array look at Google_Service_PeopleService_Person class on top.
		$id = $me->getResourceName();
		/** @var \Google_Service_PeopleService_EmailAddress $email */
		foreach ( (array)$me->getEmailAddresses() as $email ) if ( $email->getMetadata()->getPrimary() ) $user_email = $email->value;
		/** @var \Google_Service_PeopleService_Name $name */
		foreach ( (array)$me->getNames() as $name ) if ( $name->getMetadata()->getPrimary() ) $user_name = $name->displayName;

		if ( ! isset( $user_email ) ) $this->sendJson( array( 'errCode' => 2, 'error' => 'Aplikácia vyžaduje na prihlásenie emailovú adresu. Email musíte pre aplikáciu povoliť.' ) );
		if ( ! isset( $user_name ) ) $this->sendJson( array( 'errCode' => 3, 'error' => 'Aplikácia vyžaduje na prihlásenie meno. Dotaz na Google API nevrátil žiadnu hodnotu.' ) );

		$social_network_params = [
			'network'   => 'Google+',
			'id'        => $id,
			'user_name' => $user_name
		];

		$userArr = [
			'email'                 => $user_email,
			'user_name'             => $user_name,
			'social_network_params' => serialize( $social_network_params ),
		];

		try
		{
			$identity = $this->userManagerGoogle->authenticate( $userArr );

			if ( $identity->active == 0 )
			{
				throw new App\Exceptions\AccessDeniedException( 'Účet cez ktorý sa pokúšate prihlásiť, je blokovaný.', 2 );
			}

			$this->getUser()->setExpiration( 0, TRUE );
			$this->getUser()->login( $identity );
			// Don't call sendJson() from try. It throws an Exception
		}
		catch ( App\Exceptions\DuplicateEntryException $e )
		{
			Debugger::log($e);
			$this->sendJson( [ 'errCode' => 1, 'error' => 'Pri pokuse registrovať Vás došlo k chybe. Toto meno, alebo email je už registrovaný.' ] );
		}
		catch ( App\Exceptions\AccessDeniedException $e )
		{
			// Be careful what can be displayed.
			Debugger::log($e);
			$this->sendJson( [ 'errCode' => 2, 'error' => $e->getMessage() ] );
		}
		catch ( \Exception $e )
		{
			// Be careful whan can be displayed.
			Debugger::log($e);
			$this->sendJson( [ 'errCode' => 3, 'error' => 'Prihlasovanie cez Google+ zlyhalo.' ] );
		}

		$this->flashMessage( 'Ste prihlásený ako ' . $this->user->identity->user_name );
		// sendJson must not be in try block cause it throws an Nette\Application\AbortException
		// _fid is flash messages id. It is necessary for redirect which makes js.
		$this->sendJson( [ 'ok' => 'loged in', '_fid' => $this->params[self::FLASH_KEY] ] );

	}


//////component//////////////////////////////////////////////////////////////


}

