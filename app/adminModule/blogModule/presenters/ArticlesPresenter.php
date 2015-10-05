<?php
namespace App\AdminModule\BlogModule\Presenters;

use	Nette,
	App,
	Nette\Application\UI\Form,
	Tracy\Debugger;

class ArticlesPresenter extends App\AdminModule\Presenters\BaseAdminPresenter
{

	/** @var  App\Model\BlogArticles @inject */
	public $blogArticles;

	/** @var  App\Model\Users @inject */
	public $users;

	/** @var  App\Model\Categories @inject */
	public $categories;

	/** @var  Nette\Database\Table\ActiveRow */
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
			$articles = $this->blogArticles->findAll( 'admin', FALSE );

			$articles = $filter->authors ? $articles->where( 'users_id', $filter->authors ) : $articles;
			$articles = $filter->interval ? $articles->where( 'created > ?', time() - $filter->interval * 60 * 60 * 24 ) : $articles;
			$articles = $filter->order ? $articles->order( implode( ',', $filter->order ) ) : $articles;
			// if items are not ordered by date
			$articles = ( $filter->order && ( in_array( 'created DESC', $filter->order ) || in_array( 'created ASC', $filter->order ) ) ) ? $articles : $articles->order( 'created DESC' );

			$this->template->articles = $articles;

			if ( ! $filter->remember )  // if filter is not in session, settings will be lost for every redirect to renderDefault
			{
				unset( $this->getSession( 'Admin:Blog:Articles' )->filter );
			}
		}
		else
		{
			$this->template->articles = $this->blogArticles->findBy( array( 'users_id' => $this->user->id ), 'admin' )->order( 'created DESC' );
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

		$this->article = $this->blogArticles->findOneBy( array( 'id' => (int) $id ), 'admin' );

		if ( ! ( $this->article->users_id == $this->user->id || $this->user->isInRole( 'admin' ) ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte právo editovať tento článok.' );
		}

		$this['articleForm']->setDefaults( $this->article );


		$artCats = $this->categories->findArticleCategories( $id )->fetchPairs( NULL, 'id' );

		$this['articleForm']['category']->setDefaultValue( $artCats );

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

		$this->article = $this->blogArticles->findOneBy( array( 'id' => (int) $id ), 'admin' );

		if ( ! ( $this->article->users_id == $this->user->id || $this->user->isInRole( 'admin' ) ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte právo editovať tento článok.' );
		}

		$status = $this->article->status == 1 ? 0 : 1;
		$this->blogArticles->updateArticle( array( 'status' => $status ), (int) $id );

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

		$this->article = $this->blogArticles->findOneBy( array( 'id' => (int) $id ), 'admin' );

		if ( ! ( $this->article->users_id == $this->user->id || $this->user->isInRole( 'admin' ) ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte právo zmazať tento článok.' );
		}

		$this->blogArticles->delete( (int) $id );
		$this->flashMessage( 'Článok bol zmazaný.' );
		$this->redirect( ':Admin:Blog:Articles:default' );
	}



////protected////////////////////////////////////////////////////////////////


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
		foreach ( $secArr as $row )
		{
			if ( $row->id == 7 )
			{
				continue;
			} // 7 == Najnovšie and it is not optional value

			$params[$row->id] = str_repeat( '>', $lev * 1 ) . $row->title;
			if ( isset( $wholeArr[$row->id] ) )
			{
				$params = $this->getCategoriesSelectParams( $wholeArr, $wholeArr[$row->id], $params, $lev + 1 );
			}
		}

		return $params;
	}


////component/////////////////////////////////////////////////////////////////////

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
			->setAttribute( 'class', 'editor w100P' );

		$form->addTextArea( 'content', 'Text', 60 )
			->setRequired( 'Nenapísali ste žiaden text. Bez neho nebude formulár odoslaný.' )
			->setAttribute( 'class', 'area500 editor' );

		$catArray = $this->categories->getArray( 'admin' );
		$sParams = $this->getCategoriesSelectParams( $catArray, $catArray[0] );

		$form->addMultiSelect( 'category', 'Vyberte kategóriu', $sParams, 8 )
			->setRequired( 'Aplikácia vyžaduje, aby bola vybratá kategória pre článok.' )
			->setAttribute( 'class', 'w200 b7' );

		$form->addSubmit( 'sbmt', 'Uložiť' );

		$form->onSuccess[] = $this->articleFormSucceeded;

		return $form;
	}



	public function articleFormSucceeded( $form )
	{
		$values = $form->getValues();
		$id = (int) $this->getParameter( 'id' );
		$values->perex = preg_replace( '/<pre>/', '<pre class="prettyprint custom">', $values->perex );
		$values->content = preg_replace( '/<pre>/', '<pre class="prettyprint custom">', $values->content );

		if ( $id )
		{
			try
			{
				$this->blogArticles->updateArticle( $values, $id );
				$this->flashMessage( 'Článok bol upravený.' );
			}
			catch ( \Exception $e )
			{
				$form->addError( 'Pri ukladaní článku došlo k chybe.' );
				Debugger::log( $e->getMessage(), Debugger::ERROR );
				return $form;
			}
			$this->redirect( 'this' );
		}
		else
		{
			try
			{
				$values['users_id'] = $this->user->id;
				$this->blogArticles->insertArticle( $values );
				$this->flashMessage( 'Článok bol vytvorený.' );
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

		$authors = $this->users->findAll( 'admin' )->fetchPairs( 'id', 'user_name' );
		$form->addMultiSelect( 'authors', 'Autori', $authors, 7 )
			->setAttribute( 'class', 'b7' );

		$form->addMultiSelect( 'order', 'Zoradiť podľa', array(
			'users.user_name DESC' => 'Meno zostupne',
			'users.user_name ASC'  => 'Meno vzostupne',
			'created DESC'         => 'Vytvorený zostupne',
			'created ASC'          => 'Vytvorený vzostupne',
		), 7 )
			->setAttribute( 'class', 'b7' );

		$form->addText( 'interval', 'Interval posledných x dní', 3 )
			->addCondition( FORM::FILLED )
			->addRule( FORM::INTEGER, 'Hodnota v poli "Interval" musí byť číslo.' );

		$form->addCheckbox( 'remember', ' Zapamätať si nastavenia' );

		$form->addSubmit( 'sbmt', 'Filtrovať' );

		$form->onSuccess[] = $this->filterFormSucceeded;

		if ( $filter = $this->getSession( 'Admin:Blog:Articles' )->filter )
		{
			$form->setDefaults( $filter );
		}

		return $form;
	}



	public function filterFormSucceeded( $form )
	{
		$articleSession = $this->getSession( 'Admin:Blog:Articles' );
		$articleSession->setExpiration( 0 );
		$articleSession->filter = $form->getValues();
	}

////protected//////////////////////////////////////////////////////

	protected function getCategories()
	{
		$cats = new App\Model\Categories( $this->database );


	}


}
