<?php


namespace App\Model\Entity;


use Kdyby;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Nette;
use Nette\Utils\Strings;
use Tracy\Debugger;


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
class Article extends Nette\Object
{

	use Kdyby\Doctrine\Entities\MagicAccessors;
	use Kdyby\Doctrine\Entities\Attributes\Identifier;


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
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $comments;

	/** @ORM\Column(type="datetime") */
	protected $created;


	public function __construct()
	{
		$this->comments = new ArrayCollection();
		$this->categories = new ArrayCollection();
	}


	public function create( $params )
	{
		$this->title = $params['title'];
		$this->url_title = $params['url_title'];
		$this->meta_desc = $params['meta_desc'];
		$this->perex = $params['perex'];
		$this->content = $params['content'];
		$this->user = $params['user'];
		$this->categories = $params['categories'];
		$this->status = isset( $params['status'] ) ? $params['status'] : 0;
		$this->created = $params['created'];
	}


	public function update( $params )
	{
		if ( isset( $params['title'] ) )
		{
			$this->title = $params['title'];
			$this->url_title = $params['url_title'];
		}
		if ( isset( $params['meta_desc'] ) )
		{
			$this->meta_desc = $params['meta_desc'];
		}
		if ( isset( $params['perex'] ) )
		{
			$this->perex = $params['perex'];
		}
		if ( isset( $params['content'] ) )
		{
			$this->content = $params['content'];
		}
		if ( isset( $params['categories'] ) )
		{
			$this->categories->clear();
			$this->categories = new ArrayCollection( $params['categories'] );
		}
		if ( isset( $params['status'] ) )
		{
			$this->status = $params['status'];
		}
	}


	public function getArray()
	{
		$arr = [
			'meta_desc' => $this->meta_desc,
			'title'     => $this->title,
			'perex'     => $this->perex,
			'content'   => $this->content,
			'status'    => $this->status,
		];

		$arr['categories'] = [ ];
		foreach ( $this->categories as $category )
		{
			if ( $category->id == 7 )
			{
				continue;
			}
			$arr['categories'][] = $category->id;
		}

		return $arr;
	}
	
}