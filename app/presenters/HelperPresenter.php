<?php

namespace App\Presenters;

use Nette,
	App,
	Tracy\Debugger;


/**
 * Helper presenter
 * - to extend session (endless session)
 */
class HelperPresenter extends BasePresenter
{


	/**
	 * @desc Makes session endless. Is called via ajax and extends session.
	 */
	public function renderDefault()
	{
		$this->sendJson( [ 'message' => 'Session was extended' ] );
	}

}
