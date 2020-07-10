<?php

namespace App\Presenters;

use Facebook\Facebook;
use App;
use Tracy\Debugger;


/**
 * Facebook Sign presenter.
 */
class SignfbPresenter extends BasePresenter
{
	/** Facebook credentials */
	// Constants are in config.local.neon

	/** @var  App\Model\UserManagerFB @inject */
	public $userManagerFB;


	/**
	 * @param $accessToken
	 */
	public function actionIn( $accessToken )
	{
		try
		{
			$fb = new Facebook([
				'app_id' => FB_APP_ID,
				'app_secret' => FB_APP_SECRET,
				'default_graph_version' => 'v2.10',
				'http_client_handler' => 'stream',  // This is fix because of bug in Facebook use of Guzzle
				//'default_access_token' => '{access-token}', // optional
			]);

			$response = $fb->get('/me?fields=email,name,last_name,first_name,id,link', $accessToken);
			$fbUser = $response->getGraphUser();
		}
		catch ( \Exception $e )
		{
			// When validation fails or other local issues
			Debugger::log( $e, 'error' );
			$this->sendJson( array( 'errCode' => 1, 'error' => 'Prihlasovanie cez Facebook API zlyhalo.' ) );
		}

		if ( empty( $fbUser->getEmail() ) )
		{
			$this->sendJson( array( 'errCode' => 0, 'error' => 'Aplikácia potrebuje kôli prihláseniu Vašu emailovú adresu.' ) );
		}

		try
		{
			$social_network_params = [
				'network' => 'Facebook',
				'id'      => $fbUser->getId(),
				'name'    => $fbUser->getName(),
				'url'   => $fbUser->getLink(),
			];

			$userArr = [
				'email'                 => $fbUser->getEmail(), // email|NULL
				'user_name'             => $fbUser->getName(),
				'social_network_params' => serialize( $social_network_params ),
			];

			$identity = $this->userManagerFB->authenticate( $userArr );

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
			Debugger::log( $e );
			$this->sendJson( array( 'errCode' => 0, 'error' => $e->getCode() == 1 ? 'Pri pokuse registrovať Váš email došlo k chybe. Tento email je už registrovaný.' : $e->getMessage() ) );

		}
		catch ( App\Exceptions\AccessDeniedException $e )
		{
			// Be careful what can be displayed.
			Debugger::log( $e );
			$this->sendJson( array( 'errCode' => 0, 'error' => $e->getMessage() ) );

		}
		catch ( \Exception $e )
		{
			// Be careful whan can be displayed.
			Debugger::log( $e );
			$this->sendJson( array( 'errCode' => 0, 'error' => 'Prihlasovanie cez Facebook zlyhalo.' ) );
		}

		$this->flashMessage( 'Ste prihlásený ako ' . $this->user->identity->user_name );
		// sendJson must not be in try block cause it throws an Nette\Application\AbortException
		// _fid is flash messages id. It is necessary for redirect which makes js.
		$this->sendJson( array( 'ok' => 'loged in', '_fid' => $this->params[self::FLASH_KEY] ) );

	}


//////component//////////////////////////////////////////////////////////////


}
