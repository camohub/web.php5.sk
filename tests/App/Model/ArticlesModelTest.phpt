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


	public function testMethods()
	{
		$articles = $this->getService( 'App\Model\Articles' );
		$categories = $this->getService( 'App\Model\Categories' );


		$findOneBy = $articles->findOneBy( [ 'id !=' => NULL ] );
		Assert::type( App\Model\Entity\Article::class, $findOneBy );

		$find = $articles->find( $findOneBy->getId() );
		Assert::type( App\Model\Entity\Article::class, $find );

		$findAll = $articles->findAll();
		Assert::type( 'array', $findAll );
		Assert::type( App\Model\Entity\Article::class, $findAll[0] );

		$findBy = $articles->findBy( [ 'id !=' => NULL ] );
		Assert::type( 'array', $findBy );
		Assert::type( App\Model\Entity\Article::class, $findBy[0] );

		$category = $categories->findOneBy( [ 'id !=' => NULL ] );
		$findCategoryArticles = $articles->findCategoryArticles( [ $category->getId() ] );
		Assert::type( Kdyby\Doctrine\ResultSet::class, $findCategoryArticles );

		$setDefaults = $articles->setDefaults( $findOneBy );
		Assert::type( 'array', $setDefaults );
		Assert::true( array_key_exists( 'meta_desc', $setDefaults )  // Do not use isset because of NULL.
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


		Assert::true( method_exists( $articles, 'insertComment' ) );
		Assert::true( method_exists( $articles, 'delete' ) );
		Assert::true( method_exists( $articles, 'switchVisibility' ) );


	}

}

# Spuštění testovacích metod
( new ArticlesPresenterTest( $container ) )->run();

