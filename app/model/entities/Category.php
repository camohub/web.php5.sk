<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="categories",
 *        indexes={
 * 			@ORM\Index(name="priority_idx", columns={"priority"})
 *        },
 *        options={"collate"="utf8_slovak_ci"}
 * )
 */
class Category
{

	use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;


	public function __construct()
	{
		$this->articles = new ArrayCollection();
		$this->parent = 0;
	}


	/** @ORM\Column(type="string", length=150) */
	protected $name;

	/** @var  @ORM\Column(type="string", length=150) */
	protected $slug;

	/** @ORM\Column(type="string", length=25) */
	protected $url;

	/** @ORM\Column(type="string", length=255) */
	protected $url_params;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $parent_id;

	/**
	 * Needs to have parent_id param to be defined.
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="cascade")
	 */
	protected $parent;

	/** @ORM\OneToMany(targetEntity="Category", mappedBy="parent") */
	protected $children;

	/** @ORM\Column(type="smallint"), options={"unsigned"=true} */
	protected $priority;

	/** @ORM\Column(type="smallint"), nullable=true, options={"unsigned"=true, "default"=1} */
	protected $visible;

	/** @ORM\Column(type="smallint"), nullable=false, options={"comment"="If app == 1 itme can't be deleted cause it is default part of application."} */
	protected $app = 0;

	/**
	 * @ORM\ManyToOne(targetEntity="Module")
	 * @ORM\JoinColumn(name="module_id", referencedColumnName="id")
	 */
	protected $module = 1;

	/**
	 * @ORM\ManyToMany(targetEntity="Article", mappedBy="categories", cascade={"persist"})
	 */
	protected $articles;

	
}