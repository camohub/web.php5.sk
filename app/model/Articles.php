<?php
namespace App\Model;

use Nette;
use Tracy\Debugger;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Exceptions;
use Nette\Utils\Strings;
use Kdyby;


class Articles extends Nette\Object
{

	CONST TABLE_NAME = 'articles';


	/** @var Nette\Database\Context */
	protected $database;

	/** @var Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $articleRepository;



	/**
	 * @param Nette\Database\Context $db
	 * @param Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct( Nette\Database\Context $db, Kdyby\Doctrine\EntityManager $em )
	{
		$this->database = $db;
		$this->em = $em;

		$this->articleRepository = $em->getRepository( Entity\Article::class );
	}


	/**
	 * @param bool $admin
	 * @param bool $order
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll( $admin = FALSE, $order = TRUE )
	{
		$articles = $admin ? $this->getTable() : $this->getTable()->where( 'status', 1 );
		$articles = $order ? $articles->order( 'created DESC' ) : $articles;
		return $articles;
	}


	/**
	 * @param $params
	 * @param bool $admin
	 * @param bool $order
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy( $params, $admin = FALSE, $order = TRUE )
	{
		$articles = $this->getTable()->where( $params );
		$articles = $admin ? $articles : $articles->where( 'status', 1 );
		$articles = $order ? $articles->order( 'created ASC' ) : $articles;
		return $articles;
	}


	/**
	 * @param $params
	 * @param bool $admin
	 * @return bool|mixed|Nette\Database\Table\IRow
	 */
	public function findOneBy( $params, $admin = FALSE )
	{
		if ( $admin )
		{
			$params['status'] = 1;
		}

		$result = $article = $this->articleRepository->createQueryBuilder()
			->select( 'a', 'u' )
			->from( 'App\Model\Entity\Article', 'a' )
			->whereCriteria( $params )
			->join( 'App\Model\Entity\User', 'u' )
			->getQuery()->getResult();

		return $result[0];
	}



	/**
	 * @desc This method find all articles ids in blog_article_category which belongs to cat_ids
	 * @param array $cat_ids
	 * @param bool $admin
	 * @return Nette\Database\Table\Selection
	 */
	public function findCategoryArticles( Array $cat_ids, $admin = FALSE )
	{
		//$art_ids = $this->getTable( 'articles_categories' )->where( 'category_id', $cat_ids )->fetchPairs( NULL, 'article_id' );
		//$articles = $this->findAll( $admin )->where( 'id', $art_ids );

		$articles = $this->articleRepository->createQueryBuilder()
			->select( 'a' )
			->from( 'App\Model\Entity\Article', 'a' )
			->whereCriteria( [ 'categories.id' => $cat_ids ] )
			->orderBy( 'a.created', 'DESC' )
			->getQuery();

		// Returns ResultSetbecause of paginator
		return new Kdyby\Doctrine\ResultSet( $articles );
	}



	/**
	 * @param array $params
	 * @return bool|int|Nette\Database\Table\IRow
	 */
	public function insertComment( Array $params )
	{
		return $this->getTable( 'blog_comments' )->insert( $params );
	}



	/**
	 * @param $params
	 * @throws Exceptions\InvalidArgumentException
	 * @throws Exceptions\GeneralException
	 * @return bool|int|Nette\Database\Table\IRow
	 */
	public function insertArticle( $params )
	{
		if ( ! isset( $params['category'] ) || ! $params['category'] )
		{
			throw new Exceptions\InvalidArgumentException( 'Params does not contain category parameter. In BlogArticles insertArticle($params).' );
		}

		$categories = $params['category']; // Cause is necessary to set values of blog_article_category table and unset $params['category']
		unset( $params['category'] );

		if ( ! in_array( 7, $categories ) )
		{
			$categories[] = 7;
		} // Add 7(Najnovšie)

		$params['created'] = time();
		$params['url_title'] = Strings::webalize( $params['title'] );

		try
		{
			$row = $this->getTable()->insert( $params );

			foreach ( $categories as $cat )
			{
				$this->getTable( 'blog_article_category' )->insert( array( 'articles_id' => $row->id, 'categories_id' => $cat ) );
			}
		}
		catch ( \Exception $e )
		{
			throw new Exceptions\GeneralException( 'BlogArticles->insertArticle() fails on: ' . $e->getMessage() );
		}

		return $row;

	}


	/**
	 * @param $params array
	 * @param $id int
	 * @throws Exceptions\InvalidArgumentException
	 * @throws Exceptions\GeneralException
	 * @return int
	 */
	public function updateArticle( $params, $id )
	{
		if ( ! isset( $params['category'] ) || ! $params['category'] )
		{
			throw new Exceptions\InvalidArgumentException( 'Params does not contain category parameter. In BlogArticles insertArticle($params).' );
		}

		$categories = $params['category']; // Cause is necessary to set values of blog_article_category table and unset $params['category']
		unset( $params['category'] );

		$categories = array_diff( $categories, array( 7 ) ); // Ensures that cat. 7 wont be inserted again cause delete() below do not deletes 7.

		$params['url_title'] = Strings::webalize( $params['title'] );

		try
		{
			$this->getTable()->where( 'id', $id )->update( $params );

			$this->getTable( 'blog_article_category' )// Delete old values
			->where( 'articles_id', $id )
				->where( 'categories_id NOT', array( 7 ) )
				->delete();

			foreach ( $categories as $cat )
			{
				$this->getTable( 'blog_article_category' )->insert( array( 'articles_id' => $id, 'categories_id' => $cat ) );
			}
		}
		catch ( \Exception $e )
		{
			throw new Exceptions\GeneralException( 'BlogArticles->updateArticle() fails on: ' . $e->getMessage() );
		}


		$row = $this->getTable()->where( 'id', $id )->update( $params );
	}



	/**
	 * @param $id
	 * @return int
	 */
	public function delete( $id )
	{
		return $this->getTable()->where( 'id', $id )->delete();
	}


////Protected/Private//////////////////////////////////////////////////////

	/**
	 * @param null $table
	 * @return Nette\Database\Table\Selection
	 */
	protected function getTable( $table = NULL )
	{
		return $this->database->table( $table ? $table : self::TABLE_NAME );
	}

}