<?php

namespace Test;

use Nette,
	Tester,
	Tester\Assert;

$container = require __DIR__ . '/../bootstrap.php';


class ExampleTest extends Tester\TestCase
{


	function __construct()
	{

	}


	function setUp()
	{
	}


	function testSomething()
	{
		Assert::true( true );
	}

}


$test = new ExampleTest($container);
$test->run();
