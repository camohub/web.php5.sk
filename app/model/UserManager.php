<?php

namespace App\Model;

use Nette;
use App;
use Kdyby;
use Doctrine;
use Nette\Security\Passwords;
use Nette\Utils\Random;
use Nette\Utils\Strings;
use Tracy\Debugger;


/**
 * Users management.
 * Do not use this class to manage users from social networks like FB
 */
class UserManager extends Nette\Object implements Nette\Security\IAuthenticator
{
	const
		TABLE_NAME = 'users',
		COL_ID = 'id',
		COL_NAME = 'user_name',
		COL_PASSWORD = 'password',
		COL_ROLE = 'role',
		COL_EMAIL = 'email',
		COL_ACTIVE = 'active',
		COL_CONFIRMATION_CODE = 'confirmation_code';


	/** @var Nette\Database\Context */
	private $database;

	/** @var  Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $userRepository;


	public function __construct( Nette\Database\Context $database, Kdyby\Doctrine\EntityManager $em )
	{
		$this->database = $database;

		$this->em = $em;
		$this->userRepository = $em->getRepository( Entity\User::class );

	}


	/**
	 * @param array $credentials
	 * @return Nette\Security\Identity
	 * @throws App\Exceptions\AccessDeniedException
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate( array $credentials )
	{
		list( $user_name, $password ) = $credentials;

		//$row = $this->database->table(self::TABLE_NAME)->where(self::COL_NAME, $user_name)->where(self::COL_PASSWORD.' NOT', NULL)->fetch();

		$user = $this->userRepository->findOneBy( [ self::COL_NAME => $user_name, self::COL_PASSWORD . ' not' => NULL ] );

		if ( ! $user )
		{
			throw new Nette\Security\AuthenticationException( 'The username is incorrect.', self::IDENTITY_NOT_FOUND );

		}
		elseif ( ! $user->getActive() )
		{
			throw new App\Exceptions\AccessDeniedException;

		}
		elseif ( ! Passwords::verify( $password, $user->getPassword() ) )
		{
			throw new Nette\Security\AuthenticationException( 'The password is incorrect.', self::INVALID_CREDENTIAL );

		}
		elseif ( Passwords::needsRehash( $user->getPassword() ) )
		{
			/*$user->update( array(
				self::COL_PASSWORD => Passwords::hash( $password ),
			) );*/
			$user->password = Passwords::hash( $password );
			$this->em->persist( $user );
			$this->em->flush();
		}

		$userArr = $user->getArray();

		$rolesArr = [ ];
		foreach ( $user->getRoles() as $role )
		{
			$rolesArr[] = $role->name;
		}

		return new Nette\Security\Identity( $user->getId(), $rolesArr, $userArr );
	}


	/**
	 * @desc Do not use it for users from social networks. They have its own manager classes.
	 * @param $params
	 * @param bool $admin
	 * @return bool|int|Nette\Database\Table\IRow
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws \Exception
	 */
	public function add( $params, $admin = FALSE )
	{
		// Do not use transacion here. It is used in RegisterPresenter

		// If $admin is false is possible create only role "registered".
		if ( ! $admin || ! isset( $params['roles'] ) )
		{
			$params['roles'] = [ 'registered' ];
		}

		$params['roles'] = $this->em->getRepository( Entity\Role::class )->findBy( [ 'name' => $params['roles'] ] );

		$params[self::COL_PASSWORD] = Passwords::hash( $params['password'] );
		$params[self::COL_CONFIRMATION_CODE] = $code = Random::generate( 10, '0-9a-zA-Z' );

		try
		{
			$user = new Entity\User( $params ); // or $user->create( $params );
			$this->em->persist( $user );
			$this->em->flush();
		}
		catch ( Doctrine\DBAL\Exception\UniqueConstraintViolationException $e )
		{
			// if/elseif returns the name of problematic field and value
			if ( $this->userRepository->findOneBy( [ 'user_name =' => $params['user_name'] ] ) )
			{
				$code = 1;
				$msg = 'user_name';
			}
			elseif ( $this->userRepository->findOneBy( [ 'email = ' => $params['email'] ] ) )
			{
				$code = 2;
				$msg = 'email';
			}

			throw new App\Exceptions\DuplicateEntryException( $msg, $code );

		}

		return $user;

	}
}
