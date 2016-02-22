<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="articles",
 *        indexes={
 * 			@ORM\Index(name="article_url_title_idx", columns={"url_title"}),
 * 			@ORM\Index(name="article_created_idx", columns={"created"}),
 * 			@ORM\Index(name="article_users_id_idx", columns={"user_id"})
 *        },
 *        options={"collate"="utf8_slovak_ci"}
 * )
 */
class Article
{

	use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;


	public function __construct()
	{
		$this->comments = new ArrayCollection();
		$this->roles = new ArrayCollection();
	}


	/** @ORM\Column(type="string", length=255) */
	protected $meta_desc;

	/** @ORM\Column(type="string", length=255) */
	protected $title;

	/** @ORM\Column(type="string", length=255) */
	protected $url_title;

	/** @ORM\Column(type="text") */
	protected $perex;

	/** @ORM\Column(type="text") */
	protected $content;

	/** @ORM\Column(type="smallint"), options={"unsigned"=true} */
	protected $status;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="articles")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $user;

	/**
	 * @ORM\ManyToMany(targetEntity="Category", inversedBy="articles")
	 * @ORM\JoinTable(name="articles_categories")
	 */
	protected $categories;

	/**
	 * @ORM\OneToMany(targetEntity="Comment", mappedBy="article", cascade={"remove"})
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $comments;

	/** @ORM\Column(type="datetime") */
	protected $created;
	
}