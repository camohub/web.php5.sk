<?php

namespace App\Model;


use Nette;
use App;
use Kdyby;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


/**
 * Facebook users management.
 */
class UserManagerFB extends Nette\Object
{

	/** @var Kdyby\Doctrine\EntityManager */
	private $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	private $userRepository;

	/** @var Kdyby\Doctrine\EntityRepository */
	private $roleRepository;



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
			'resource =' => 'Facebook',
		] );

		if ( ! $user )
		{
			$user = $this->add( $credentials );
		}

		$userArr = $user->getArray();

		return new Nette\Security\Identity( $user->getId(), [ 'registered' ], $userArr );
	}


	/**
	 * @desc $trial is increased in recursion if user_name already exists. It produces Fero Mrkva 3
	 * @param $params
	 * @param int $trial
	 * @return Entity\User
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws \Exception
	 */
	public function add( $params, $trial = 1 )
	{
		extract( $params );

		if ( $trial !== 1 )
		{
			$user_name = $user_name . ' ' . $trial;
		}

		// if name already exists call add() with the name + $trial value // it makes e.g. Jozef Mak 3
		if ( $this->userRepository->findOneBy( [ 'user_name =' => $user_name ] ) )
		{
			return $this->add( $params, ++$trial );
		}

		$this->em->beginTransaction();
		try
		{
			if ( $trial > 30 )
			{
				throw new \Exception( 'Unexpected error. Variable $trial in UserManagerFB->add() reached value 30.' );
			}

			$user = new Entity\User();
			$roles = $this->roleRepository->findBy( [ 'name =' => 'registered' ] );
			$user->create( [
				'user_name'             => $user_name,
				'password'              => NULL,
				'email'                 => $email,
				'active'                => 1,
				'resource'              => 'Facebook',
				'social_network_params' => $social_network_params,
				'roles'                 => $roles,
			] );

			$this->em->persist( $user );
			$this->em->flush( $user );
		}
		catch ( UniqueConstraintViolationException $e )
		{
			$this->em->rollback();
			// if duplicate is name calls add() and the name join with $trial value // it makes ie. Jozef Mak 3
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
