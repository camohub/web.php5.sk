<?php
namespace App\AdminModule\BlogModule\Presenters;

use Nette;
use App;
use Nette\Application\UI\Form;
use    Tracy\Debugger;


class ArticlesPresenter extends App\AdminModule\Presenters\BaseAdminPresenter
{

	/** @var  App\Model\Articles @inject */
	public $articles;

	/** @var  App\Model\Users @inject */
	public $users;

	/** @var  App\Model\Categories @inject */
	public $categories;

	/** @var  App\Model\Entity\Article */
	public $article;

	/** @var  Nette\Database\Table\Selection */
	protected $filteredArticles;



	public function startup()
	{
		parent::startup();
		$this['breadcrumbs']->add( 'Články', ':Admin:Blog:Articles:default' );
	}



	public function renderDefault()
	{
		// filter is relevant only for admin if he sets some in ArticlesFilterForm
		// $filter == $form->getValues()
		if ( $filter = $this->getSession( 'Admin:Blog:Articles' )->filter )
		{
			$this->template->articles = $this->articles->findBy( $filter['criteria'], $filter['order'], NULL, NULL, 'admin' );

			if ( ! isset( $filter['remember'] ) )  // if filter is not in session, settings will be lost for every redirect to renderDefault
			{
				unset( $this->getSession( 'Admin:Blog:Articles' )->filter );
			}
		}
		else
		{
			$this->template->articles = $this->articles->findBy( [ 'user.id =' => $this->user->id ], [ 'created' => 'DESC' ], NULL, NULL, 'admin' );
		}
	}



