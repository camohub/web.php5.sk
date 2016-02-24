<?php


namespace App\AdminModule\Presenters;


use Nette;
use App;
use Doctrine;
use Nette\Application\UI\Form;
use Nette\Caching\Cache;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\DuplicateEntryException;
use Tracy\Debugger;


class MenuPresenter extends App\AdminModule\Presenters\BaseAdminPresenter
{

	/** @var Nette\Caching\IStorage @inject */
	public $storage;

	/** @var  @var Nette\Caching\Cache */
	protected $categories_cache;

	/** @var  array */
	protected $menu;



	public function startup()
	{
		parent::startup();

		if ( ! $this->user->isAllowed( 'menu', 'edit' ) )
		{
			throw new AccessDeniedException( 'Nemáte oprávnenie editovať položku menu.' );
		}

		$this->categories_cache = new Cache( $this->storage, 'categories' );

		$this['breadcrumbs']->add( 'Spravovať menu', ':Admin:Menu:default' );
	}



	public function renderDefault()
	{
		// Necessary for snippet with form (?)
		$this->template->_form = $this['createSectionForm'];

		//$arr = $this->getArray = $this->categories->getArray( $admin = TRUE );
		$this->menu = $this->categories->findBy( [ 'parent_id =' => NULL ], NULL, TRUE );

		$this->template->section = $this->menu;
		$this->template->categories = $this->categories;
	}


	/**
	 * @secured
	 */
	public function handlePriority()
	{
		try
		{
			$this->categories->updatePriority( $_GET['menuItem'] );
			$this->cleanCache();

			if ( ! $this->isAjax() )
			{
				$this->flashMessage( 'Poradie položiek bolo upravené.' );
				$this->redirect( 'this' );
			}
			else
			{
				$this->setFlexiFlash( 'Poradie položiek bolo upravené.' );
				$this->redrawControl( 'menu' );
				$this->redrawControl( 'flexiFlash' );
				return;
			}
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			$msg = 'Pri ukladaní údajov došlo k chybe.';
			$this->isAjax() ? $this->setFlexiFlash( $msg, 'error' ) : $this->flashMessage( $msg, 'error' );
		}
	}


	/**
	 * @secured
	 */
	public function handleSelect()
	{
		if ( ! $this->menu )
		{
			$this->menu = $this->categories->findBy( [ 'parent_id =' => NULL ], NULL, TRUE );
		}

		$params = $this->getCategoriesSelectParams( $this->menu );

		$this['createSectionForm']['parent_id']->setItems( $params );

		$this->redrawControl( 'create_parent' );
	}


	/**
	 * @param $id integer
	 * @secured
	 */
	public function handleVisibility( $id )
	{
		try
		{
			$this->categories->switchVisibility( $id );
			$this->cleanCache();
			$msg = 'Viditeľnosť položky bola upravená.';
			$this->isAjax() ? $this->setFlexiFlash( $msg, 'success' ) : $this->flashMessage( $msg, 'success' );
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			$msg = 'Pri ukladaní údajov došlo k chybe.';
			$this->isAjax() ? $this->setFlexiFlash( $msg, 'error' ) : $this->flashMessage( $msg, 'error' );
		}

		if ( $this->isAjax() )
		{
			$this->redrawControl( 'sortableList' );
			$this->redrawControl( 'menu' );
			$this->redrawControl( 'flexiFlash' );

			return;
		}

		$this->redirect( ':Admin:Menu:default' );

	}


	/**
	 * @param $id
	 * @secured
	 */
	public function handleDelete( $id )
	{
		$item = $this->categories->findOneBy( array( 'id' => (int) $id ), 'admin' );

		$this->cleanCache();

		if ( $this->isAjax() )
		{
			$this->redrawControl( 'menu' );
			$this->redrawControl( 'sortableList' );
			if ( $item )
			{
				$this->redrawControl( 'flexiFlash' );
			} // Double click makes double deletion => second del. has not $row
		}

		try
		{
			$names = $result = $this->categories->delete( $item );
			$this->setFlexiFlash( 'Item ' . join( ', ', $names ) . ' has been deleted.' );

			if ( $this->isAjax() )
			{
				return;
			}
		}
		catch ( App\Model\PartOfAppException $e )
		{
			$this->setFlexiFlash( $e->getMessage(), 'error' );
			return;
		}
		catch ( App\Model\ContainsArticleException $e )
		{
			$this->setFlexiFlash( $e->getMessage(), 'error' );
			return;
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
			$this->setFlexiFlash( 'Pri ukladaní údajov došlo k chybe.', 'error' );
			return;
		}

		$this->redirect( ':Admin:Menu:default' );

	}



//////Protected/Private///////////////////////////////////////////////

