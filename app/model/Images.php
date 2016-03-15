<?php
namespace App\Model;

use Nette;
use App;
use Kdyby;
use Nette\Utils\Image;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Tracy\Debugger;


class Images
{

	/** @var Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $imageRepository;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $moduleRepository;

	/** @var  String */
	protected $wwwDir;


	/**
	 * @param String $wwwDir
	 * @param Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct( $wwwDir, Kdyby\Doctrine\EntityManager $em )
	{
		$this->wwwDir = $wwwDir;
		$this->em = $em;
		$this->imageRepository = $em->getRepository( Entity\Image::class );
		$this->moduleRepository = $em->getRepository( Entity\Module::class );
	}


	/**
	 * @param int $id
	 * @return Entity\Image
	 */
	public function find( $id )
	{
		return $this->imageRepository->find( (int) $id );
	}


	/**
	 * @return array
	 */
	public function findAll()
	{
		return $this->imageRepository->findAll();
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

		return $this->imageRepository->findBy( $params, $order, $limit, $offset );
	}


	/**
	 * @param $params
	 * @return Null|Entity\Image
	 */
	public function findOneBy( $params )
	{
		return $this->imageRepository->findOneBy( $params );
	}


	/**
	 * @desc This method find all images ids which belongs to blog module.
	 * @return array
	 */
	public function findBlogImages()
	{
		$criteria = [ 'module.id =' => 1 ];

		$images = $this->imageRepository->createQueryBuilder()
			->select( 'i' )
			->from( 'App\Model\Entity\Image', 'i' )
			->whereCriteria( $criteria )
			->orderBy( 'i.created', 'DESC' )
			->getQuery();

		// Returns ResultSetbecause of paginator
		return new Kdyby\Doctrine\ResultSet( $images );
	}


	/**
	 * @param array $images
	 * @return array
	 * @throws App\Exceptions\CreateDirectoryException
	 * @throws App\Exceptions\DuplicateEntryException
	 */
	public function insert( Array $images )
	{
		$module = $this->moduleRepository->findOneBy( [ 'name =' => 'blog' ] );
		$path = $this->wwwDir . '/images/app';

		if ( ! is_dir( $path ) && ! mkdir( $path, 0777 ) ) // Dir blog does not exist and can not be created
		{
			throw new App\Exceptions\CreateDirectoryException( 'Nepodarilo sa vytvoriť adresár pre obrázky. Kontaktujte prosím administrátora.' );;
		}
		if ( ! is_dir( $path . '/thumbnails' ) && ! mkdir( $path . '/thumbnails', 0777 ) )
		{
			throw new App\Exceptions\CreateDirectoryException( 'Nepodarilo sa vytvoriť adresár pre obrázky. Kontaktujte prosím administrátora.' );
		}
		if ( ! is_dir( $path . '/mediums' ) && ! mkdir( $path . '/mediums', 0777 ) )
		{
			throw new App\Exceptions\CreateDirectoryException( 'Nepodarilo sa vytvoriť adresár pre obrázky. Kontaktujte prosím administrátora.' );
		}

		$result = [ 'errors' => [ ], 'saved_items' => [ ] ];
		foreach ( $images as $image )
		{
			if ( $image->isOk() )
			{
				$name = $image->getName();
				$sName = $image->getSanitizedName();
				$tmpName = $image->getTemporaryFile();

				$spl = new \SplFileInfo( $sName );
				$sName = $spl->getBasename( '.' . $spl->getExtension() ) . '-' . microtime( TRUE ) . '.' . $spl->getExtension();

				$this->em->beginTransaction();

				try
				{
					try
					{
						$image = new Entity\Image();
						$image->create( [
							'name'   => $sName,
							'module' => $module,
						] );

						// And if module is merged, image cant be persisted but have to be merged too.
						$this->em->merge( $image );
						$this->em->flush();
					}
					catch ( UniqueConstraintViolationException $e )
					{
						$result['errors'][] = 'Súbor s názvom ' . $name . ' už existuje.';
						$this->em->rollback();
						$this->reopenEm();
						// If exception occurs $module is detached and needs to be merged.
						$this->em->merge( $module );
						continue;
					}

					$img = Image::fromFile( $tmpName );
					$x = $img->width;
					$y = $img->height;

					if ( $x > 1200 || $y > 1000 )
					{
						$img->resize( 1200, 1000 );  // Keeps ratio => one of the sides can be shorter, but none will be longer
					}
					$img->save( $path . '/' . $sName );

					if ( $x > 400 )
					{
						$img->resize( 400, NULL );  // Width will be 400px and height keeps ratio
					}
					$img->save( $path . '/mediums/' . $sName );

					if ( $x > 150 )
					{
						$img->resize( 150, NULL );  // Width will be 150px and height keeps ratio
					}
					$img->save( $path . '/thumbnails/' . $sName );

					$result['saved_items'][] = $name;
					$this->em->commit();
				}
				catch ( \Exception $e )
				{
					$this->em->rollback();
					$this->reopenEm();
					$this->em->merge( $module );
					Debugger::log( $e->getMessage(), 'ERROR' );
					@unlink( $path . '/' . $sName );  // If something is saved, delete it.
					@unlink( $path . '/mediums/' . $sName );
					@unlink( $path . '/thumbnails/' . $sName );
					$result['errors'][] = 'Pri ukladaní súboru ' . $name . ' došlo k chybe. Súbor nebol uložený.';
				}
			}
			else
			{
				$result['errors'][] = 'Pri ukladaní súboru došlo k chybe.';
			}
		}

		return $result;

	}



	/**
	 * @param $img int|App\Model\Entity\Image
	 * @throws App\Exceptions\GeneralException
	 */
	public function delete( $img )
	{
		if ( is_numeric( $img ) )
		{
			$img = $this->imageRepository->find( (int) $img );
		}

		$this->em->remove( $img );
		$this->em->flush( $img );
	}


	public function imagesResultSet( $criteria )
	{
		$images = $this->imageRepository->createQueryBuilder()
			->select( 'i' )
			->from( 'App\Model\Entity\Image', 'i' )
			->whereCriteria( $criteria )
			->orderBy( 'i.id', 'DESC' )
			->getQuery();

		// Returns ResultSet because of paginator.
		return new Kdyby\Doctrine\ResultSet( $images );

	}


////Protected/Private//////////////////////////////////////////////////////

	protected function reopenEm()
	{
		$this->em = $this->em->create( $this->em->getConnection(), $this->em->getConfiguration() );
	}

	protected function refreshEm( $entity )
	{
		$this->em->refresh( $entity );
	}


}