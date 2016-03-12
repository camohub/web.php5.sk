<?php

namespace App\Model;

use App;
use Nette;
use Kdyby;
use Nette\Security\Passwords;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Tracy\Debugger;


class Users
{

	/** @var Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $usersRepository;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $rolesRepository;



	/**
	 * @param Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct( Kdyby\Doctrine\EntityManager $em )
	{
		$this->em = $em;

		$this->usersRepository = $em->getRepository( Entity\User::class );
		$this->rolesRepository = $em->getRepository( Entity\Role::class );
	}



	/**
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->usersRepository->findAll();
	}


	/**
	 * @return array
	 */
	public function toSelect()
	{
		return $this->usersRepository->findPairs( 'user_name', [ 'user_name' => 'ASC' ], 'id' );
	}


	/**
	 * @param array $params
	 * @param bool $admin
	 * @return App\Model\Entity\User
	 */
	public function findOneBy( Array $params, $admin = FALSE )
	{
		if ( ! $admin )
		{
			$params['active ='] = 1;
		}

		return $this->usersRepository->findOneBy( $params );

	}



	/**
	 * @param array $params
	 * @param int|App\model\Entity\User $user
	 * @return int
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws \Exception
	 */
	public function updateUser( array $params, $user )
	{
		if ( is_numeric( $user ) )
		{
			$user = $this->usersRepository->find( (int) $user );
		}

		try
		{
			$user->update( $params );
			$this->em->flush( $user );
			return $user;
		}
		catch ( UniqueConstraintViolationException $e )
		{
			// if/elseif returns the name of problematic field and value
			if ( isset( $params['user_name'] ) && $this->usersRepository->findOneBy( [ 'user_name =' => $params['user_name'] ] ) )
			{
				$msg = 'user_name';
				$code = 1;
			}
			elseif ( isset( $params['email'] ) && $this->usersRepository->findOneBy( [ 'email =' => $params['email'] ] ) )
			{
				$msg = 'email';
				$code = 2;
			}

			throw new App\Exceptions\DuplicateEntryException( $msg, $code );
		}

	}


	/**
	 * @param int $id
	 * @param array $roles
	 * @throws \Exception
	 */
	public function setUserRoles( $id, $roles )
	{
		$user = $this->usersRepository->find( (int) $id );
		$roles = $this->rolesRepository->findBy( [ 'name =' => $roles ] );

		foreach ( $roles as $role )
		{
			$user->addRole( $role );
		}

	}


	/**
	 * @param int|App\Model\Entity\User $user
	 * @param array $credentials
	 * @return bool
	 * @throws App\Exceptions\AccessDeniedException
	 * @throws App\Exceptions\InvalidArgumentException
	 */
	public function updatePassword( $user, Array $credentials )
	{
		if ( is_numeric( $user ) )
		{
			$user = $this->usersRepository->find( (int) $user );
		}

		if ( ! Passwords::verify( $credentials['password'], $user->getPassword() ) )
		{
			throw new App\Exceptions\AccessDeniedException();
		}

		$newPassword = Passwords::hash( $credentials['newPassword'] );

		$this->updateUser( [ 'password' => $newPassword ], $user );

	}


	public function confirmEmail( $id, $code )
	{
		$user = $this->usersRepository->findOneBy( [ 'id =' => (int) $id, 'confirmation_code =' => $code ] );

		if ( ! $user )
		{
			throw new App\Exceptions\ConfirmationEmailException( 'Odkaz bol neplatný. Užívateľ s daným kódom neexistuje.', 1 );
		}

		$user->update( [
			'confirmation_code' => NULL,
			'active'            => 1,
		] );
		$this->em->flush( $user );

		return $user;

	}



//////////Protected/Private/////////////////////////////////////////////////


}