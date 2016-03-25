<?php

/**
 * @testCase
 */

namespace Test;


$container = require __DIR__ . '/../../bootstrap.php';


use App;
use Nette;
use Kdyby;
use Tester;
use Tester\Assert;
use Testbench;
use Tracy\Debugger;


class ArticlesPresenterTest extends Tester\TestCase
{

	use Testbench\TCompiledContainer;
	use Testbench\TPresenter;


	protected $commentFormCSRFToken;


	public function setUp()
	{

	}


	public function tearDown()
	{
		# Úklid
	}


	/**
	 * @desc This tests RenderShow action for some category.
	 */
	public function testRenderShow()
	{
		$categories = $this->getService( 'App\Model\Categories' );
		$category = $categories->findOneBy( [ 'id !=' => NULL ] );

		/*$request = new Nette\Application\Request( ':Articles', 'GET', [
			'action' => 'show',
			'title'  => $category->getSlug(),
		] );*/
		$response = $this->checkAction( 'Articles:show', [ 'title' => $category->getSlug() ] );
		$template = $response->getSource();

		Assert::true( $template->articles instanceof Kdyby\Doctrine\ResultSet );
	}


	/**
	 * @desc This expects exception with code 404 because of title param which does not exist.
	 * @param string $title
	 */
	public function testRenderShow2( $title = 'With title parameter which does not exist.' )
	{
		Assert::exception( function () use ( $title )
		{
			$this->check( 'Articles:show', [ 'title' => $title ] );
		}, 'Nette\Application\BadRequestException', NULL, 404 );

	}



	/**
	 * @desc This test RenderShow for one article.
	 */
	public function testRenderShow3()
	{
		$articles = $this->getService( 'App\Model\Articles' );
		$article = $articles->findOneBy( [ 'id !=' => NULL ] );

		$response = $this->checkAction( 'Articles:show', [ 'title' => $article->getUrlTitle() ] );
		$template = $response->getSource();

		Assert::true( $template->article instanceof App\Model\Entity\Article );

	}



	/**
	 * @desc dataProvider loop use one instance of $this, which can share $articles/$article/$users/$user
	 * @dataProvider commentFormParameters
	 * @param $post
	 */
	public function testCommentForm( $post )
	{
		$post['do'] = 'commentForm-submit';
		// Next 4 params are shared in @dataProvider loop.
		// ie. @dataProvider loop shares the instance of $this for this testCase.
		$this->users = isset( $this->users ) ? $this->users : $this->getService( 'App\Model\Users' );
		$this->user = isset( $this->user ) ? $this->user : $this->users->findOneBy( [ 'roles.name =' => [ 'admin', 'registered' ] ] );
		$this->articles = isset( $this->articles ) ? $this->articles : $this->getService( 'App\Model\Articles' );
		$this->article = isset( $this->article ) ? $this->article : $this->articles->findOneBy( [ 'id !=' => NULL ] );

		$presenterFactory = $this->getService( 'Nette\Application\IPresenterFactory' );
		$presenter = $presenterFactory->createPresenter( 'Articles' );
		$presenter->session->disableNative();  // Because of CSRF protection.
		$presenter->session->setFakeId( 'fake_session_id' );
		$presenter->session->getSection( Nette\Forms\Controls\CsrfProtection::class )->token = 'fake_session_token';

		$identity = new Nette\Security\Identity( $this->user->getId(), $this->user->getRolesNames(), $this->user->getArray() );
		if ( $post['test'] === 'permission' )
		{
			$identity->setRoles( [ 'guest' ] );
		}

		$presenter->user->logIn( $identity );

		$request = new Nette\Application\Request( 'Articles', 'POST', [ 'action' => 'show', 'title' => $this->article->getUrlTitle() ], $post );

		if ( $post['test'] === 'permission' )
		{
			Assert::exception( function () use ( $presenter, $request )
			{
				$presenter->run( $request );
			}, App\Exceptions\AccessDeniedException::class );
			return;
		}
		$response = $presenter->run( $request );

		$errors = $presenter['commentForm']->getErrors();

		switch ( TRUE )
		{
			case ( $post['test'] === 1 ) :
				Assert::count( 2, $errors );  // token and content
				break;

			case ( $post['test'] === 2 || $post['test'] === 3 ) :
				Assert::count( 1, $errors );  // token or content
				break;

			case ( $post['test'] === 'success' ) :
				Assert::type( Nette\Application\Responses\RedirectResponse::class, $response );
				break;
		}

	}


	public function commentFormParameters()
	{
		return [
			[ [ 'test' => 1, 'content' => '' ] ],
			[ [ 'test' => 2, 'content' => 'Some required content but no _token_.' ] ],
			[ [ 'test' => 3, '_token_' => '1234567890Heqdr2Qe/iqq0xvIFudjtXEOBEo=', 'content' => '' ] ],
			[ [ 'test' => 'success', '_token_' => '1234567890Heqdr2Qe/iqq0xvIFudjtXEOBEo=', 'content' => 'Some required contnet.' ] ],
			[ [ 'test' => 'permission', '_token_' => '1234567890Heqdr2Qe/iqq0xvIFudjtXEOBEo=', 'content' => 'Some required contnet.' ] ],
		];
	}


}

# Spuštění testovacích metod
( new ArticlesPresenterTest( $container ) )->run();

