<?php
namespace App\AdminModule\BlogModule\Presenters;

use Nette;
use App;
use Tracy\Debugger;

class CommentsPresenter extends App\AdminModule\Presenters\BaseAdminPresenter
{

	/** @var  App\Model\Articles @inject */
	public $articles;

	/** @var  App\Model\Comments @inject */
	public $comments;

	/** @var  Nette\Database\Table\IRow */
	protected $article;



	public function startup()
	{
		parent::startup();

		$this['breadcrumbs']->add( 'Články', ':Admin:Blog:Articles:default' );
	}



	public function renderDefault( $id )
	{
		$article = $this->article ? $this->article : $this->articles->findOneBy( array( 'id' => (int) $id ), 'admin' );

		$this->template->article = $article;

		$this['breadcrumbs']->add( 'Komentáre', ':Admin:Blog:Comments:default ' . $article->id );
	}


	/**
	 * @secured
	 * @param $id
	 * @param $comment_id
	 * @throws App\Exceptions\AccessDeniedException
	 */
	public function handleVisibility( $id, $comment_id )
	{
		if ( ! $this->user->isAllowed( 'comment', 'delete' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte oprávnenie mazať komentáre.' );
		}

		$this->article = $article = $this->articles->findOneBy( array( 'id' => (int) $id ), 'admin' );

		if ( ! ( $article->user->getId() == $this->user->id || $this->user->isInRole( 'admin' ) ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte právo zmazať tento komentár.' );
		}

		try
		{
			$this->comments->switchVisibility( (int) $comment_id );
			$this->flashMessage( 'Viditeľnosť komentára bola zmenená.' );
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(), Debugger::ERROR );
			$this->flashMessage( 'Pri editovaní komentára došlo k chybe.', 'error' );
		}


		if ( $this->isAjax() )
		{
			$this->redrawControl( 'comments' );
			return;
		}

		$this->redirect( 'this' );

	}


///////////component//////////////////////////////////////////////////////////


}
