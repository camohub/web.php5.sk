<?php

/**
 * @testCase
 */

namespace Test;


$container = require __DIR__ . '/../bootstrap.php';
require_once( __DIR__ . '/../BaseTest.php' );


use App;
use Nette;
use Kdyby;
use Tester\Assert;


class ArticlesPresenterTest extends BaseTest
{

	public function setUp()
	{
		$this->presenter = $this->getPresenter( 'Articles' );
	}


	public function tearDown()
	{
		# Úklid
	}


	/**
	 * @desc This tests mainly injected services and components from BasePresenter and ArticlesPresenter.
	 */
	public function testBaseDependencies()
	{
		$category = $this->presenter->categories->findOneBy( [ 'id !=' => NULL ] );
		$request = new Nette\Application\Request( 'Articles', 'GET', [
			'action' => 'show',
			'title'  => $category->getSlug(),
		] );

		Assert::true( $this->presenter->em instanceof Kdyby\Doctrine\EntityManager );
		Assert::true( $this->presenter->categories instanceof App\Model\Categories );
		Assert::true( $this->presenter['menu'] instanceof App\Controls\Menu );
		Assert::true( $this->presenter['breadcrumbs'] instanceof App\Controls\Breadcrumbs );

		$response = $this->presenter->run( $request );
		Assert::true( $response instanceof Nette\Application\Responses\TextResponse );

		$template = $response->getSource();
		Assert::true( $template instanceof Nette\Application\UI\ITemplate );

	}


	/**
	 * @desc This tests RenderShow action for some category.
	 * @param $category
	 */
	public function testRenderShow( $category = NULL )
	{
		$category = $this->presenter->categories->findOneBy( [ 'id !=' => NULL ] );
		$request = new Nette\Application\Request( ':Articles', 'GET', [
			'action' => 'show',
			'title'  => $category->getSlug(),
		] );

		$response = $this->presenter->run( $request );
		$template = $response->getSource();

		Assert::true( $template->articles instanceof Kdyby\Doctrine\ResultSet );
	}


	/**
	 * @desc This expects exception with code 404 because of title param which does not exist.
	 * @param string $title
	 */
	public function testRenderShow2( $title = 'With title parameter which does not exist.' )
	{
		$presenter = $this->presenter;
		$request = new Nette\Application\Request( ':Articles', 'GET', [
			'action' => 'show',
			'title'  => $title,
		] );

		Assert::exception( function () use ( $presenter, $request )
		{
			$this->presenter->run( $request );
		}, 'Nette\Application\BadRequestException', NULL, 404 );

	}


	/**
	 * @desc This test RenderShow for one article.
	 * @param string $title
	 */
	public function testRenderShow3( $title = NULL )
	{
		$title = $this->presenter->articles->findOneBy( [ 'id !=' => NULL ] );
		$request = new Nette\Application\Request( ':Articles', 'GET', [
			'action' => 'show',
			'title'  => $title->getUrlTitle(),
		] );

		$response = $this->presenter->run( $request );
		$template = $response->getSource();

		Assert::true( $template->article instanceof App\Model\Entity\Article );

	}

}

# Spuštění testovacích metod
( new ArticlesPresenterTest( $container ) )->run();

