<?php

namespace mageekguy\atoum\tests\units\iterators\recursives;

require_once __DIR__ . '/../../../runner.php';

use
	mageekguy\atoum,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\iterators\filters,
	mageekguy\atoum\iterators\recursives
;

class directory extends atoum\test
{
	public function beforeTestMethod($method)
	{
		$this->mockGenerator->shunt('__construct')->generate('recursiveDirectoryIterator');
	}

	public function testClass()
	{
		$this->testedClass->implements('iteratorAggregate');
	}

	public function test__construct()
	{
		$this
			->if($iterator = new recursives\directory())
				->variable($iterator->getPath())->isNull()
				->boolean($iterator->dotsAreAccepted())->isFalse()
				->array($iterator->getAcceptedExtensions())->isEqualTo(array('php'))
				->object($iteratorResolver = $iterator->getIteratorResolver())->isInstanceOf('mageekguy\atoum\dependencies\resolver')
				->object($iteratorResolver(array('directory' => __DIR__)))->isEqualTo(new \recursiveDirectoryIterator(__DIR__))
				->object($dotFilterResolver = $iterator->getDotFilterResolver())->isInstanceOf('mageekguy\atoum\dependencies\resolver')
				->object($dotFilterResolver(array('iterator' => $directoryIterator = new \recursiveDirectoryIterator(__DIR__))))->isEqualTo(new filters\recursives\dot($directoryIterator))
				->object($extensionFilterIterator = $iterator->getExtensionFilterResolver())->isInstanceOf('mageekguy\atoum\dependencies\resolver')
				->object($extensionFilterIterator(array('iterator' => $directoryIterator, 'extensions' => $extensions = array(uniqid()))))->isEqualTo(new filters\recursives\extension($directoryIterator, $extensions))
			->if($iterator = new recursives\directory($path = uniqid(), $resolver = new dependencies\resolver()))
			->then
				->string($iterator->getPath())->isEqualTo($path)
				->boolean($iterator->dotsAreAccepted())->isFalse()
				->array($iterator->getAcceptedExtensions())->isEqualTo(array('php'))
				->object($iterator->getIteratorResolver())->isInstanceOf('mageekguy\atoum\dependencies\resolver')
				->object($iteratorResolver(array('directory' => __DIR__)))->isEqualTo(new \recursiveDirectoryIterator(__DIR__))
				->object($dotFilterResolver = $iterator->getDotFilterResolver())->isInstanceOf('mageekguy\atoum\dependencies\resolver')
				->object($dotFilterResolver(array('iterator' => $directoryIterator = new \recursiveDirectoryIterator(__DIR__))))->isEqualTo(new filters\recursives\dot($directoryIterator))
				->object($extensionFilterIterator = $iterator->getExtensionFilterResolver())->isInstanceOf('mageekguy\atoum\dependencies\resolver')
				->object($extensionFilterIterator(array('iterator' => $directoryIterator, 'extensions' => $extensions = array(uniqid()))))->isEqualTo(new filters\recursives\extension($directoryIterator, $extensions))
			->if($resolver = new dependencies\resolver())
			->and($resolver['iterator'] = $iteratorResolver = new dependencies\resolver(function() {}))
			->and($resolver['filters\dot'] = $dotFilterResolver = new dependencies\resolver(function() {}))
			->and($resolver['filters\extension'] = $extensionFilterResolver = new dependencies\resolver(function() {}))
			->and($iterator = new recursives\directory($path = uniqid(), $resolver))
			->then
				->string($iterator->getPath())->isEqualTo($path)
				->boolean($iterator->dotsAreAccepted())->isFalse()
				->array($iterator->getAcceptedExtensions())->isEqualTo(array('php'))
				->object($iterator->getIteratorResolver())->isIdenticalTo($iteratorResolver)
				->object($iterator->getDotFilterResolver())->isIdenticalTo($dotFilterResolver)
				->object($iterator->getExtensionFilterResolver())->isIdenticalTo($extensionFilterResolver)
			;
	}

	public function testSetPath()
	{
		$this
			->if($iterator = new recursives\directory(uniqid()))
			->then
				->object($iterator->setPath($path = uniqid()))->isIdenticalTo($iterator)
				->string($iterator->getPath())->isEqualTo($path)
		;
	}

