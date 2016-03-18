<?php


namespace Test;


use Nette;
use Tester;


abstract class BaseTest extends Tester\TestCase
{

	protected $container;

	protected $presenter;


	public function __construct( Nette\DI\Container $container )
	{
		$this->container = $container;
	}


	protected function getPresenter( $name, $canonicalize = FALSE )
	{
		$presenter = $this->container->getByType( 'Nette\Application\IPresenterFactory' )->createPresenter( $name );
		$presenter->autoCanonicalize = $canonicalize;

		//$presenter = new App\Presenters\DoctrinePresenter();

		return $presenter;
	}

}
