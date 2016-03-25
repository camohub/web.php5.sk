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
use Testbench;
use Tester\Assert;


class ArticlesPresenterTest extends Tester\TestCase
{

	use Testbench\TPresenter;
	use Testbench\TCompiledContainer;


	public function setUp()
	{

	}


	public function tearDown()
	{
		# Úklid
	}


	/**
	 * @desc This tests mainly injected services and components from BasePresenter and ArticlesPresenter.
	 */
	public function testDependencies()
	{
		$articles = $this->getService( 'App\Model\Articles' );

		Assert::true( property_exists( $articles, 'em' ) );
		Assert::true( property_exists( $articles, 'articleRepository' ) );
		Assert::true( property_exists( $articles, 'userRepository' ) );
		Assert::true( property_exists( $articles, 'categoryRepository' ) );
	}


	/*public function testMethods()
	{
		$articles = $this->presenter->articles;

		$findOneBy = $articles->findOneBy( [ 'id !=' => NULL ] );
		Assert::true( $findOneBy instanceof App\Model\Entity\Article );

		$find = $articles->find( $findOneBy->getId() );
		Assert::true( $find instanceof App\Model\Entity\Article );

		$findAll = $articles->findAll();
		Assert::true( is_array( $findAll ) && $findAll[0] instanceof App\Model\Entity\Article );

		$findBy = $articles->findBy( [ 'id !=' => NULL ] );
		Assert::true( is_array( $findBy ) && $findBy[0] instanceof App\Model\Entity\Article );

		$category = $this->presenter->categories->findOneBy( [ 'id !=' => NULL ] );
		$findCategoryArticles = $articles->findCategoryArticles( [ $category->getId() ] );
		Assert::true( $findCategoryArticles instanceof Kdyby\Doctrine\ResultSet );

		Assert::true( method_exists( $articles, 'insertComment' ) );

		$setDefaults = $articles->setDefaults( $findOneBy );
		Assert::true( is_array( $setDefaults )
			&& array_key_exists( 'meta_desc', $setDefaults )
			&& array_key_exists( 'title', $setDefaults )
			&& array_key_exists( 'perex', $setDefaults )
			&& array_key_exists( 'content', $setDefaults )
			&& array_key_exists( 'status', $setDefaults )
		);

		Assert::exception( function () use ( $articles )
		{
			$articles->createArticle( [ ] );
		}, 'App\Exceptions\InvalidArgumentException' );
		Assert::exception( function () use ( $articles )
		{
			$articles->createArticle( [ 'categories' => [ ] ] );
		}, 'App\Exceptions\InvalidArgumentException' );
		Assert::exception( function () use ( $articles )
		{
			$articles->updateArticle( [ ], 1 );
		}, 'App\Exceptions\InvalidArgumentException' );
		Assert::exception( function () use ( $articles )
		{
			$articles->updateArticle( [ 'categories' => [ ] ], 1 );
		}, 'App\Exceptions\InvalidArgumentException' );

		Assert::true( method_exists( $articles, 'delete' ) );
		Assert::true( method_exists( $articles, 'switchVisibility' ) );


	}*/

}

# Spuštění testovacích metod
( new ArticlesPresenterTest( $container ) )->run();