	public function testAcceptExtensions()
	{
		$this
			->if($iterator = new recursives\directory(uniqid()))
			->then
				->object($iterator->acceptExtensions($extensions = array(uniqid())))->isIdenticalTo($iterator)
				->array($iterator->getAcceptedExtensions())->isEqualTo($extensions)
				->object($iterator->acceptExtensions($extensions = array('.' . ($extension = uniqid()))))->isIdenticalTo($iterator)
				->array($iterator->getAcceptedExtensions())->isEqualTo(array($extension))
		;
	}

	public function testAcceptAllExtensions()
	{
		$this
			->if($iterator = new recursives\directory(uniqid()))
			->then
				->object($iterator->acceptAllExtensions())->isIdenticalTo($iterator)
				->array($iterator->getAcceptedExtensions())->isEmpty()
		;
	}

	public function testRefuseExtension()
	{
		$this
			->if($iterator = new recursives\directory(uniqid()))
			->then
				->object($iterator->refuseExtension('php'))->isIdenticalTo($iterator)
				->array($iterator->getAcceptedExtensions())->isEmpty()
			->if($iterator->acceptExtensions(array('php', 'txt', 'xml')))
			->then
				->object($iterator->refuseExtension('txt'))->isIdenticalTo($iterator)
				->array($iterator->getAcceptedExtensions())->isEqualTo(array('php', 'xml'))
		;
	}

	public function testAcceptDots()
	{
		$this
			->if($iterator = new recursives\directory(uniqid()))
			->then
				->object($iterator->acceptDots())->isIdenticalTo($iterator)
				->boolean($iterator->dotsAreAccepted())->isTrue()
		;
	}

	public function testRefuseDots()
	{
		$this
			->if($iterator = new recursives\directory(uniqid()))
			->then
				->object($iterator->refuseDots())->isIdenticalTo($iterator)
				->boolean($iterator->dotsAreAccepted())->isFalse()
		;
	}

	public function testGetIterator()
	{
		$this
			->if($iterator = new \mock\mageekguy\atoum\iterators\recursives\directory())
			->then
				->exception(function() use ($iterator) {
						$iterator->getIterator();
					}
				)
					->isInstanceOf('mageekguy\atoum\exceptions\runtime')
					->hasMessage('Path is undefined')
			->if($resolver = new dependencies\resolver())
			->and($resolver['iterator'] = new dependencies\resolver(function($resolver) use (& $recursiveDirectoryIterator) { return ($recursiveDirectoryIterator = new \mock\recursiveDirectoryIterator($resolver['directory']())); }))
			->and($resolver['filters\dot'] = new dependencies\resolver(function($resolver) use (& $dotFilterIterator) { return ($dotFilterIterator = new filters\recursives\dot($resolver['iterator']())); }))
			->and($resolver['filters\extension'] = new dependencies\resolver(function($resolver) use (& $extensionFilterIterator) { return ($extensionFilterIterator = new filters\recursives\extension($resolver['iterator'](), $resolver['extensions']())); }))
			->and($iterator = new recursives\directory($path = uniqid(), $resolver))
			->then
				->object($filterIterator = $iterator->getIterator())->isIdenticalTo($extensionFilterIterator)
				->object($filterIterator->getInnerIterator())->isIdenticalTo($dotFilterIterator)
				->object($filterIterator->getInnerIterator()->getInnerIterator())->isIdenticalTo($recursiveDirectoryIterator)
				->mock($filterIterator->getInnerIterator()->getInnerIterator())
					->call('__construct')->withArguments($path)->once()
			->if($iterator->acceptDots())
			->then
				->object($filterIterator = $iterator->getIterator())->isIdenticalTo($extensionFilterIterator)
				->object($filterIterator->getInnerIterator())->isIdenticalTo($recursiveDirectoryIterator)
				->mock($filterIterator->getInnerIterator())
					->call('__construct')->withArguments($path)->once()
			->if($iterator->refuseDots())
			->and($iterator->acceptExtensions(array()))
			->then
				->object($filterIterator = $iterator->getIterator())->isIdenticalTo($dotFilterIterator)
				->object($filterIterator->getInnerIterator())->isIdenticalTo($recursiveDirectoryIterator)
				->mock($filterIterator->getInnerIterator())
					->call('__construct')->withArguments($path)->once()
			->if($iterator->acceptDots())
			->and($iterator->acceptExtensions(array()))
			->then
				->object($filterIterator = $iterator->getIterator())->isIdenticalTo($recursiveDirectoryIterator)
				->mock($filterIterator)
					->call('__construct')->withArguments($path)->once()
		;
	}
}
