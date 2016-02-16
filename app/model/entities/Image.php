<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="images",
 *        indexes={
 * 			@ORM\Index(name="image_module_id_idx", columns={"module_id"}),
 *        },
 *        options={"collate"="utf8_slovak_ci"}
 * )
 */
class Image
{

	use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;


	/** @ORM\Column(type="string", length=50) */
	protected $name;

	/**
	 * @ORM\ManyToOne(targetEntity="Module")
	 * @ORM\JoinColumn(name="module_id", referencedColumnName="id")
	 */
	protected $module = 1;

	/** @ORM\Column(type="datetime") */
	protected $created;

}