	/**
	 * @desc produces an array of categories in format required by form->select
	 * @param $section
	 * @param array $params
	 * @param int $lev
	 * @return array
	 */
	protected function getCategoriesSelectParams( $section, $params = array(), $lev = 0 )
	{
		if ( ! $params )
		{
			$params[0] = 'Sekcia Top';
		}
		foreach ( $section as $item )
		{
			$params[$item->id] = str_repeat( '>', $lev * 1 ) . $item->name;
			if ( $item->children->count() )
			{
				$params = $this->getCategoriesSelectParams( $item->children, $params, $lev + 1 );
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
		// Now is_in_cache from menu.latte is not used.
		// Because of latte cache is invalidated if latte code was changed.
		// Then is necessary to invalidate it manually. We do not want it.
		$this->categories_cache->clean( [ Cache::TAGS => [ 'menu_tag'/*, 'is_in_cache'*/ ] ] );
	}


//////Control////////////////////////////////////////////////////////////////

	protected function createComponentCreateSectionForm()
	{
		$this->menu = $this->menu ?: $this->categories->findBy( [ 'parent_id =' => NULL ], NULL, TRUE );

		$form = new Nette\Application\UI\Form();
		$form->elementPrototype->addAttributes( array( 'class' => 'ajax' ) );

		$form->addText( 'name', 'Zvoľte názov' )
			->addRule( Form::FILLED, 'Pole meno musí byť vyplnené.' )
			->setAttribute( 'class', 'b4 c3 w100P' );


		$form->addSelect( 'parent_id', 'Vyberte pozíciu' )
			->setAttribute( 'class', 'w100P' )
			->setAttribute( 'id', 'createSelect' );

		$form->addSubmit( 'sbmt', 'Uložiť' )
			->setAttribute( 'class', 'dIB button1 pH20 pV5' );

		$form->onSuccess[] = $this->createSectionFormSucceeded;

		return $form;
	}


	public function createSectionFormSucceeded( $form )
	{
		$values = $this->isAjax() ? $form->getHttpData() : $form->getValues();

		if ( $this->isAjax() ) // Is before try block cause catch returns
		{
			$this->redrawControl( 'menu' );
			$this->redrawControl( 'sortableList' );
			$this->redrawControl( 'flexiFlash' );
		}

		try
		{
			$this->categories->newBlogCategory( $values );
			$this->cleanCache();
		}
		catch ( DuplicateEntryException $e )
		{
			$this->setFlexiFlash( 'Kategória s názvom ' . $values['name'] . ' už existuje. Musíte vybrať iný názov.', 'error' );
			return $form;
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			$this->flashMessage( 'Pri ukladaní došlo k chybe. ', 'error' );
			return $form;
		}

		$this->setFlexiFlash( 'Sekcia bola vytvorená.' );

		if ( $this->isAjax() )
		{
			return;
		}

		$this->redirect( 'this' );

	}


///////Control///////////////////////////////////////////////////////////////////////


	public function createComponentEditSectionForm()
	{
		$form = new Form();
		$form->elementPrototype->addAttributes( array( 'class' => 'ajax' ) );

		$form->addText( 'title', 'Premenovať sekciu' )
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

		if ( $this->isAjax() )
		{
			$this->redrawControl( 'menu' );
			$this->redrawControl( 'sortableList' );
			$this->redrawControl( 'flexiFlash' );
		}

		try
		{
			$this->categories->updateName( $values->id, $values->title );
			$this->cleanCache();
		}
		catch ( App\Exceptions\ItemNotFoundException $e )
		{
			$this->setFlexiFlash( $e->getMessage(), 'error' );
			return $form;
		}
		catch ( DuplicateEntryException $e )
		{
			$this->setFlexiFlash( 'Položka s názvom ' . $values->title . ' už existuje. Musíte vybrať iný názov.', 'error' );
			return $form;
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			$this->setFlexiFlash( 'Pri ukladaní údajov došlo k chybe.', 'error' );
			return $form;
		}

		$this->setFlexiFlash( 'Sekcia bola upravená.' );

		if ( $this->isAjax() )
		{
			return;
		}

		$this->redirect( 'this' );

	}


}
