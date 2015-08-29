<?php

namespace App\Presenters;

use Nette,
	App\Model,
	Tracy\Debugger;


class DromPresenter extends BasePresenter
{
	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'user_name',
		COLUMN_PASSWORD = 'password',
		COLUMN_ROLE = 'role',
		COLUMN_EMAIL = 'email',
		COLUMN_ACTIVE = 'active',
		COLUMN_CONFIRMATION_CODE = 'confirmation_code';

	public function renderDefault()
	{
		$pass = 'password';
		$this->database->table('users')->where('email', 'vladimir.camaj@gmail.com')->where(self::COLUMN_PASSWORD.' NOT', NULL)->fetch();
	}


	public function renderTest()
	{

	}



///////component//////////////////////////////////////////////////////

	public function createComponentEditForm()
	{
		$form = new Nette\Application\UI\Form;


		return $form;
	}

	public function editFormSubmitted($form)
	{
		return true;
	}


}
