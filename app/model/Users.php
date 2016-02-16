<?php
namespace App\Model;

use App;
use Nette;
use Nette\Security\Passwords;
use Tracy\Debugger;


class Users
{

	CONST   TABLE_NAME = 'users';


	/** @var Nette\Database\Context */
	protected $database;



	/**
	 * @param Nette\Database\Context $db
	 */
	public function __construct( Nette\Database\Context $db )
	{
		$this->database = $db;
	}



	/**
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll( $admin = NULL )
	{
		$users = $admin ? $this->getTable() : $this->getTable()->where( 'active', 1 );
		return $users->order( 'user_name ASC' );
	}


	/**
	 * @param array $params
	 * @param bool $admin
	 * @return FALSE|Nette\Database\Table\IRow
	 */
	public function findOneBy( Array $params, $admin = FALSE )
	{
		$users = $this->getTable()->where( $params );
		$users = $admin ? $users : $users->where( 'active', 1 );
		return $users->limit( 1 )->fetch();
	}


	/**
	 * @return Nette\Database\Table\Selection
	 */
	public function findAllRoles()
	{
		return $this->getTable( 'acl_roles' );
	}


	/**
	 * @param int $id
	 * @param bool $admin
	 * @return Nette\Database\Table\Selection
	 */
	public function findUserRoles( $id, $admin = FALSE )
	{
		return $this->getTable( 'users_acl_roles' )->where( 'users_id = ?', (int) $id );
	}


	/**
	 * @param array $params
	 * @param $id
	 * @return int
	 * @throws App\Exceptions\DuplicateEntryException
	 */
	public function updateUser( Array $params, $id )
	{
		try
		{
			return $this->getTable()->where( 'id', (int) $id )->update( $params );
		}
		catch ( \PDOException $e )
		{
			// This catch ONLY checks duplicate entry to fields with UNIQUE KEY
			$info = $e->errorInfo;

			// mysql==1062  sqlite==19  postgresql==23505
			if ( $info[0] == 23000 && $info[1] == 1062 )
			{
				// if/elseif returns the name of problematic field and value
				if ( $this->database->table( self::TABLE_NAME )->where( 'user_name = ?', $params['user_name'] )->fetch() )
				{
					$msg = 'user_name';
					$code = 1;
				}
				elseif ( $this->database->table( self::TABLE_NAME )->where( 'email = ?', $params['email'] )->fetch() )
				{
					$msg = 'email';
					$code = 2;
				}

				throw new App\Exceptions\DuplicateEntryException( $msg, $code );

			}
			else
			{
				throw $e;
			}
		}
	}


	/**
	 * @param array $set
	 * @param array $cond
	 * @return int
	 * @throws App\Exceptions\DuplicateEntryException
	 */
	public function updateUserBy( Array $set, Array $cond )
	{
		try
		{
			return $this->getTable()->where( $cond )->update( $set );
		}
		catch ( \PDOException $e )
		{
			// This catch ONLY checks duplicate entry to fields with UNIQUE KEY
			$info = $e->errorInfo;

			// mysql==1062  sqlite==19  postgresql==23505
			if ( $info[0] == 23000 && $info[1] == 1062 )
			{
				// if/elseif returns the name of problematic field and value
				if ( $this->database->table( self::TABLE_NAME )->where( 'user_name = ?', $set['user_name'] )->fetch() )
				{
					$msg = 'user_name';
					$code = 1;
				}
				elseif ( $this->database->table( self::TABLE_NAME )->where( 'email = ?', $set['email'] )->fetch() )
				{
					$msg = 'email';
					$code = 2;
				}

				throw new App\Exceptions\DuplicateEntryException( $msg, $code );

			}
			else
			{
				throw $e;
			}
		}

	}


	/**
	 * @param $id
	 * @param $roles
	 * @throws \Exception
	 */
	public function setUsersRoles( $id, $roles )
	{
		$table = $this->getTable( 'users_acl_roles' );

		foreach ( $roles as $role )
		{
			$table->insert( array( 'users_id' => (int) $id, 'acl_roles_id' => (int) $role ) );
		}

	}


	/**
	 * @param $id
	 * @param array $credentials
	 * @return bool
	 * @throws App\Exceptions\AccessDeniedException
	 * @throws App\Exceptions\InvalidArgumentException
	 */
	public function updatePassword( $id, Array $credentials )
	{
		if ( ! $user = $this->findOneBy( array( 'id' => (int) $id ) ) )
		{
			throw new App\Exceptions\InvalidArgumentException( 'Param $id did not match any user in database', 'error' );
		}

		if ( ! Passwords::verify( $credentials['password'], $user->password ) )
		{
			throw new App\Exceptions\AccessDeniedException;
		}

		$newPassword = Passwords::hash( $credentials['newPassword'] );

		$count = $this->updateUser( array( 'password' => $newPassword ), (int) $id );

		return $count ? TRUE : FALSE;
	}



//////////Protected/Private/////////////////////////////////////////////////

	/**
	 * @return Nette\Database\Table\Selection
	 */
	protected function getTable( $table = NULL )
	{
		return $this->database->table( $table ? $table : self::TABLE_NAME );
	}

}