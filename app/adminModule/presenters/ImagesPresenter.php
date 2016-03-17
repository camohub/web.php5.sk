<?php


namespace App\AdminModule\Presenters;


use Nette;
use App;
use Nette\Application\UI\Form;
use Tracy\Debugger;


class ImagesPresenter extends BaseAdminPresenter
{

	CONST ITEMS_PER_PAGE = 4;


	/** @var  App\Model\Images  @inject */
	public $images;



	public function startup()
	{
		parent::startup();

		if ( ! $this->user->isAllowed( 'image', 'add' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte oprávnenie editovať obrázky.' );
		}
	}



	public function renderBlog()
	{
		$images = $this->images->findBlogImages();

		$this->template->images = $this->setPaginator( $images );

		$this['breadcrumbs']->add( 'Obrázky - blog', ':Admin:Images:blog' );

	}



	public function renderEshop()
	{

	}


	/**
	 * @param $id
	 * @throws App\Exceptions\AccessDeniedException
	 * @secured
	 */
	public function handleDelete( $id )
	{
		if ( ! $this->user->isAllowed( 'image', 'delete' ) )
		{
			throw new App\Exceptions\AccessDeniedException( 'Nemáte oprávnenie mazať obrázky.' );
		}

		try
		{
			$this->images->delete( $id );
			$this->flashMessage( 'Obrázok bol zmazaný.' );
		}
		catch ( \Exception $e )
		{
			$this->flashMessage( 'Pri mazaní obrázku došlo k chybe.', 'error' );
			return;
		}

		$this->redirect( 'this' );
	}


//////protected//////////////////////////////////////////////////////


	private function setPaginator( $images )
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = self::ITEMS_PER_PAGE;

		//$paginator->itemCount = $articles->count( '*' );
		$images->applyPaginator( $paginator );

		//$this->template->articles = $articles->limit( $paginator->itemsPerPage, $paginator->offset );
		return $images;

	}


//////Control/////////////////////////////////////////////////////////

	public function createComponentInsertForm()
	{
		$form = new Form();

		$form->addUpload( 'images', 'Vyberte obrázok', TRUE )
			->setRequired( 'Nevybrali ste žiadny obrázok.' )
			->addRule( Form::IMAGE, 'Obrázok musí biť JPEG, PNG nebo GIF.' )
			->addRule( Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 1000 kB.', 1000 * 1024 /* v bytech */ )
			->setAttribute( 'class', 'button1 fWB' );

		$form->addSubmit( 'sbmt', 'Ulož' )
			->setAttribute( 'class', 'button1 fWB' );

		$form->onSuccess[] = $this->insertFormSucceeded;

		return $form;

	}



	public function insertFormSucceeded( $form )
	{

		$images = $form->getValues()->images;

		if ( count( $images ) > 7 )
		{
			$this->flashMessage( 'Naraz môžete ukladať maxim8lne 7 obrázkov.', 'error' );
			return;
		}

		try
		{
			$result = $this->images->insert( $images );
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			$this->flashMessage( 'Pri ukladaní obrázkov, došlo k chybe.', 'error' );
			return;
		}

		if ( $result['errors'] )
		{
			foreach ( $result['errors'] as $error )
			{
				$this->flashMessage( $error, 'error' );
			}
		}

		if ( $result['saved_items'] )
		{
			$this->flashMessage( 'Súbor ' . join( ', ', $result['saved_items'] ) . ' bol úspešne uložený na server.' );
		}

		$this->redirect( 'this' );

	}

}
