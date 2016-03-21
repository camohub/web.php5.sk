<?php
namespace App\Presenters;

use    Nette;
use    App;
use    App\Model;
use    Tracy\Debugger;


class ArticlesPresenter extends \App\Presenters\BasePresenter
{


	/** @var Nette\Caching\IStorage @inject */
	public $storage;

	/** @var  App\Model\Categories @inject */
	public $categories;

	/** @var  App\Model\Articles @inject */
	public $articles;

	/** @var  App\Model\Entity\Article */
	protected $article;

	/** @var  array */
	protected $optCompArray;



	public function startup()
	{
		parent::startup();

	}



	/**
	 * @desc This method is used for both category and article actions.
	 * @param $title
	 * @throws Nette\Application\BadRequestException
	 */
	public function renderShow( $title )
	{
		if ( $category = $this->categories->findOneBy( [ 'slug' => $title ] ) )  // Displays category.
		{
			$session_article = $this->getSession( 'article' );
			$session_article->category_id = $category->getId();  // Cat. id is used in else part. So is necessary to store it in session.

			$this->setCategoryId( $category->getId() );

			// Displays all articles from category and nested categories.
			$ids = $this->categories->findCategoryIds( $category );

			$articles = $this->articles->findCategoryArticles( $ids );

			$this->template->articles = $this->setPaginator( $articles );

			$this->setHeaderTags( $metaDesc = 'web.php5.sk - najnovšie články', $title = 'Najnovšie články' );

		}
		else // Displays one article.
		{
			$this->setCategoryId( $this->getSession( 'article' )->category_id );

			if ( ! $article = $this->article ) // $this->article comes from commentFormSucceeded().
			{
				$this->article = $article = $this->articles->findOneBy( [ 'url_title' => $title ] );
			}

			if ( ! $article )
			{
				throw new Nette\Application\BadRequestException( 'Požadovanú stránku sa nepodarilo nájsť.', 404 );
			}

			$this->template->article = $article;
			$this->template->comments = $article->getComments();

			$this->template->fb = TRUE; // If is true template loads FB javascript SDK
			$this->template->google = TRUE; // If is true template loads Google javascript API

			$this['breadcrumbs']->add( $article->getTitle(), ':Articles:show ' . $article->getUrlTitle() );

			$this->setHeaderTags( $metaDesc = $article->getMetaDesc(), $title = $article->getTitle() );

		}

	}


/////Helpers/////////////////////////////////////////////////////////////////////////

	private function setPaginator( $articles )
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 2;

		//$paginator->itemCount = $articles->count( '*' );
		$articles->applyPaginator( $paginator );

		//$this->template->articles = $articles->limit( $paginator->itemsPerPage, $paginator->offset );
		return $articles;

	}



/////component/////////////////////////////////////////////////////////////////////////

	protected function createComponentCommentForm()
	{
		$form = new Nette\Application\UI\Form;
		$form->addProtection( 'Možný útok CSRF. Údaje neboli uložené. Buď vypršala platnosť tokenu, alebo sa jedná o útok.' );

		$form->addTextArea( 'content', 'Vložte komentár' )
			->setRequired( 'Komentár je povinná položka' )
			->setAttribute( 'class', 'w100P h60' );

		// This is a trap for robots
		$form->addText( 'name', 'Vyplňte meno' );

		$form->addSubmit( 'send', 'Odoslať' );

		$form->onSuccess[] = $this->commentFormSucceeded;

		return $form;
	}



	public function commentFormSucceeded( $form )
	{
		if ( ! $this->user->isAllowed( 'comment', 'add' ) )
		{
			$this->flashMessage( 'Pridávať komentáre môžu iba regirovaní užívatelia.', 'error' );
			throw new App\Exceptions\AccessDeniedException( 'Pridávať komentáre môžu iba regirovaní užívatelia.' );
		}

		$values = $form->getValues();

		if ( $values->name )  // Probably robot insertion
		{
			$this->redirect( 'this' );
		}

		$title = $this->getParameter( 'title' );
		$this->article = $article = $this->articles->findOneBy( [ 'url_title =' => $title ] );

		$values->content = htmlspecialchars( $values->content, ENT_QUOTES | ENT_HTML401 );
		$values->content = preg_replace( '/\*\*([^*]+)\*\*/', '<b>$1</b>', $values->content );
		$values->content = preg_replace( '/```\\n?([^`]+)```/', '<pre class="prettyprint custom"><code>$1</code></pre>', $values->content );

		$params = [
			'article' => $article,
			'user_id' => $this->getUser()->id,
			'content' => $values->content,
		];

		try
		{
			$this->articles->insertComment( $params );
			$this->flashMessage( 'Ďakujeme za komentár', 'success' );
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			$form->addError( 'Došlo k chybe. Váš komentár sa nepodarilo odoslať. Skúste to prosím neskôr.' );
			return;
		}

		$this->redirect( 'this' );

	}

}
