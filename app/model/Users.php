<?php

namespace App\Model;

use App;
use Nette;
use Kdyby;
use Nette\Security\Passwords;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette\Utils\Random;
use Tracy\Debugger;


class Users
{

	/** @var Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $usersRepository;

	/** @var Kdyby\Doctrine\EntityRepository */
	protected $rolesRepository;

	/** @var Nette\Mail\SendmailMailer */
	private $mailer;

	/** @var Nette\Mail\Message */
	private $mail;



	/**
	 * @param Kdyby\Doctrine\EntityManager $em
	 * @param Nette\Mail\SendmailMailer $mailer
	 * @param Nette\Mail\Message $mail
	 */
	public function __construct( Kdyby\Doctrine\EntityManager $em, Nette\Mail\SendmailMailer $mailer, Nette\Mail\Message $mail )
	{
		$this->em = $em;

		$this->usersRepository = $em->getRepository( Entity\User::class );
		$this->rolesRepository = $em->getRepository( Entity\Role::class );

		$this->mailer = $mailer;
		$this->mail = $mail;
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
	 * @param int|App\Model\Entity\User $user
	 * @param array $roles
	 * @throws \Exception
	 */
	public function setUserRoles( $user, array $roles )
	{
		if ( is_numeric( $user ) )
		{
			$user = $this->usersRepository->find( (int) $user );
		}

		foreach ( $user->getRoles() as $role )
		{
			$user->removeRole( $role );
		}

		$roles = $this->rolesRepository->findBy( [ 'id =' => $roles ] );

		foreach ( $roles as $role )
		{
			$user->addRole( $role );
		}

		$this->em->flush( $user );

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


	public function usersResultSet( array $criteria = [ ], $admin = FALSE )
	{
		$criteria = $admin ? $criteria : [ 'active =' => 1 ];

		$users = $this->usersRepository->createQueryBuilder()
			->select( 'u' )
			->from( 'App\Model\Entity\User', 'u' )
			->whereCriteria( $criteria )
			->orderBy( 'u.user_name', 'ASC' )
			->getQuery();

		// Returns ResultSet because of paginator.
		return new Kdyby\Doctrine\ResultSet( $users );
	}


	/**
	 * @param $ent Entity\User|int
	 * @return array
	 */
	public function setDefaults( $ent )
	{
		if ( is_numeric( $ent ) )
		{
			$ent = $this->usersRepository->find( (int) $ent );
		}
		return $arr = $ent->getArray();

	}


	public function setRolesDefaults( $id )
	{
		return $this->rolesRepository->findPairs( [ 'users.id =' => (int) $id ], 'id' );
	}


	public function rolesToSelect()
	{
		return $this->rolesRepository->findPairs( 'name', [ 'name' => 'ASC' ], 'id' );
	}


	/**
	 * @desc Transaction have to be initialized/commited in presenter because of registration.
	 * @param $template
	 * @param $user
	 * @throws App\Exceptions\DuplicateEntryException
	 */
	public function sendConfirmEmail( $template, $user )
	{
		if ( is_numeric( $user ) )
		{
			$user = $this->usersRepository->find( (int) $user );
		}

		$code = Random::generate( 10, '0-9a-zA-Z' );

		$this->updateUser( [ 'active' => 0, 'confirmation_code' => $code ], $user );

		$template->code = $code;
		$template->userId = $user->getId();

		$mail = $this->mail;
		$mail->setFrom( 'admin@email.sk' )
			->addTo( 'vladimir.camaj@gmail.com' )
			->setReturnPath( 'camo@tym.sk' )
			->setSubject( 'Overenie emailovej adresy.' )
			->setHtmlBody( $template );

		$mailer = $this->mailer;
		$mailer->send( $mail );

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