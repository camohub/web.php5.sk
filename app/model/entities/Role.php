<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="roles",
 *        options={"collate"="utf8_slovak_ci"}
 * ))
 */
class Role
{

	//use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;


	/** @ORM\Column(type="string", length=25) */
	protected $name;

	/**
	 * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
	 */
	protected $users;


	public function __construct()
	{
		$this->users = new ArrayCollection();
	}


	public function getName()
	{
		return $this->name;
	}


	public function getUsers()
	{
		return $this->users;
	}

	
}