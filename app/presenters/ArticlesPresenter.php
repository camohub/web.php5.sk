<?php
namespace App\Presenters;

use	Nette;
use	App;
use	App\Model;
use	Tracy\Debugger;


class ArticlesPresenter extends \App\Presenters\BasePresenter
{

	/** @var Nette\Caching\IStorage @inject */
	public $storage;

	/** @var  App\Model\Categories @inject */
	public $categories;

	/** @var  App\Model\BlogArticles @inject */
	public $blogArticles;

	/** @var  Nette\Database\Table\ActiveRow */
	protected $article;

	/** @var  array */
	protected $optCompArray;



	public function startup()
	{
		parent::startup();
		$this->optCompArray = $this->getOptionalComponents( $this->name );
	}



	public function renderShow( $title )
	{
		if ( $category = $this->categories->findOneBy( array( 'url_title' => $title ) ) )
		{
			// Displays all articles from category and nested categories.
			$ids = $this->categories->findCategoryIds( $category->id );

			$articles = $this->blogArticles->findCategoryArticles( $ids );

			$vp = $this['vp'];
			$paginator = $vp->getPaginator();
			$paginator->itemsPerPage = 7;
			$paginator->itemCount = $articles->count( '*' );

			$this->template->articles = $articles->limit( $paginator->itemsPerPage, $paginator->offset );

			$this->setHeaderTags( $metaDesc = 'web.php5.sk - najnovšie články', $title = ' Najnovšie články' );
			$this->template->optCompArray = $this->getOptionalComponents( $this->name ) ? $this->getOptionalComponents( $this->name ) : $this->optCompArray;
		}
		else // Displays one article.
		{
			if ( ! $article = $this->article ) // This->article comes from commentFormSucceeded() on error.
			{
				$this->article = $article = $this->blogArticles->findOneBy( array( 'url_title' => $title ) );
			}

			if ( ! $article )
			{
				throw new Nette\Application\BadRequestException( 'Požadovanú stránku sa nepodarilo nájsť.' );
			}

			$this->template->article = $article;

			$this->template->comments = $article->related( 'blog_comments', 'blog_articles_id' )->order( 'created_at' );

			$this['breadcrumbs']->add( $article->title, ':Articles:show ' . $article->url_title );

			$this->setHeaderTags( $metaDesc = $article->meta_desc, $title = $article->title );

			$this->template->fb = TRUE; // If is true template loads FB javascript SDK
			$this->template->google = TRUE; // If is true template loads Google javascript API

			$this->template->optCompArray = $this->getOptionalComponents( $this->name . ' ' . $article->id ) ? $this->getOptionalComponents( $this->name . ' ' . $article->id ) : $this->optCompArray;

		}

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
		$title = $this->getParameter( 'title' );
		$this->article = $article = $this->blogArticles->findOneBy( array( 'url_title' => $title ) );


		if ( $values->name !== '' ) // Probably robot insertion
		{
			$this->redirect( 'this' );
			return;
		}

		$values->content = htmlspecialchars( $values->content, ENT_QUOTES | ENT_HTML401 );
		$values->content = preg_replace( '/\*\*([^*]+)\*\*/', '<b>$1</b>', $values->content );
		$values->content = preg_replace( '/```\\n?([^`]+)```/', '<pre class="prettyprint custom"><code>$1</code></pre>', $values->content );

		$params = array(
			'blog_articles_id' => $article->id,
			'users_id'         => $this->getUser()->id,
			'name'             => $this->getUser()->identity->user_name,
			'email'            => $this->getUser()->identity->email,
			'content'          => $values->content,
		);
		$row = $this->blogArticles->insertComment( $params );

		if ( $row )
		{
			$this->flashMessage( 'Ďakujeme za komentár', 'success' );
			$this->redirect( 'this' );
		}
		else
		{
			$form->addError( 'Došlo k chybe. Váš komentár sa nepodarilo odoslať. Skúste to prosím neskôr.' );
		}
	}

}
