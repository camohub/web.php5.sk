<?php


namespace App\Model;


use App;
use Nette;
use Kdyby;
use Nette\Utils\Strings;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Tracy\Debugger;


class Articles extends Nette\Object
{

	/** @var Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $articleRepository;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $userRepository;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $categoryRepository;



	/**
	 * @param Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct( Kdyby\Doctrine\EntityManager $em )
	{
		$this->em = $em;

		$this->articleRepository = $em->getRepository( Entity\Article::class );
		$this->userRepository = $em->getRepository( Entity\User::class );
		$this->categoryRepository = $em->getRepository( Entity\Category::class );
	}



	/**
	 * @param int $id
	 * @return Entity\Article
	 */
	public function find( $id )
	{
		return $this->articleRepository->find( (int) $id );
	}



	/**
	 * @return array
	 */
	public function findAll()
	{
		return $this->articleRepository->findAll();
	}


	/**
	 * @param array $params
	 * @param null $order
	 * @param null $limit
	 * @param null $offset
	 * @param bool $admin
	 * @return array
	 */
	public function findBy( array $params, $order = NULL, $limit = NULL, $offset = NULL, $admin = FALSE )
	{
		if ( ! $admin )
		{
			$params['status ='] = 1;
		}
		$order = $order ? $order : [ 'created' => 'DESC' ];

		return $this->articleRepository->findBy( $params, $order, $limit, $offset );
	}


	/**
	 * @param $params
	 * @param bool $admin
	 * @return Null|Entity\Article
	 */
	public function findOneBy( $params, $admin = FALSE )
	{
		if ( ! $admin )
		{
			$params['status ='] = 1;
		}

		// QB make less queries if there is join, than repository->findOneBy lazy loading.
		$result = $article = $this->articleRepository->createQueryBuilder()
			->select( 'a', 'u' )
			->from( 'App\Model\Entity\Article', 'a' )
			->leftJoin( 'a.user', 'u' )
			->whereCriteria( $params )
			// setMaxResults bacause of getOneOrNullResult throws an exception if there is more than one result.
			->getQuery()->setMaxResults( 1 )->getOneOrNullResult();

		return $result;
	}



	/**
	 * @desc This method find all articles ids in blog_article_category which belongs to cat_ids
	 * @param array $cat_ids
	 * @param bool $admin
	 * @return array
	 */
	public function findCategoryArticles( Array $cat_ids, $admin = FALSE )
	{
		$criteria = $admin ? [ 'categories.id' => $cat_ids ] : [ 'categories.id' => $cat_ids, 'status =' => 1 ];

		$articles = $this->articleRepository->createQueryBuilder()
			->select( 'a' )
			->from( 'App\Model\Entity\Article', 'a' )
			->whereCriteria( $criteria )
			->orderBy( 'a.created', 'DESC' )
			->getQuery();

		// Returns ResultSet because of paginator.
		return new Kdyby\Doctrine\ResultSet( $articles );
	}



	/**
	 * @param $params
	 */
	public function insertComment( $params )
	{
		$params['user'] = $this->userRepository->find( $params['user_id'] );

		$comment = new Entity\Comment();
		$comment->create( $params );
		$this->em->persist( $comment );
		$this->em->flush( $comment );
	}

	
	/**
	 * @param $ent Entity\Article|int
	 * @return array
	 */
	public function setDefaults( $ent )
	{
		if ( ! $ent instanceof Entity\Article )
		{
			$ent = $this->articleRepository->find( (int) $ent );
		}
		return $arr = $ent->getArray();

	}



