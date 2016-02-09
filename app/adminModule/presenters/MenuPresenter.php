<?php


namespace App\AdminModule\Presenters;


use Nette;
use	App;
use	Nette\Application\UI\Form;
use	App\Model\Categories;
use Nette\Caching\Cache;
use	Nette\Utils\Strings;
use	Tracy\Debugger;


class MenuPresenter extends App\AdminModule\Presenters\BaseAdminPresenter
{

	/** @var  Array */
	protected $getArray;



	public function startup()
	{
		parent::startup();

		if ( ! $this->user->isAllowed( 'menu', 'edit' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte oprávnenie editovať položku menu.' );
		}

		$this['breadcrumbs']->add( 'Spravovať menu', ':Admin:Menu:default' );
	}



	public function renderDefault()
	{
		// Necessary for snippet with form (?)
		$this->template->_form = $this['createSectionForm'];

		$arr = $this->getArray = $this->categories->getArray( $admin = TRUE );

		$this->template->menuArr = $arr;
		$this->template->section = $arr[0];
	}


	/**
	 * @secured
	 */
	public function handlePriority()
	{
		$iterator = 1;
		$error = FALSE; // for ajax
		try
		{
			foreach ( $_GET['menuItem'] as $key => $val )
			{
				// if the array is large it would be better to update only changed items
				$this->categories->update( (int) $key, array( 'parent_id' => (int) $val, 'priority' => $iterator ) );
				$iterator++;
			}
			if ( ! $this->isAjax() )
			{
				$this->flashMessage( 'Zmeny v menu boli uložené.' );
			}

			$this->cleanCache();

		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			if ( ! $this->isAjax() )
			{
				$this->flashMessage( 'Pri ukladaní údajov došlo k chybe.' );
			}
			$error = TRUE;
		}

		if ( $this->isAjax() )
		{
			if ( $error )
			{
				$this->setFlexiFlash( 'Poradie položiek bolo upravené' );
			}
			else
			{
				$this->setFlexiFlash( 'Poradie položiek bolo upravené' );
			}

			$this->redrawControl( 'menu' );
			$this->redrawControl( 'flexiFlash' );
			return;
		}

		$this->redirect( 'this' );

	}


	/**
	 * @secured
	 * TODO: Unused???
	 */
	public function handleSelect()
	{
		if ( ! $this->getArray )
		{
			$this->getArray = $this->categories->getArray();
		}
		$getArray = $this->getArray;

		$params = $this->getCategoriesSelectParams( $getArray, $getArray[0] );

		$this['createSectionForm']['parent_id']->setItems( $params );

		$this->redrawControl( 'create_parent' );
	}


	/**
	 * @param $id
	 * @secured
	 */
	public function handleVisibility( $id )
	{
		$row = $this->categories->findOneBy( array( 'id' => (int) $id ), 'admin' );

		$visible = $row->visible == 1 ? 0 : 1;
		$this->categories->update( (int) $id, array( 'visible' => $visible ) );

		$this->cleanCache();

		if ( $this->isAjax() )
		{
			$this->setFlexiFlash( 'Viditeľnosť položky bola upravená' );
			$this->redrawControl( 'sortableList' );
			$this->redrawControl( 'menu' );
			$this->redrawControl( 'flexiFlash' );

			return;
		}
		else
		{
			$this->flashMessage( 'Viditeľnosť položky bola upravená.' );
		}

		$this->redirect( ':Admin:Menu:default' );

	}


	/**
	 * @param $id
	 * @secured
	 */
	public function handleDelete( $id )
	{
		$row = $this->categories->findOneBy( array( 'id' => (int) $id ), 'admin' );
		if ( $row && $row->app == 1 )
		{
			if ( $this->isAjax() )
			{
				$this->setFlexiFlash( 'Položka je natívnou súčasťou aplikácie. Neni možné ju zmazať. Môžete ju ale skryť.', TRUE );
			}
			else
			{
				$this->flashMessage( 'Položka je natívnou súčasťou aplikácie. Neni možné ju zmazať. Môžete ju ale skryť.' );
			}
		}
		else
		{
			if ( $row )
			{
				$count = $this->categories->delete( (int) $id );
				$this->cleanCache();

				if ( $this->isAjax() )
				{
					$this->setFlexiFlash( 'Položka s názvom ' . $row->title . ' bola odstránená. Počet odstránených položiek ' . $count );
				}
				else
				{
					$this->flashMessage( 'Položka s názvom ' . $row->title . ' bola odstránená. Počet odstránených položiek ' . $count );
				}
			}
		}

		if ( $this->isAjax() )
		{
			$this->redrawControl( 'menu' );
			$this->redrawControl( 'sortableList' );
			if ( $row )
			{
				$this->redrawControl( 'flexiFlash' );
			} // Double click makes double deletion => second del. has not $row

			return;
		}

		$this->redirect( ':Admin:Menu:default' );

	}



//////Protected/Private///////////////////////////////////////////////

	/**
	 * @desc produces an array of categories in format required by form->select
	 * @param $wholeArr
	 * @param $secArr
	 * @param array $params
	 * @param int $lev
	 * @return array
	 */
	protected function getCategoriesSelectParams( $wholeArr, $secArr, $params = array(), $lev = 0 )
	{
		if ( ! $params )
		{
			$params[0] = 'Sekcia Top';
		}
		foreach ( $secArr as $row )
		{
			$params[$row->id] = str_repeat( '>', $lev * 1 ) . $row->title;
			if ( isset( $wholeArr[$row->id] ) )
			{
				$params = $this->getCategoriesSelectParams( $wholeArr, $wholeArr[$row->id], $params, $lev + 1 );
			}
		}

		return $params;
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



	/**
	 * @desc Cleans the menu cache.
	 */
	private function cleanCache()
	{
		//$cache = new Cache( $this->storage, 'categories' );
		$this->categories_cache->clean( [ Cache::TAGS => [ 'menu_tag', 'is_in_cache' ] ] );
	}


//////Control////////////////////////////////////////////////////////////////

	protected function createComponentCreateSectionForm()
	{
		$this->getArray = $this->getArray ? $this->getArray : $this->categories->getArray();

		$form = new Nette\Application\UI\Form();
		$form->elementPrototype->addAttributes( array( 'class' => 'ajax' ) );

		$form->addText( 'title', 'Zvoľte názov' )
			->addRule( Form::FILLED, 'Pole meno musí byť vyplnené.' )
			->setAttribute( 'class', 'b4 c3 w100P' );


		$form->addSelect( 'parent_id', 'Vyberte pozíciu' )
			->setAttribute( 'class', 'w100P' );

		$form->addSubmit( 'sbmt', 'Uložiť' )
			->setAttribute( 'class', 'dIB button1 pH20 pV5' );

		$form->onSuccess[] = $this->createSectionFormSucceeded;

		return $form;
	}


	public function createSectionFormSucceeded( $form )
	{
		if ( $this->isAjax() )
		{
			$values = $form->getHttpData();
		}
		else
		{
			$values = $form->getValues();
		}

		unset( $values['sbmt'] );
		unset( $values['do'] );

		$values['priority'] = 0;
		$values['url_title'] = Strings::webalize( $values['title'] );
		$values['url'] = ':Articles:show';
		$values['url_params'] = $values['url_title'];

		if ( $this->isAjax() ) // Is before try block cause catch returns
		{
			$this->redrawControl( 'menu' );
			$this->redrawControl( 'sortableList' );
			$this->redrawControl( 'flexiFlash' );
		}

		$row = $this->categories->findOneBy( array( 'url_title' => $values['url_title'] ) );
		if ( $row ) // Duplicate entry
		{
			if ( $this->isAjax() )
			{
				$this->setFlexiFlash( 'Kategória s názvom ' . $values['title'] . ' už existuje. Musíte vybrať iný názov.', 'error' );
			}
			else
			{
				$this->flashMessage( 'Kategória s názvom ' . $values['title'] . ' už existuje. Musíte vybrať iný názov.', 'error' );
			}

			return $form;
		}

		try
		{
			$this->categories->add( $values );
			$this->cleanCache();
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );

			if ( ! $this->isAjax() )
			{
				$this->flashMessage( 'Pri ukladaní došlo k chybe. ', 'error' );
				return;
			}

			$this->setFlexiFlash( 'Pri ukladaní došlo k chybe.', 'error' );

			return $form;
		}

		if ( $this->isAjax() )
		{
			$this->setFlexiFlash( 'Sekcia bola vytvorená.' );
			return;
		}

		$this->flashMessage( 'Sekcia bola vytvorená.' );
		$this->redirect( 'this' );

	}


///////Control///////////////////////////////////////////////////////////////////////


	public function createComponentEditSectionForm()
	{
		$form = new Form();
		$form->elementPrototype->addAttributes( array( 'class' => 'ajax' ) );

		$form->addText( 'title', 'Zmeňte názov' )
			->addRule( Form::FILLED, 'Pole názov musí byť vyplnené' )
			->setAttribute( 'class', 'w100P b4 c3' );

		$form->addHidden( 'id' )
			->addRule( Form::FILLED, 'Došlo k chybe. Sekcia nemá nastavené id. Skúste kliknúť na ikonu edit znova prosím.' );

		$form->addSubmit( 'sbmt', 'Uložiť' )
			->setAttribute( 'class', 'dIB button1 pH20 pV5' );

		$form->onSuccess[] = $this->editSectionFormSucceeded;

		return $form;

	}



	public function editSectionFormSucceeded( $form )
	{
		if ( $this->isAjax() )
		{
			$values = (object) $form->getHttpData();
		}
		else
		{
			$values = $form->getValues();
		}

		$url_title = $url_params = Strings::webalize( $values->title );

		if ( $this->isAjax() )
		{
			$this->redrawControl( 'menu' );
			$this->redrawControl( 'sortableList' );
			$this->redrawControl( 'flexiFlash' );
		}


		$row = $this->categories->findOneBy( array( 'url_title = ?' => $url_title ) );
		if ( $row )
		{
			if ( $this->isAjax() )
			{
				$this->setFlexiFlash( 'Kategória s názvom ' . $values->title . ' už existuje. Musíte vybrať iný názov.', 'error' );
				return;
			}

			$this->flashMessage( 'Kategória s názvom ' . $values->title . ' už existuje. Musíte vybrať iný názov.', 'error' );

			return $form;
		}

		try
		{
			$this->categories->update( (int) $values->id, array( 'title' => $values->title, 'url_title' => $url_title, 'url_params' => $url_params ) );
			$this->cleanCache();
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );

			if ( $this->isAjax() )
			{
				$this->setFlexiFlash( 'Pri ukladaní údajov došlo k chybe.', 'error' );
				$this->redrawControl( 'flexiFlash' );
				return;
			}

			$this->flashMessage( 'Pri ukladaní údajov do databázy došlo k chybe. Kontaktujte administrátora.', 'error' );

			return $form;
		}

		if ( $this->isAjax() )
		{
			$this->setFlexiFlash( 'Sekcia bola upravená.' );
			return;
		}

		$this->flashMessage( 'Sekcia bola upravená.' );

		$this->redirect( 'this' );

	}


}
