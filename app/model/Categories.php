<?php
namespace App\Model;

use Nette;
use Kdyby;
use Doctrine;
use Tracy\Debugger;


class Categories
{

	CONST   TABLE_NAME = 'categories';

	/** @var Nette\Database\Context */
	protected $database;

	/** @var Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $categoryRepository;



	/**
	 * @param Nette\Database\Context $db
	 * @param Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct( Nette\Database\Context $db, Kdyby\Doctrine\EntityManager $em )
	{
		$this->database = $db;
		$this->em = $em;

		$this->categoryRepository = $em->getRepository( Entity\Category::class );
	}



	/**
	 * @desc Array of entities for menu component.
	 * @return array
	 */
	public function getMenu( $admin = FALSE )
	{
		$criteria = $admin ? [ 'parent_id =' => NULL ] : [ 'parent_id =' => NULL, 'visible =' => 1 ];
		return $this->categoryRepository->findBy( $criteria, [ 'priority' => 'ASC' ] );
	}



	/**
	 * @return array
	 * @desc This is only example which uses getArray() insted of getMenu(). Look at the menu_array.php.
	 * like $a[0][5] $a[0][6] where [0] is parent_id which is array of his children
	 */
	public function getArray( $admin = FALSE )
	{
		$selection = $this->findAll( $admin );
		$arr = [];
		while ( $row = $selection->fetch() )
		{
			$arr[$row['parent_id']][$row['id']] = $row;
		}

		return $arr;
	}



	/**
	 * @return Nette\Database\Table\Selection
	 */
	protected function findAll( $admin = FALSE )
	{
		$selection = $this->getTable();
		$selection = $admin ? $selection : $selection->where( 'visible = ?', 1 );

		return $selection->order( 'parent_id ASC, priority ASC' );
	}


	/**
	 * @param array $params
	 * @param bool $admin
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy( Array $params, $admin = FALSE )
	{
		$selection = $this->getTable()->where( $params );
		$selection = $admin ? $selection : $selection->where( 'visible = ?', 1 );

		return $selection->order( 'parent_id ASC, priority ASC' );
	}



	/**
	 * @param $s string (:Admin:Blog....)
	 * @return bool|mixed|Nette\Database\Table\IRow
	 */
	public function findOneByUrl( $s )
	{
		$s = ltrim( $s, ':' );
		$s = explode( ':', $s )[0];
		return $this->getTable()->where( 'url LIKE ?', '%' . $s . '%' )->order( 'parent_id' )->limit( 1 )->fetch();

	}


	/**
	 * @param array $params
	 * @param bool $admin
	 * @return bool|mixed|Nette\Database\Table\IRow
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
	 * @desc This method find all categories in blog_article_category which belongs to article_id
	 * @param int $id
	 * @admin bool $admin
	 * @return Nette\Database\Table\Selection
	 */
	public function findArticleCategories( $id, $admin = FALSE )
	{
		$cat_ids = $this->getTable( 'blog_article_category' )->select( 'categories_id' )
			->where( 'articles_id', $id )
			->where( 'categories_id NOT', array( 7 ) );

		$categories = $this->getTable()->where( 'id', $cat_ids );

		return $admin ? $categories : $categories->where( 'visible', 1 );
	}



	/**
	 * @desc be carefull if parent does not exist returns same row
	 * @param $id
	 * @param bool $admin
	 * @return Nette\Database\Table\IRow
	 */
	public function getParentRow( $id, $admin = FALSE )
	{
		$row1 = $admin ? $this->getTable()->get( (int) $id ) : $this->getTable()->where( array( 'id' => (int) $id, 'visible' => 1 ) )->fetch();
		$row2 = $this->getTable()->get( $row1->parent_id );

		return $row2 ? $row2 : $row1;

	}



	/**
	 * @param array $params
	 * @return bool|int|Nette\Database\Table\IRow
	 */
	public function add( Array $params )
	{
		return $this->getTable()->insert( $params );
	}



	/**
	 * @param $id
	 * @param $params
	 * @return int
	 */
	public function update( $id, $params )
	{
		return $this->getTable()->where( 'id', $id )->update( $params );
	}


	/**
	 * @param $id
	 * @return int
	 */
	public function delete( $id )
	{
		$ids = $this->deleteHelper( $id, $this->getArray( 'admin' ) );

		return $this->getTable()->where( array( 'id' => $ids, 'app' => 0 ) )->delete();
	}


	/**
	 * @return Nette\Database\Table\Selection
	 */
	public function findModules()
	{
		return $this->getTable( 'modules' );
	}



	/**
	 * @param $params
	 * @return bool|mixed|Nette\Database\Table\IRow
	 */
	public function findOneModuleBy( $params )
	{
		return $this->getTable( 'modules' )->where( $params )->fetch();
	}



	/**
	 * @desc Find ids of category and nested categories. Is faster than queryBuilder because of recursion.
	 * @param $category Entity\Category
	 * @param $ids array
	 * @return array
	 */
	public function findCategoryIds( Entity\Category $category, array $ids = [ ] )
	{
		$ids[] = $category->id;

		//$children = $this->categoryRepository->findBy( [ 'parent_id' => $category->id ] );

		foreach ( $category->children as $child )
		{
			$ids[] = $this->findCategoryIds( $child, $ids );
		}

		return $ids;
	}



	/**
	 * @desc Find ids of category and nested categories. Is slower that findBy in this case.
	 * @param $id integer
	 * @param $ids array
	 * @return array
	 */
	/*public function findCategoryIds( $id, array $ids = [ ] )
	{
		$ids[] = $id = (int) $id;

		$children = $this->categoryRepository
			->createQueryBuilder()
			->select( 'c.id' )
			->from( 'App\Model\Entity\Category', 'c' )
			->where( 'c.parent_id = :id' )
			->setParameter( 'id', $id )
			->getQuery()
			->execute( null, Doctrine\ORM\Query::HYDRATE_ARRAY );

		foreach ( $children as $child )
		{
			$ids[] = $this->findCategoryIds( $child['id'], $ids );
		}

		return $ids;
	}*/



//////Protected/Private///////////////////////////////////////////////////////

	/**
	 * @return Nette\Database\Table\Selection
	 */
	protected function getTable( $tableName = NULL )
	{
		return $tableName ? $this->database->table( $tableName ) : $this->database->table( self::TABLE_NAME );
	}



	/**
	 * @desc Recursively adds nested ids to array
	 * @param $id
	 * @param array $getArray
	 * @param array $ids
	 * @return array
	 */
	protected function deleteHelper( $id, Array $getArray, Array $ids = array() )
	{
		$ids[] = $id;

		if ( isset( $getArray[$id] ) )
		{
			foreach ( $getArray[$id] as $row )
			{
				$ids = $this->deleteHelper( $row->id, $getArray, $ids );
			}
		}

		return $ids;
	}

}