	public function actionCreate()
	{
		if ( ! $this->user->isAllowed( 'article', 'create' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte oprávnenie vytvárať články.' );
		}

		$this['breadcrumbs']->add( 'Vytvoriť', ':Admin:Blog:Articles:create' );

	}



	public function actionEdit( $id )
	{
		if ( ! $this->user->isAllowed( 'article', 'edit' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte oprávnenie editovať články.' );
		}

		$this->article = $this->articles->find( (int) $id );

		if ( ! ( $this->article->user->getId() == $this->user->id || $this->user->isInRole( 'admin' ) ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte právo editovať tento článok.' );
		}

		$this['articleForm']->setDefaults( $this->articles->setDefaults( $this->article ) );

		$this['breadcrumbs']->add( 'Editovať', ':Admin:Blog:Articles:edit' );

	}


	/**
	 * @secured
	 * @param $id
	 * @throws App\Exceptions\AccessDeniedException
	 */
	public function handleVisibility( $id )
	{
		if ( ! $this->user->isAllowed( 'article', 'edit' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte oprávnenie editovať články.' );
		}

		$this->article = $this->articles->findOneBy( array( 'id' => (int) $id ), 'admin' );

		if ( ! ( $this->article->user->getId() == $this->user->id || $this->user->isInRole( 'admin' ) ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte právo editovať tento článok.' );
		}

		try
		{
			$this->articles->switchVisibility( $this->article );
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage() . 'in ' . $e->getFile() . ' on line ' . $e->getLine() );
			$this->flashMessage( 'Pri upravovaní údajov došlo k chybe.', Debugger::ERROR );
			return;
		}

		$this->flashMessage( 'Zmenili ste vyditeľnosť článku.' );
		$this->redirect( ':Admin:Blog:Articles:default' );

	}


	/**
	 * @secured
	 * @param $id
	 * @throws App\Exceptions\AccessDeniedException
	 */
	public function handleDelete( $id )
	{
		if ( ! $this->user->isAllowed( 'article', 'delete' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte oprávnenie mazať články.' );
		}

		$this->article = $this->articles->find( (int) $id );

		if ( ! ( $this->article->user->getId() == $this->user->id || $this->user->isInRole( 'admin' ) ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte právo zmazať tento článok.' );
		}

		$this->articles->delete( $this->article );
		$this->flashMessage( 'Článok bol zmazaný.' );
		$this->redirect( ':Admin:Blog:Articles:default' );
	}



////protected////////////////////////////////////////////////////////////////


////component////////////////////////////////////////////////////////////////

	public function createComponentArticleForm()
	{
		$form = new Form;
		$form->addProtection( 'Vypršal čas vyhradený pre odoslanie formulára. Z dôvodu rizika útoku CSRF bola požiadavka na server zamietnutá.' );

		$form->addText( 'meta_desc', 'Popis', 60 )
			->setRequired()
			->setAttribute( 'class', 'w100P' );

		$form->addText( 'title', 'Nadpis', 60 )
			->setRequired( 'Nadpis je povinná položka. Vyplňte ho prosím.' )
			->setAttribute( 'class', 'w100P' );

		$form->addTextArea( 'perex', 'Perex' )
			->setRequired( 'Nemáte vyplnený prerex. Bez neho nebude formulár odoslaný.' )
			->setAttribute( 'class', 'show-hidden-error editor w100P' );  // show-hidden-errors is necessary because of live-form-validation.js

		$form->addTextArea( 'content', 'Text', 60 )
			->setRequired( 'Nenapísali ste žiaden text. Bez neho nebude formulár odoslaný.' )
			->setAttribute( 'class', 'show-hidden-error area500 editor' );

		$catSel = $this->categories->toSelect( 'admin' );
		$form->addMultiSelect( 'categories', 'Vyberte kategóriu', $catSel, 8 )
			->setRequired( 'Aplikácia vyžaduje, aby bola vybratá kategória pre článok.' )
			->setAttribute( 'class', 'w200 b7' );

		$form->addCheckbox( 'status', ' Zverejniť' )
			->setDefaultValue( 1 );

		$form->addSubmit( 'sbmt', 'Uložiť' );

		$form->onSuccess[] = $this->articleFormSucceeded;

		return $form;
	}



	public function articleFormSucceeded( $form )
	{
		$values = $form->getValues();
		$id = (int) $this->getParameter( 'id' );
		$values->perex = preg_replace( '#<pre>#', '<pre class="prettyprint custom">', $values->perex );
		$values->content = preg_replace( '#<pre>#', '<pre class="prettyprint custom">', $values->content );

		if ( $id )
		{
			try
			{
				$this->articles->updateArticle( $values, $id );
				$this->flashMessage( 'Článok bol upravený.' );
			}
			catch ( App\Exceptions\DuplicateEntryException $e )
			{
				$this->flashMessage( $e->getMessage(), 'error' );
				return $form;
			}
			catch ( \Exception $e )
			{
				$form->addError( 'Pri ukladaní článku došlo k chybe.' );
				Debugger::log( $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(), Debugger::ERROR );
				return $form;
			}
			$this->redirect( 'this' );
		}
		else
		{
			try
			{
				$values['user_id'] = $this->user->id;
				$this->articles->createArticle( $values );
				$this->flashMessage( 'Článok bol vytvorený.' );
			}
			catch ( App\Exceptions\DuplicateEntryException $e )
			{
				$this->flashMessage( $e->getMessage(), 'error' );
				return $form;
			}
			catch ( \Exception $e )
			{
				$form->addError( 'Pri ukladaní článku došlo k chybe.' );
				Debugger::log( $e->getMessage(), Debugger::ERROR );
				return $form;
			}
			$this->redirect( ':Admin:Blog:Articles:default' );
		}

	}

////component///////////////////////////////////////////////////////////////////

	public function createComponentArticlesFilterForm()
	{
		$form = new Form;

		$authors = $this->users->toSelect();
		$form->addMultiSelect( 'authors', 'Autori', $authors, 7 )
			->setAttribute( 'class', 'b7' );

		// Order of select items is critical.
		// order by created ASC, user_name ASC !== order by user_name ASC, created ASC.
		$form->addMultiSelect( 'order', 'Zoradiť podľa', array(
			'user.user_name DESC' => 'Meno zostupne',
			'user.user_name ASC'  => 'Meno vzostupne',
			'created DESC'        => 'Vytvorený zostupne',
			'created ASC'         => 'Vytvorený vzostupne',
		), 7 )
			->setAttribute( 'class', 'b7' );

		$form->addText( 'interval', 'Interval posledných x dní', 3 )
			->addCondition( FORM::FILLED )
			->addRule( FORM::INTEGER, 'Hodnota v poli "Interval" musí byť číslo.' );

		$form->addCheckbox( 'remember', ' Zapamätať si nastavenia' );

		$form->addSubmit( 'sbmt', 'Filtrovať' )
			->setAttribute( 'class', 'button1' );

		$form->onSuccess[] = $this->filterFormSucceeded;

		if ( $filter = $this->getSession( 'Admin:Blog:Articles' )->filter )
		{
			$form->setDefaults( $filter );
		}

		return $form;
	}



	public function filterFormSucceeded( $form )
	{
		$values = $form->getValues();

		$criteria = [ ];
		$order = [ ];

		$criteria['user.id ='] = $values['authors'] ? $values['authors'] : $this->user->id;

		if ( $values['interval'] )
		{
			$criteria['created >'] = date( 'Y-m-d H:i:s', time() - $values['interval'] * 60 * 60 * 24 );
		}

		foreach ( $values->order as $item )
		{
			list( $key, $val ) = explode( ' ', $item );
			$order[$key] = $val;
		}
		if ( ! array_key_exists( 'created', $order ) )
		{
			$order['created'] = 'DESC';
		}

		$filter = [ 'criteria' => $criteria, 'order' => $order ];

		$articleSession = $this->getSession( 'Admin:Blog:Articles' );
		$articleSession->setExpiration( 0 );
		$articleSession->filter = $filter;
	}

////protected//////////////////////////////////////////////////////


}