	/**
	 * @param array $values
	 * @return Entity\Article
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws App\Exceptions\GeneralException
	 * @throws App\Exceptions\InvalidArgumentException
	 */
	function createArticle( $values )
	{
		if ( ! isset( $values['categories'], $values['title'], $values['perex'], $values['meta_desc'], $values['content'], $values['user_id'] ) )
		{
			throw new App\Exceptions\InvalidArgumentException( 'Values does not contain some of required parameter: title, perex, content, meta_desc, user_id or category. In Articles->insertArticle($params).' );
		}
		if ( ! $values['categories'] )
		{
			throw new App\Exceptions\InvalidArgumentException( 'Params does not contain category parameter. In Articles->insertArticle($params).' );
		}

		$params = [ ];

		$params['categories'] = $values['categories'];
		if ( ! in_array( 7, $params['categories'] ) )
		{
			$params['categories'][] = 7;
		}
		$params['categories'] = $this->categoryRepository->findBy( [ 'id =' => $params['categories'] ] );
		$params['user'] = $this->userRepository->find( (int) $values['user_id'] );
		$params['title'] = $values['title'];
		$params['url_title'] = Strings::webalize( $values['title'] );
		$params['meta_desc'] = $values['meta_desc'];
		$params['perex'] = $values['perex'];
		$params['content'] = $values['content'];
		$params['status'] = $values['status'];
		$params['created'] = new Nette\Utils\DateTime();

		try
		{
			$article = new Entity\Article();
			$article->create( $params );
			$this->em->persist( $article );
			$this->em->flush();
		}
		catch ( UniqueConstraintViolationException $e )
		{
			throw new App\Exceptions\DuplicateEntryException( 'Article with title ' . $values['title'] . ' already exists. You have to change.' );
		}
		catch ( \Exception $e )
		{
			throw new App\Exceptions\GeneralException( 'Articles->insertArticle() fails on: ' . $e->getMessage() );
		}

		return $article;

	}


	/**
	 * @param $values array
	 * @param $id int
	 * @throws App\Exceptions\InvalidArgumentException
	 * @throws App\Exceptions\GeneralException
	 * @return int
	 */
	public function updateArticle( $values, $id )
	{
		if ( ! isset( $values['categories'] ) || ! $values['categories'] )
		{
			throw new App\Exceptions\InvalidArgumentException( 'Values does not contain category parameter. In Articles insertArticle($params).' );
		}

		$params = [ ];

		// Ensures that cat. 7 wont be inserted again cause delete() below do not deletes 7.
		if ( isset( $values['categories'] ) )
		{
			$params['categories'] = $values['categories'];
			if ( ! in_array( 7, $params['categories'] ) )  // Cause collection will be completely rewritten.
			{
				$params['categories'][] = 7;
			}
			$params['categories'] = $this->categoryRepository->findBy( [ 'id =' => $params['categories'] ] );
		}
		if ( isset( $values['title'] ) )
		{
			$params['title'] = $values['title'];
			$params['url_title'] = Strings::webalize( $values['title'] );
		}
		if ( isset( $values['meta_desc'] ) )
		{
			$params['meta_desc'] = $values['meta_desc'];
		}
		if ( isset( $values['perex'] ) )
		{
			$params['perex'] = $values['perex'];
		}
		if ( isset( $values['content'] ) )
		{
			$params['content'] = $values['content'];
		}
		if ( isset( $values['status'] ) )
		{
			$params['status'] = $values['status'];
		}

		try
		{
			$article = $this->articleRepository->find( $id );
			$article->update( $params );
			$this->em->flush( $article );
		}
		catch ( \Exception $e )
		{
			throw new App\Exceptions\GeneralException( $e->getMessage() );
		}

	}



	/**
	 * @param $article int|Entity\Article
	 * @return int
	 */
	public function delete( $article )
	{
		if ( is_numeric( $article ) )
		{
			$article = $this->articleRepository->find( (int) $article );
		}
		$this->em->remove( $article );
		$this->em->flush( $article );
	}



	public function switchVisibility( $article )
	{
		if ( is_numeric( $article ) )
		{
			$article = $this->find( (int) $article );
		}
		$article->update( [ 'status' => $article->getStatus() == 0 ? 1 : 0 ] );
		$this->em->flush( $article );
	}


////Protected/Private//////////////////////////////////////////////////////


}