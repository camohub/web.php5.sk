<?php



namespace App\AdminModule\Presenters;



use	Nette,
	App,
	Tracy\Debugger,
	Nette\Utils\Strings,
	App\Model,
	Nette\Mail\Message,
	Nette\Mail\SendmailMailer,
	Nette\Utils\Random;



class UsersPresenter extends App\AdminModule\Presenters\BaseAdminPresenter
{

	/** @var Model\Users */
	private $usersModel;

	/** @var Nette\Mail\SendmailMailer */
	private $mailer;

	/** @var Nette\Mail\Message */
	private $mail;

	/** @var  Nette\Database\Table\Selection */
	protected $users;

	/** @var  Nette\Database\Table\ActiveRow */
	protected $userRow;



	public function __construct(Model\Users $usersModel, Nette\Mail\SendmailMailer $mailer, Nette\Mail\Message $mail)
	{
		parent::__construct();

		$this->usersModel = $usersModel;
		$this->mailer = $mailer;
		$this->mail = $mail;

	}



	public function startup()
	{
		parent::startup();

		if(!$this->user->isAllowed('user', 'edit'))
		{
			throw new App\Exceptions\AccessDeniedException('Nemáte oprávnenie editovať účty užívateľov.');
		}

		$this['breadcrumbs']->add('Užívatelia', ':Admin:Users:default');

	}



	public function renderDefault()
	{
		$users = $this->usersModel->findAll('admin');

		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 10;
		$paginator->itemCount = count($users);

		$this->template->users = $users->limit($paginator->itemsPerPage, $paginator->offset);

	}



	public function actionEdit($id)
	{
		$this['breadcrumbs']->add('Editovať', ':Admin:Users:edit');

		$this->userRow = $this->template->userRow = $this->usersModel->findOneBy(array('id' => (int)$id), 'admin');

	}



	public function handleActive($id)
	{
		$user = $this->usersModel->findOneBy(array('id' => (int)$id), 'admin');

		$status = $user->active == 1 ? 0 : 1;
		$this->usersModel->updateUser(array('active' => $status), (int)$id);

		$this->flashMessage('Zmenili ste vyditeľnosť užívateľského účtu.');
		$this->redirect('this');
	}



//////component/////////////////////////////////////////////////////////////////////////////


	protected function createComponentEditForm()
	{
		$form = new Nette\Application\UI\Form;
		$id = (int)$this->getParameter('id');

		$form->addProtection('Vypršal čas vyhradený pre odoslanie formulára. Z dôvodu rizika útoku CSRF bola požiadavka na server zamietnutá.', 'error');


		$rolesDefault = $this->usersModel->findUserRoles($id, 'admin')->fetchPairs('users_id', 'acl_roles_id');
		$allRoles = $this->usersModel->findAllRoles()->fetchPairs('id', 'name');
		$form->addMultiSelect('users_acl_roles', 'Uživatľské role', $allRoles, '5')
			->setRequired('Musíte vybrať jedného uživateľa.')
			->setDefaultValue($rolesDefault)
			->setAttribute('class', 'w150');

		$form->addCheckbox('confirmEmail', ' Overiť emailovú adresu.');


		$form->addSubmit('send', 'Uložiť')
			->setAttribute('class', 'formElB');

		$form->onSuccess[] = $this->editFormSucceeded;
		return $form;
	}


	/**
	 * @param $form
	 */
	public function editFormSucceeded($form)
	{
		$values = $form->getValues();
		$id = (int)$this->getParameter('id');

		try {
			$this->usersModel->setUsersRoles($id, $values->users_acl_roles);
		}
		catch(\Exception $e) {
			$this->flashMessage('Pri nastavovaní užívateľských rolí došlo k chybe. Skontrolujte prosím aké práva má užívateľ nastavené.', 'error');
			return;
		}

		// Sending confirmation email + sets user->active = 0
		if($values->confirmEmail)
		{
			$this->database->beginTransaction();

			try {
				$code = Random::generate(10,'0-9a-zA-Z');

				$this->usersModel->updateUser(array('active' => 0, 'confirmation_code' => $code), $id);

				$template = $this->createTemplate()->setFile(__DIR__ . '/../templates/Users/email.latte');
				$template->code = $code;
				$template->userId = $id;

				$mail = $this->mail;
				$mail->setFrom('admin@email.sk')
					->addTo('vladimir.camaj@gmail.com')
					->setReturnPath('camo@tym.sk')
					->setSubject('Overenie emailovej adresy.')
					->setHtmlBody($template);

				$mailer = $this->mailer;
				$mailer->send($mail);
			}
			catch(\Exception $e) {
				$this->database->rollBack();
				$this->flashMessage('Pri odosielaní emailu došlo k chybe. Email pravdepodobne nebol odoslaný.', 'error');
				return;
			}

			$this->database->commit();
			$this->flashMessage('Bol odoslaný konfirmačný email.');

		}

		$this->flashMessage('Nastavenia boli zmenené.');
		$this->redirect('this');

	}

}
