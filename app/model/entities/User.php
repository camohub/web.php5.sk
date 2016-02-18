<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Nette\Utils\ArrayHash;
use Tracy\Debugger;


/**
 * @ORM\Entity
 * @ORM\Table(name="users", options={"collate"="utf8_slovak_ci"})
 */
class User
{

	//use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;


	/** @ORM\Column(type="string", length=30, unique=true) */
	protected $user_name;

	/** @ORM\Column(type="string", length=50, unique=true) */
	protected $email;

	/** @ORM\Column(type="string", length=255, nullable=true) */
	protected $password;

	/** @ORM\Column(type="smallint"), options={"unsigned"=true} */
	protected $active;

	/** @ORM\Column(type="datetime") */
	protected $created;

	/** @ORM\Column(type="string", length=40, nullable=true) */
	protected $confirmation_code;

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


	public function __construct( ArrayHash $params )
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
	 * @param bool|FALSE $admin
	 */
	public function create( $params, $admin = FALSE )
	{
		$this->user_name = $params['user_name'];
		$this->password = $params['password'];
		$this->email = $params['email'];
		$this->active = 0;
		$this->confirmation_code = isset( $params['confirmation_code'] ) ? $params['confirmation_code'] : NULL;
		$this->social_network_params = isset( $params['social_network_params'] ) ? $params['social_network_params'] : NULL;

		foreach ( $params['roles'] as $role )
		{
			$this->addRole( $role );
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
			'social_network_params' => $this->social_network_params,
		];
	}


	public function id()
	{
		return $this->id;
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
		return $this->social_network_params;
	}

	public function getConfirmationCode()
	{
		return $this->confirmation_code;
	}


	
}