<?php
namespace App\Model;

use App;
use Nette;
use Kdyby;
use Doctrine;
use Nette\Utils\Strings;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Tracy\Debugger;


class Categories
{

	/** @var Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $categoryRepository;



	/**
	 * @param Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct( Kdyby\Doctrine\EntityManager $em )
	{
		$this->em = $em;
		$this->categoryRepository = $em->getRepository( Entity\Category::class );
	}



	/**
	 * @param $id integer
	 */
	public function switchVisibility( $id )
	{
		$item = $this->categoryRepository->find( (int) $id );
		$item->setVisible( $item->getVisible() == 1 ? 0 : 1 );
		$this->em->persist( $item );
		$this->em->flush( $item );
	}



	/**
	 * @desc Find ids of category and nested categories. Is faster than queryBuilder because of recursion.
	 * @param $category Entity\Category
	 * @param $ids array
	 * @return array
	 */
	public function findCategoryIds( Entity\Category $category, array $ids = [ ] )
	{
		$ids[] = $category->getId();

		if ( $children = $this->categoryRepository->findBy( [ 'parent_id' => $category->getId() ] ) )
		{
			foreach ( $children as $child )
			{
				$ids[] = $this->findCategoryIds( $child, $ids );
			}
		}

		return $ids;
	}



	/**
	 * @desc produces an array of categories in format required by form->select
	 * @param bool $admin
	 * @param array $arr
	 * @param array $result
	 * @param int $lev
	 * @return array
	 */
	public function toSelect( $admin, $arr = [ ], $result = [ ], $lev = 0 )
	{
		if ( ! $arr )  // First call.
		{
			$arr = $this->findBy( [ 'parent_id =' => NULL ], [ 'priority' => 'ASC' ], $admin );
		}

		foreach ( $arr as $item )
		{
			if ( $item->getId() != 7 )  // 7 == Najnovšie and it is not optional value
			{
				$result[$item->getId()] = str_repeat( '>', $lev * 1 ) . $item->getName();
			}

			if ( $arr = $this->findBy( [ 'parent_id =' => $item->getId() ], [ 'priority' => 'ASC' ], $admin ) )
			{
				$result = $this->toSelect( $admin, $arr, $result, $lev + 1 );
			}
		}

		return $result;
	}



	/**
	 * @param bool $admin
	 * @return array
	 */
	public function findAll( $admin = FALSE )
	{
		return $this->categoryRepository->findAll();
	}



	/**
	 * @return array
	 */
	public function findPairs( $criteria, $value = NULL, $orderBy = array(), $key = NULL )
	{
		return $this->categoryRepository->findPairs( $criteria, $value, $orderBy, $key );
	}


	/**
	 * @param array $params
	 * @param array|NULL $order
	 * @param bool $admin
	 * @return array
	 */
	public function findBy( array $params, $order = NULL, $admin = FALSE )
	{
		$params = $admin ? $params : $params + [ 'visible =' => 1 ];
		$order = $order ?: [ 'priority' => 'ASC' ];

		return $this->categoryRepository->findBy( $params, $order );
	}



	/**
	 * @param array $params
	 * @param bool $admin
	 * @return mixed|null|object
	 */
	public function findOneBy( Array $params, $admin = FALSE )
	{
		if ( ! $admin )
		{
			$params['visible'] = 1;
		}

		return $this->categoryRepository->findOneBy( $params );

	}



