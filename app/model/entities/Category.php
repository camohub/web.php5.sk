<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;


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


	/** @ORM\Column(type="string", length=150) */
	protected $name;

	/** @var  @ORM\Column(type="string", length=150, unique=true) */
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

	/**
	 * Now is not used to generating menu, but still useful when deleting child entities.
	 * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
	 */
	protected $children;

	/** @ORM\Column(type="smallint"), options={"unsigned"=true} */
	protected $priority;

	/** @ORM\Column(type="smallint"), nullable=true, options={"unsigned"=true, "default"=1} */
	protected $visible;

	/** @ORM\Column(type="smallint"), nullable=false, options={"comment"="If app == 1 itme can't be deleted cause it is default part of application."} */
	protected $app;

	/**
	 * @ORM\ManyToOne(targetEntity="Module")
	 * @ORM\JoinColumn(name="module_id", referencedColumnName="id")
	 */
	protected $module;

	/**
	 * @ORM\ManyToMany(targetEntity="Article", mappedBy="categories", cascade={"persist"})
	 */
	protected $articles;


	public function __construct()
	{
		$this->articles = new ArrayCollection();
		$this->children = new ArrayCollection();
	}


	public function create( array $params )
	{
		$this->name = $params['name'];
		$this->slug = $params['slug'];
		$this->url = $params['url'];
		$this->url_params = $params['url_params'];
		$this->parent = isset( $params['parent'] ) ? $params['parent'] : NULL;
		$this->priority = isset( $params['priority'] ) ? $params['priority'] : 0;
		$this->app = isset( $params['app'] ) ? $params['app'] : 0;
		$this->module = isset( $params['module'] ) ? $params['module'] : NULL;
		$this->visible = isset( $params['visible'] ) ? $params['visible'] : 1;

		return $this;

	}




	
}