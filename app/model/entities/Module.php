<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="modules", options={"collate"="utf8_slovak_ci"})
 */
class Module
{

	use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;


	/** @ORM\Column(type="string", length=30) */
	protected $name = "No value";
	
}