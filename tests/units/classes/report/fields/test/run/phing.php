<?php

namespace mageekguy\atoum\tests\units\report\fields\test\run;

use
	mageekguy\atoum\mock,
	mageekguy\atoum\locale,
	mageekguy\atoum\depedencies,
	mageekguy\atoum\test,
	mageekguy\atoum\test\adapter,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\report\fields\test\run\phing as field
;

require_once __DIR__ . '/../../../../../runner.php';

class phing extends test
{
	public function testClass()
	{
		$this->testedClass->isSubClassOf('mageekguy\atoum\report\fields\test\run\cli');
	}

	public function test__construct()
	{
		$this
			->if($field = new field())
			->then
				->object($field->getLocale())->isEqualTo(new locale())
				->variable($field->getTestClass())->isNull()
				->object($field->getLocale())->isEqualTo(new locale())
			->if($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($field = new field($prompt = new prompt(), $colorizer = new colorizer(), $depedencies))
			->then
				->object($field->getPrompt())->isIdenticalTo($prompt)
				->object($field->getColorizer())->isIdenticalTo($colorizer)
				->object($field->getLocale())->isIdenticalTo($locale)
		;
	}

	public function testSetPrompt()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setPrompt($prompt = new prompt()))->isIdenticalTo($field)
				->object($field->getPrompt())->isIdenticalTo($prompt)
			->if($field = new field(new prompt()))
			->then
				->object($field->setPrompt($prompt = new prompt()))->isIdenticalTo($field)
				->object($field->getPrompt())->isIdenticalTo($prompt)
		;
	}

	public function testSetColorizer()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getColorizer())->isIdenticalTo($colorizer)
			->if($field = new field(null, new colorizer()))
			->then
				->object($field->setColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testHandleEvent()
	{
		$this
			->if($field = new field())
			->and($adapter = new adapter())
			->and($adapter->class_exists = true)
			->and($testController = new mock\controller())
			->and($testController->getTestedClassName = uniqid())
			->and($test = new \mock\mageekguy\atoum\test(null, null, $adapter, null, null, $testController))
			->then
				->boolean($field->handleEvent(test::runStop, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::beforeSetUp, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::afterSetUp, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::beforeTestMethod, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::fail, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::error, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::exception, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::success, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::afterTestMethod, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::beforeTearDown, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::afterTearDown, $test))->isFalse()
				->variable($field->getTestClass())->isNull()
				->boolean($field->handleEvent(test::runStart, $test))->isTrue()
				->string($field->getTestClass())->isEqualTo($test->getClass())
		;
	}

	public function test__toString()
	{
		$this
			->if($adapter = new adapter())
			->and($adapter->class_exists = true)
			->and($testController = new mock\controller())
			->and($testController->getTestedClassName = uniqid())
			->and($test = new \mock\mageekguy\atoum\test(null, null, $adapter, null, null, $testController))
			->and($defaultField = new field())
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::runStop, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::beforeSetUp, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::afterSetUp, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::beforeTestMethod, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::fail, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::error, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::exception, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::success, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::afterTestMethod, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::beforeTearDown, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::afterTearDown, $test))
			->then
				->castToString($defaultField)->isEqualTo('There is currently no test running.')
			->if($defaultField->handleEvent(test::runStart, $test))
			->then
				->castToString($defaultField)->isEqualTo(sprintf('%s : ', $test->getClass()))
			->if($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($customField = new field($prompt = new prompt(uniqid()), $colorizer = new colorizer(uniqid(), uniqid()), $depedencies))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::runStop, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::beforeSetUp, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::afterSetUp, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::beforeTestMethod, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::fail, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::error, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::exception, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::success, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::afterTestMethod, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::beforeTearDown, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::afterTearDown, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . $colorizer->colorize($locale->_('There is currently no test running.')))
			->if($customField->handleEvent(test::runStart, $test))
			->then
				->castToString($customField)->isEqualTo($prompt . sprintf($locale->_('%s : '), $colorizer->colorize($test->getClass())))
		;
	}
}

?>