	/**
	 * @desc Creats new cat. for blog module with specific params like url, module.
	 * @param array $params
	 * @return Entity\Category
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws App\Exceptions\InvalidArgumentException
	 */
	public function newBlogCategory( array $params )
	{
		if ( ! isset( $params['name'] ) )
		{
			throw new App\Exceptions\InvalidArgumentException( '$params["name"] is required in newBlogCategory( $params ).' );
		}
		$params['slug'] = Strings::webalize( $params['name'] );
		$params['url'] = ':Articles:show';
		$params['url_params'] = $params['slug'];

		// If parent_id is not set or is 0 => NULL
		$params['parent_id'] = isset( $params['parent_id'] ) && $params['parent_id'] != 0 ? $params['parent_id'] : NULL;
		// parent != parent_id
		$params['parent'] = $params['parent_id'] != FALSE ? $this->categoryRepository->find( $params['parent_id'] ) : NULL;

		$params['visible'] = 1;
		$params['app'] = 0;
		$params['prioryty'] = 0;

		$item = $this->categoryRepository->findOneBy( array( 'slug' => $params['slug'] ) );

		if ( $item )
		{
			throw new App\Exceptions\DuplicateEntryException( 'Kategória s názvom ' . $params['name'] . 'už existuje.', 1 );
		}

		$sameLevelCats = $this->findBy( [ 'parent_id =' => $params['parent_id'] ], NULL, 'admin' );
		foreach ( $sameLevelCats as $cat )
		{
			$cat->setPriority( $cat->getPriority() + 1 );
		}

		$category = new Entity\Category();
		$category->create( $params );
		$this->em->persist( $category );
		$sameLevelCats[] = $category;
		$this->em->flush( $sameLevelCats );  // All categories + new one
		//$this->em->clear();  // Was used when children collection was not set in __construct. Now is set.

		return $category;
	}



	/**
	 * @param $id int
	 * @param $name string
	 * @return int
	 * @throws App\Exceptions\ItemNotFoundException
	 * @throws \Exception
	 */
	public function updateName( $id, $name )
	{
		$item = $this->categoryRepository->find( $id );

		$slug = $url_params = Strings::webalize( $name );

		if ( $item )
		{
			try
			{
				$item->setName( $name );
				$item->setSlug( $slug );
				$item->setUrlParams( $url_params );

				$this->em->flush();
				return $item;
			}
			catch ( UniqueConstraintViolationException $e )
			{
				throw new App\Exceptions\DuplicateEntryException( $e );
			}
		}
		else
		{
			throw new App\Exceptions\ItemNotFoundException( 'Položka s názvom ' . $name . ' nebola nájdená.' );
		}


	}

	public function findAssoc( $criteria, $key = NULL )
	{
		return $this->categoryRepository->findAssoc( $key );
	}



	public function updatePriority( array $arr )
	{
		$pairs = $this->categoryRepository->findAssoc( 'id' );
		$i = 1;
		foreach ( $arr as $key => $val )
		{
			// if the array is large it would be better to update only changed items
			$pairs[(int) $key]->setParentId( $val == 0 ? NULL : (int) $val );
			$pairs[(int) $key]->setPriority( $i );
			$i++;
		}

		$this->em->flush();
	}



	/**
	 * @param $item
	 * @return array
	 * @throws ContainsArticleException
	 * @throws PartOfAppException
	 * @throws \Exception
	 */
	public function delete( $item )
	{
		$result = $this->canDelete( $item );

		if ( isset( $result['app'] ) )
		{
			$msg = 'Item can not be deleted because item ' . $result['app'] . ' is native part of application and can not be deleted.';
			throw new PartOfAppException( $msg );
		}
		if ( isset( $result['articles'] ) )
		{
			$msg = 'Item can not be deleted because item ' . $result['articles'] . ' contains one or more articles.';
			throw new ContainsArticleException( $msg );
		}

		$names = [ ];

		foreach ( $result['items'] as $item )
		{
			$names[] = $item->getName();
			$this->em->remove( $item );
		}

		$this->em->flush();

		return $names;
	}


//////Protected/Private///////////////////////////////////////////////////////

	/**
	 * @desc If $result contains app or article key, it means check is invalid ans can not be deleted.
	 * @param Entity\Category $item
	 * @param array $result
	 * @return array
	 */
	protected function canDelete( Entity\Category $item, $result = NULL )
	{
		$result = $result ?: [ 'items' => [ ] ];

		if ( $item->getArticles()->count() )
		{
			$result = [ 'articles' => $item->getName() ];
			return $result;
		}
		if ( $item->getApp() )
		{
			$result = [ 'app' => $item->getName() ];
			return $result;
		}

		foreach ( $item->getChildren() as $child )
		{
			$result = $this->canDelete( $child, $result );
		}

		$result['items'][] = $item;

		return $result;

	}


}

class PartOfAppException extends \Exception
{
	// Entity or nested entity is part of application and so it can not be deleted
}

class ContainsArticleException extends \Exception
{
	// Entity or nested entity contains one or more articles and so it can not be deleted.
}