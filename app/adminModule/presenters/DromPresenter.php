<?php
namespace App\AdminModule\Presenters;


use	Nette,
	App,
	Nette\Application\UI\Form,
	Nette\Utils\Validators,
	Nette\Caching\Cache,
	Tracy\Debugger;


class DromPresenter extends App\AdminModule\Presenters\BaseAdminPresenter
{

	/** @var  App\Model\Categories @inject */
	public $categories;

	
	public function startup()
	{
		parent::startup();

		$this['breadcrumbs']->add( 'Drom', ':Admin:Drom' );

		$this->setFlexiFlash( 'hocico co pomoze.' );
		
	}



	public function renderDefault($id)
	{
		$this->template->_form = $this['testForm'];


	}


	public function handleChange($test_id)
	{
		if ($test_id) {
			$secondItems = array(
				1 => 'First option ' . $test_id . ' - second option 1',
				2 => 'First option ' . $test_id . ' - second option 2'
			);

			$this['testForm']['second']->setPrompt('Select')
				->setItems($secondItems);

		} else {
			$this['testForm']['second']->setPrompt('Select from first')
				->setItems(array());
		}
		$this->redrawControl('secondSnippet');
	}



	/**
	 * @param string $msg
	 * @param string $type
	 */
	protected function setFlexiFlash( $msg, $type = 'info' )
	{
		if ( ! isset( $this->template->flexiFlash ) )
		{
			$this->template->flexiFlash = array();
		}

		$this->template->flexiFlash[] = array( $msg, $type );

	}



	protected function createComponentTestForm()
	{
		$firstItems = array(
			1 => 'First option 1',
			2 => 'First option 2'
		);

		$form = new Form;
		$form->addSelect('first', 'First select:', $firstItems)
			->setPrompt('Select');

		$form->addSelect('second', 'Second select:')
			->setPrompt('Must select from first');

		$form->addSubmit('send', 'Submit');

		$form->onSuccess[] = $this->testFormSucceeded;

		return $form;
	}

	public function testFormSucceeded($form)
	{
		$values = $form->getHttpData();
		Debugger::dump($values);

	}




}
