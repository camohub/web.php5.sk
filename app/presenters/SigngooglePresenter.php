<?php

namespace App\Presenters;

use Nette,
	App,
	Facebook,
	Tracy\Debugger;


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
			$client = new \Google_Client();
			$client->setClientId(self::APP_ID);
			$client->setClientSecret(self::APP_SECRET);
			$client->setRedirectUri(self::REDIRECT_URI);

			$client->authenticate($code);

			//$client->setAccessToken( $client->getAccessToken() ); // TODO: unused for now????

			//$client->getAuth(); // TODO: unused for now????

			$plus = new \Google_Service_Plus($client);

			$me = $plus->people->get('me');
		}
		catch (\Exception $e)
		{
			Debugger::log('SigngooglePresenter->actionIn() Google+ API exception: '.$e->getMessage(), 'error');
			$this->sendJson(array('errCode' => 1, 'error' => 'Prihlasovanie cez Google+ API zlyhalo.'));
			// No need to call terminate(). SendJson() already calls it.
		}

		foreach($me->emails as $email)
		{
			if($email->type == 'account') { $user_email = $email->value; }
		}
		if(!isset($user_email))
		{
			$this->sendJson(array('errCode' => 0, 'error' => 'Aplikácia vyžaduje na prihlásenie emailovú adresu. Email musíte pre aplikáciu povoliť.'));
		}

		$social_network_params = 'network=>Google+'
			.'***id=>'.$me->getId()
			.'***name=>'.$me->getDisplayName()
			.'***image=>'.$me->getImage()->url
			.'***url=>'.$me->getUrl();

		$userArr = array(
			'email' => $user_email,
			'user_name' => $me->getDisplayName(),
			'social_network_params' => $social_network_params,
		);

		try {
			$identity = $this->userManagerGoogle->authenticate($userArr);

			if ($identity->active == 0)
			{
				throw new App\Exceptions\AccessDeniedException('Účet cez ktorý sa pokúšate prihlásiť, je blokovaný.', 2);
			}

			$this->getUser()->setExpiration('30 minutes', TRUE);
			$this->getUser()->login($identity);
			// Don't call sendJson() from try. It throws an Exception

		} catch (App\Exceptions\DuplicateEntryException $e) {
			Debugger::log('SigngooglePresenter:actionIn e3 fails on:'.$e->getMessage());
			$this->sendJson(array('errCode' => 0, 'error' => $e->getCode() == 1 ? 'Pri pokuse registrovať Váš email došlo k chybe. Tento email je už registrovaný.' : $e->getMessage()) );

		} catch (App\Exceptions\AccessDeniedException $e) {
			// Be careful what can be displayed.
			Debugger::log('SigngooglePresenter:actionIn e4 fails on:'.$e->getMessage());
			$this->sendJson(array('errCode' => 0, 'error' => $e->getMessage()) );

		} catch (\Exception $e) {
			// Be careful whan can be displayed.
			Debugger::log('SigngooglePresenter:actionIn e4 fails on:'.$e->getMessage());
			$this->sendJson(array('errCode' => 0, 'error' => 'Prihlasovanie cez Google+ zlyhalo.'));
		}

		$this->flashMessage('Ste prihlásený ako '.$this->user->identity->user_name);
		// sendJson must not be in try block cause it throws an Nette\Application\AbortException
		// _fid is flash messages id. It is necessary for redirect which makes js.
		$this->sendJson(array('ok' => 'loged in', '_fid' => $this->params[self::FLASH_KEY]));


	}


//////component//////////////////////////////////////////////////////////////


}

class test
{
	public $jeden = 'jeden';
	public $dva = 'dva';
	public $tri = null;
	public $styri = 'styri';

	public function __construct()
	{
		$this->tri = new test2();
	}
}

class test2
{
	public $jeden = 'jeden';
	public $dva = 'dva';
	public $styri = 'styri';
}
