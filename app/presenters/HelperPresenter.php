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


	public function renderDefault()
	{
		$this->sendJson( ['message' => 'Session was extended'] );
	}

}
