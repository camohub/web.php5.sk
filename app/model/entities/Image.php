<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;
use Nette;


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

	//use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;


	/** @ORM\Column(type="string", length=100, unique=true) */
	protected $name;

	/**
	 * @ORM\ManyToOne(targetEntity="Module", inversedBy="images")
	 * @ORM\JoinColumn(name="module_id", referencedColumnName="id")
	 */
	protected $module;

	/** @ORM\Column(type="datetime") */
	protected $created;


	public function create( $params )
	{
		$this->name = $params['name'];
		$this->module = $params['module'];
		$this->created = new Nette\Utils\DateTime();

	}


	public function getName()
	{
		return $this->name;
	}

}