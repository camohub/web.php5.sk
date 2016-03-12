<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="modules", options={"collate"="utf8_slovak_ci"})
 */
class Module
{

	//use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;


	/** @ORM\Column(type="string", length=30) */
	protected $name;

	/**
	 * @ORM\OneToMany(targetEntity="Image", mappedBy="module")
	 */
	protected $images;


	public function __construct( $params = [ ] )
	{
		$this->name = isset( $params['name'] ) ? $params['name'] : 'No value';
	}
	
}