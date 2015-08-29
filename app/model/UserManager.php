<?php

namespace App\Model;

use Nette,
	App,
	Nette\Security\Passwords,
	Nette\Utils\Random,
	Nette\Utils\Strings,
	Tracy\Debugger;


/**
 * Users management.
 * Do not use this class to manage users from social networks like FB
 */
class UserManager extends Nette\Object implements Nette\Security\IAuthenticator
{
	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'user_name',
		COLUMN_PASSWORD = 'password',
		COLUMN_ROLE = 'role',
		COLUMN_EMAIL = 'email',
		COLUMN_ACTIVE = 'active',
		COLUMN_CONFIRMATION_CODE = 'confirmation_code';


	/** @var Nette\Database\Context */
	private $database;


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	/**
	 * @param array $credentials
	 * @return Nette\Security\Identity
	 * @throws App\Exceptions\AccessDeniedException
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($user_name, $password) = $credentials;

		$row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $user_name)->where(self::COLUMN_PASSWORD.' NOT', NULL)->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!$row->active) {
			throw new App\Exceptions\AccessDeniedException;

		} elseif (!Passwords::verify($password, $row[self::COLUMN_PASSWORD])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD])) {
			$row->update(array(
				self::COLUMN_PASSWORD => Passwords::hash($password),
			));
		}

		$userArr = $row->toArray();
		unset($userArr[self::COLUMN_PASSWORD]);

		$rolesArr = array();
		foreach( $row->related('users_acl_roles', 'users_id') as $role )
		{
			$rolesArr[] = $role->ref('acl_roles', 'acl_roles_id')->name;
		}

		return new Nette\Security\Identity($row[self::COLUMN_ID], $rolesArr, $userArr);
	}


	/**
 	 * @desc Do not use it for users from social networks. They have its own managers.
	 * @param $params
	 * @return bool|int|Nette\Database\Table\IRow
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws \Exception
	 */
	public function add($params)
	{
		// Do not use transacion here. It is used in RegisterPresenter
		$params['password'] = Passwords::hash($params['password']);
		$code = Random::generate(10,'0-9a-zA-Z');

		try {
			$row = $this->database->table(self::TABLE_NAME)->insert(array(
						self::COLUMN_NAME => $params['user_name'],
						self::COLUMN_PASSWORD => $params['password'],
						self::COLUMN_EMAIL => $params['email'],
						self::COLUMN_ACTIVE => 0,
						self::COLUMN_CONFIRMATION_CODE => $code,
			));
		}
		catch(\PDOException $e)	{
			// This catch ONLY checks duplicate entry to fields with UNIQUE KEY
			$info = $e->errorInfo;

			// mysql==1062  sqlite==19  postgresql==23505
			if ($info[0] == 23000 && $info[1] == 1062)
			{
				// if/elseif returns the name of problematic field and value
				if( $this->database->table(self::TABLE_NAME)->where('user_name = ?', $params['user_name'])->fetch() )
				{
					$msg = 'user_name';	$code = 1;
				}
				elseif( $this->database->table(self::TABLE_NAME)->where('email = ?', $params['email'])->fetch() )
				{
					$msg = 'email';	$code = 2;
				}

				throw new App\Exceptions\DuplicateEntryException($msg, $code);

			}
			else { throw $e; }

		}

		$this->database->table('users_acl_roles')->insert(array('users_id' => $row->id, 'acl_roles_id' => 3));

		return $row;

	}
}
