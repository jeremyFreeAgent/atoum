<?php

namespace mageekguy\atoum\tests\units\asserter;

use
	mageekguy\atoum,
	mageekguy\atoum\asserter
;

require_once __DIR__ . '/../../runner.php';

class generator extends atoum\test
{
	public function test__construct()
	{
		$this
			->if($generator = new asserter\generator())
			->then
				->object($generatorDepedencies = $generator->getDepedencies())->isInstanceOf('mageekguy\atoum\dependencies')
				->boolean(isset($generatorDepedencies['locale']))->isTrue()
				->object($generator->getLocale())->isInstanceOf('mageekguy\atoum\locale')
			->if($generator = new asserter\generator($dependencies = new atoum\dependencies()))
			->then
				->object($generatorDepedencies = $generator->getDepedencies())->isIdenticalTo($dependencies['mageekguy\atoum\asserter\generator'])
				->boolean(isset($generatorDepedencies['locale']))->isTrue()
				->object($generator->getLocale())->isInstanceOf('mageekguy\atoum\locale')
			->if($dependencies = new atoum\dependencies())
			->and($dependencies['mageekguy\atoum\asserter\generator']['locale'] = $localeInjector = function() use (& $locale) { return $locale = new atoum\locale(); })
			->and($generator = new asserter\generator($dependencies))
			->then
				->object($generatorDepedencies = $generator->getDepedencies())->isIdenticalTo($dependencies['mageekguy\atoum\asserter\generator'])
				->object($generatorDepedencies['locale'])->isIdenticalTo($localeInjector)
				->object($generator->getLocale())->isIdenticalTo($locale)
		;
	}

	public function test__get()
	{
		$this
			->if($generator = new asserter\generator())
			->then
				->exception(function() use ($generator, & $asserter) { $generator->{$asserter = uniqid()}; })
					->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
					->hasMessage('Asserter \'' . $asserter . '\' does not exist')
				->object($generator->variable)->isInstanceOf('mageekguy\atoum\asserters\variable')
		;
	}

	public function test__set()
	{
		$this
			->if($generator = new asserter\generator())
			->then
				->when(function() use ($generator, & $alias, & $asserter) { $generator->{$alias = uniqid()} = ($asserter = uniqid()); })
					->array($generator->getAliases())->isEqualTo(array($alias => $asserter))
				->when(function() use ($generator, & $otherAlias, & $otherAsserter) { $generator->{$otherAlias = uniqid()} = ($otherAsserter = uniqid()); })
					->array($generator->getAliases())->isEqualTo(array($alias => $asserter, $otherAlias => $otherAsserter))
		;
	}

	public function test__call()
	{
		$this
			->if($generator = new asserter\generator())
			->then
				->exception(function() use ($generator, & $asserter) { $generator->{$asserter = uniqid()}(); })
					->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
					->hasMessage('Asserter \'' . $asserter . '\' does not exist')
				->object($generator->variable(uniqid()))->isInstanceOf('mageekguy\atoum\asserters\variable')
		;
	}

	public function testSetDepedencies()
	{
		$this
			->if($generator = new asserter\generator())
			->then
				->object($generator->setDepedencies($dependencies = new atoum\dependencies()))->isIdenticalTo($generator)
				->object($generatorDepedencies = $generator->getDepedencies())->isIdenticalTo($dependencies['mageekguy\atoum\asserter\generator'])
				->boolean(isset($generatorDepedencies['locale']))->isTrue()
			->if($dependencies = new atoum\dependencies())
			->and($dependencies['mageekguy\atoum\asserter\generator']['locale'] = $localeInjector = function() {})
			->then
				->object($generator->setDepedencies($dependencies))->isIdenticalTo($generator)
				->object($generatorDepedencies = $generator->getDepedencies())->isIdenticalTo($dependencies['mageekguy\atoum\asserter\generator'])
				->object($generatorDepedencies['locale'])->isIdenticalTo($localeInjector)
			->if($dependencies['mageekguy\atoum\asserter\generator']['locale'] = $otherLocaleInjector = function() {})
			->then
				->object($generatorDepedencies['locale'])->isIdenticalTo($otherLocaleInjector)
		;
	}

	public function testSetLocale()
	{
		$this
			->if($generator = new asserter\generator())
			->then
				->object($generator->setLocale($locale = new atoum\locale()))->isIdenticalTo($generator)
				->object($generator->getLocale())->isIdenticalTo($locale)
		;
	}

	public function testSetAlias()
	{
		$this
			->if($generator = new asserter\generator())
			->then
				->object($generator->setAlias($alias = uniqid(), $asserter = uniqid()))->isIdenticalTo($generator)
				->array($generator->getAliases())->isEqualTo(array($alias => $asserter))
				->object($generator->setAlias($otherAlias = uniqid(), $otherAsserter = uniqid()))->isIdenticalTo($generator)
				->array($generator->getAliases())->isEqualTo(array($alias => $asserter, $otherAlias => $otherAsserter))
		;
	}

	public function testResetAliases()
	{
		$this
			->if($generator = new asserter\generator())
			->and($generator->setAlias(uniqid(), uniqid()))
			->then
				->array($generator->getAliases())->isNotEmpty()
				->object($generator->resetAliases())->isIdenticalTo($generator)
				->array($generator->getAliases())->isEmpty()
		;
	}
}

?>
