<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Tracy\Debugger;


/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="users",
 *     options={"collate"="utf8_slovak_ci"},
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"user_name", "email", "resource"})}
 * )
 */
class User
{

	//use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;


	/** @ORM\Column(type="string", length=30) */
	protected $user_name;

	/** @ORM\Column(type="string", length=50) */
	protected $email;

	/** @ORM\Column(type="string", length=255, nullable=true) */
	protected $password;

	/** @ORM\Column(type="smallint"), options={"unsigned"=true} */
	protected $active;

	/** @ORM\Column(type="datetime") */
	protected $created;

	/** @ORM\Column(type="string", length=40, nullable=true) */
	protected $confirmation_code;

	/** @ORM\Column(type="string", length=20) */
	protected $resource;

	/** @ORM\Column(type="text", nullable=true) */
	protected $social_network_params;

	/**
	 * @ORM\OneToMany(targetEntity="Article", mappedBy="user", cascade={"persist"})
	 */
	protected $articles;

	/**
	 * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
	 * @ORM\JoinTable(name="users_roles")
	 */
	private $roles;


	public function __construct( $params = [ ] )
	{
		$this->articles = new ArrayCollection();
		$this->roles = new ArrayCollection();
		$this->created = new \DateTime();

		if ( $params )
		{
			$this->create( $params );
		}
	}


	/**
	 * @param $params
	 * @param bool $admin
	 */
	public function create( $params, $admin = FALSE )
	{
		$this->user_name = $params['user_name'];
		$this->password = $params['password'];
		$this->email = $params['email'];
		$this->active = isset( $params['active'] ) ? $params['active'] : 0;
		$this->confirmation_code = isset( $params['confirmation_code'] ) ? $params['confirmation_code'] : NULL;
		$this->resource = $params['resource'];
		$this->social_network_params = isset( $params['social_network_params'] ) ? $params['social_network_params'] : NULL;

		foreach ( $params['roles'] as $role )
		{
			$this->addRole( $role );
		}

	}


	public function update( $params )
	{
		if ( isset( $params['user_name'] ) )
		{
			$this->user_name = $params['user_name'];
		}
		if ( isset( $params['password'] ) )
		{
			$this->password = $params['password'];
		}
		// array_key_exists because isset does not catch NULL value. And isset because is faster than array_key_exists.
		if ( isset( $params['confirmation_code'] ) || array_key_exists( 'confirmation_code', $params ) )
		{
			$this->confirmation_code = $params['confirmation_code'];
		}
		if ( isset( $params['active'] ) )
		{
			$this->active = $params['active'];
		}
	}


	public function addRole( Role $role )
	{
		$this->roles->add( $role );
	}


	/**
	 * @return array
	 */
	public function getArray()
	{
		return [
			'user_name'             => $this->user_name,
			'email'                 => $this->email,
			'active'                => $this->active,
			'created'               => $this->created,
			'social_network_params' => $this->social_network_params ? unserialize( $this->social_network_params ) : NULL,
		];
	}


	public function getUserName()
	{
		return $this->user_name;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getRoles()
	{
		return $this->roles;
	}

	public function getActive()
	{
		return $this->active;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getSocialNetworkParams()
	{
		return $this->$this->social_network_params ? unserialize( $this->social_network_params ) : NULL;
	}

	public function getConfirmationCode()
	{
		return $this->confirmation_code;
	}


	public function getCreated()
	{
		return $this->created;
	}


	
}