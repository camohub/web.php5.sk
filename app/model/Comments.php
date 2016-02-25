<?php
namespace App\Model;

use Nette;
use Kdyby;
use App\Exceptions\InvalidArgumentException;
use Tracy\Debugger;


class Comments
{

	/** @var  Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $commentRepository;



	/**
	 * @param Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct( Kdyby\Doctrine\EntityManager $em )
	{
		$this->em = $em;
		$this->commentRepository = $em->getRepository( Entity\Comment::class );
	}


	/**
	 * @param $ent Entity\Comment|int
	 * @param $params array
	 * @return int
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function update( $ent, $params )
	{
		if ( ! isset( $params['content'] ) )
		{
			throw new InvalidArgumentException( 'Argument content is not set.' );
		}
		if ( is_int( $ent ) )
		{
			$ent = $this->commentRepository->find( (int) $ent );
		}
		$ent->deleted = $ent->deleted ? FALSE : TRUE;
		$this->em->flush( $ent );
	}


	/**
	 * @param $ent
	 * @throws \Exception
	 */
	public function switchVisibility( $ent )
	{
		if ( is_int( $ent ) )
		{
			$ent = $this->commentRepository->find( (int) $ent );
		}
		$ent->deleted = $ent->deleted ? FALSE : TRUE;
		$this->em->flush( $ent );
	}


////Protected/Private//////////////////////////////////////////////////////


}