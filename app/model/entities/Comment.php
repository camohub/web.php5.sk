<?php


namespace App\Model\Entity;


use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;


/**
 * @ORM\Entity
 * @ORM\Table(name="comments",
 *        indexes={
 * 			@ORM\Index(name="comments_article_id_idx", columns={"article_id"}),
 * 			@ORM\Index(name="comments_created_idx", columns={"created"})
 *        },
 *        options={"collate"="utf8_slovak_ci"}
 * )
 */
class Comment
{

	use \Kdyby\Doctrine\Entities\MagicAccessors;
	use \Kdyby\Doctrine\Entities\Attributes\Identifier;

	/**
	 * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments")
	 * @ORM\JoinColumn(name="article_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $article;

	/**
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $user;

	/** @ORM\Column(type="string", length=255) */
	protected $user_name;

	/** @ORM\Column(type="string", length=50) */
	protected $email;

	/** @ORM\Column(type="text") */
	protected $content;

	/** @ORM\Column(type="datetime") */
	protected $created;


	public function create( $params )
	{
		$this->content = $params['content'];
		$this->user = $params['user'];
		$this->email = $this->user->getEmail();
		$this->user_name = $this->user->getUserName();
		$this->article = $params['article'];
		$this->created = new DateTime();
	}
	
}