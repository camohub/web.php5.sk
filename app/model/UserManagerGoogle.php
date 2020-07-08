<?php

namespace App\Model;


use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette;
use App;
use Kdyby;
use Tracy\Debugger;


/**
 * Facebook users management.
 */
class UserManagerGoogle extends Nette\Object
{

	/** @var  Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $userRepository;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $roleRepository;



	public function __construct( Kdyby\Doctrine\EntityManager $em )
	{
		$this->em = $em;
		$this->userRepository = $em->getRepository( Entity\User::class );
		$this->roleRepository = $em->getRepository( Entity\Role::class );
	}


	/**
	 * @param array $credentials
	 * @return Nette\Security\Identity
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws \Exception
	 */
	public function authenticate( array $credentials )
	{
		// $email, $user_name, $social_network_params
		extract( $credentials );

		$user = $this->userRepository->findOneBy( [
			'email ='    => $email,
			'password =' => NULL,
			'resource =' => 'Google+',
		] );

		if ( ! $user )
		{
			$user = $this->add( $credentials );
		}

		$userArr = $user->getArray();

		return new Nette\Security\Identity( $user->getId(), [ 'registered' ], $userArr );
	}


	/**
	 * @param $params
	 * @param int $round
	 * @return Entity\User
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws \Exception
	 */
	public function add( $params, $round = 1 )
	{
		extract( $params );

		if ( $round > 1 ) $user_name = $user_name . ' ' . $round;

		// if name already exists call add() with the name + $round value // it makes e.g. Jozef Mak 3
		if ( $this->userRepository->findOneBy( [ 'user_name =' => $user_name ] ) )
		{
			return $this->add( $params, ++$round );
		}

		$this->em->beginTransaction();
		try
		{
			if ( $round > 30 )
			{
				throw new \Exception( 'Unexpected error. Variable $round in UserManagerGoogle->add() reached value 30.' );
			}

			$user = new Entity\User();
			$roles = $this->roleRepository->findBy( [ 'name =' => 'registered' ] );
			$user->create( [
				'user_name'             => $user_name,
				'password'              => NULL,
				'email'                 => $email,
				'active'                => 1,
				'resource'              => 'Google+',
				'social_network_params' => $social_network_params,
				'roles'                 => $roles,
			] );

			$this->em->persist( $user );
			$this->em->flush( $user );
		}
		catch ( UniqueConstraintViolationException $e )
		{
			$this->em->rollback();
			// if duplicate is name calls add() and the name join with $round value // it makes e.g. Jozef Mak 3
			$msg = 'email or name';
			$code = 1;
			throw new App\Exceptions\DuplicateEntryException( $msg, $code );
		}
		catch ( \Exception $e )
		{
			$this->em->rollback();
			throw $e;
		}

		$this->em->commit();
		return $user;

	}

}
