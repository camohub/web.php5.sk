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
	CONST
		APPID = '1452969908332494',
		APPSECRET = '5a328a8e5bb2e4ac565c2449d0d50f54';

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
				'app_id' => self::APPID,
				'app_secret' => self::APPSECRET,
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



	/**
	 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	 * @desc This method is not used today !!!!!!!!!!!!!!!!!!!!!!!!!!!!
	 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	 */
	/*public function renderIn( $id = NULL )
	{
		$this->setHeaderTags( NULL, NULL, $robots = 'noindex, nofolow' );
		$this['breadcrumbs']->add( 'Prihlásenie cez Facebook', ':Signfb:in' );

		Facebook\FacebookSession::setDefaultApplication( '344387805765697', '61c89e18a89237de5a5c68a5a48fc370' );

		// Facebook API needs to be redirected first to Facebook and then back with id = 1 to make a session
		if ( !$id )
		{
			$this->getSession( 'Signfb' )->referer = $this->context->httpRequest->referer ? $this->context->httpRequest->referer->absoluteUrl : NULL;

			$helper = new Facebook\FacebookRedirectLoginHelper( $this->link( '//:Signfb:in', 1 ) );
			$this->redirectUrl( $helper->getLoginUrl( array( 'scope' => 'public_profile,email' ) ) );
		}
		else
		{
			$this->template->fbLogin = 0;

			$referer = $this->getSession( 'Signfb' )->referer;
			$helper = new Facebook\FacebookRedirectLoginHelper( $this->link( '//:Signfb:in', 1 ) );

			try
			{
				$session = $helper->getSessionFromRedirect();
			}
			catch ( Facebook\FacebookRequestException $ex )
			{
				Debugger::log( 'FacebookRequestException: ' . $ex->getMessage(), 'error' );
				$this->flashMessage( 'Pri prihlasovaní cez Facebook API došlo k chybe. ' . $ex->getMessage() );
				$referer ? $this->redirectUrl( $referer ) : $this->redirect( 'Default:default' );
			}
			catch ( \Exception $ex )
			{
				Debugger::log( 'AnotherFacebookException: ' . $ex->getMessage(), 'error' );
				$this->flashMessage( 'Pri prihlasovaní cez Facebook API došlo k chybe. Skúste to prosím ešte raz.' . $ex->getMessage() );
				$referer ? $this->redirectUrl( $referer ) : $this->redirect( 'Default:default' );
			}

			if ( isset( $session ) && $session )
			{
				$request = new Facebook\FacebookRequest( $session, 'GET', '/me' );
				$response = $request->execute();
				$fbUser = $response->getGraphObject( Facebook\GraphUser::className() );

				$userArr = array(
					'fb_id'     => $fbUser->getProperty( 'id' ),
					'email'     => $fbUser->getProperty( 'email' ),
					'user_name' => $fbUser->getProperty( 'name' ),
				);

				if ( empty( $userArr['email'] ) )
				{
					$this->flashMessage( 'Aplikácia potrebuje kôli autentizácii Vašu emailovú adresu.
					Bez emailu Vás nieje možné prihlásiť cez Facebook.' );
					$referer ? $this->redirectUrl( $referer ) : $this->redirect( 'Default:default' );
				}

				try
				{
					$identity = $this->userManagerFB->authenticate( $userArr );
					if ( $identity->active == 0 )
					{
						throw new App\Exceptions\AccessDeniedException( 'Učet ku ktorému chcete pristúpiť bol zablokovaný.' );
					}
					$this->getUser()->login( $identity );
					$this->template->fbLogin = 1;
				}
				catch ( App\Exceptions\DuplicateEntryException $e )
				{
					$this->flashMessage( $e->getMessage() );
					$referer ? $this->redirectUrl( $referer ) : $this->redirect( 'Default:default' );
				}
				catch ( \Exception $e )
				{
					$this->flashMessage( 'Pri prihlasovaní došlo k chybe. Skúste to prosím ešte raz.' );
					Debugger::log( $e->getMessage(), 'error' );
					$referer ? $this->redirectUrl( $referer ) : $this->redirect( 'Default:default' );
				}


				$referer ? $this->redirectUrl( $referer ) : $this->redirect( 'Default:default' );

			}
		}
	}*/


//////component//////////////////////////////////////////////////////////////


}
