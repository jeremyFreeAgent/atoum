<?php

namespace mageekguy\atoum\tests\units\report\fields\test\memory;

use
	mageekguy\atoum\mock,
	mageekguy\atoum\locale,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\test,
	mageekguy\atoum\test\adapter,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\report\fields\test\memory\phing as field
;

require_once __DIR__ . '/../../../../../runner.php';

class phing extends test
{
	public function testClass()
	{
		$this->testedClass->isSubClassOf('mageekguy\atoum\report\fields\test\memory\cli');
	}

	public function test__construct()
	{
		$this
			->if($field = new field())
			->then
				->object($field->getPrompt())->isEqualTo(new prompt())
				->object($field->getTitleColorizer())->isEqualTo(new colorizer())
				->object($field->getMemoryColorizer())->isEqualTo(new colorizer())
				->object($field->getLocale())->isEqualTo(new locale())
				->variable($field->getValue())->isNull()
				->array($field->getEvents())->isEqualTo(array(test::runStop))
			->if($dependencies = new dependencies())
			->and($dependencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->if($field = new field($prompt = new prompt(), $titleColorizer = new colorizer(), $memoryColorizer = new colorizer(), $dependencies))
			->then
				->object($field->getPrompt())->isIdenticalTo($prompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getMemoryColorizer())->isIdenticalTo($memoryColorizer)
				->object($field->getLocale())->isIdenticalTo($locale)
				->variable($field->getValue())->isNull()
				->array($field->getEvents())->isEqualTo(array(test::runStop))
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

	public function testSetTitleColorizer()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setTitleColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getTitleColorizer())->isIdenticalTo($colorizer)
			->if($field = new field(null, new colorizer()))
			->then
				->object($field->setTitleColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getTitleColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testSetDurationColorizer()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setMemoryColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getMemoryColorizer())->isIdenticalTo($colorizer)
			->if($field = new field(null, null, new colorizer()))
			->then
				->object($field->setMemoryColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getMemoryColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testHandleEvent()
	{
		$this
			->if($field = new field())
			->and($score = new \mock\mageekguy\atoum\score())
			->and($score->getMockController()->getTotalMemoryUsage = $totalMemoryUsage = rand(0, PHP_INT_MAX))
			->and($adapter = new adapter())
			->and($adapter->class_exists = true)
			->and($testController = new mock\controller())
			->and($testController->getTestedClassName = uniqid())
			->and($test = new \mock\mageekguy\atoum\test(null, null, $adapter, null, null, $testController))
			->and($test->getMockController()->getScore = $score)
			->then
				->boolean($field->handleEvent(test::runStart, $test))->isFalse()
				->variable($field->getValue())->isNull()
				->boolean($field->handleEvent(test::runStop, $test))->isTrue()
				->integer($field->getValue())->isEqualTo($totalMemoryUsage)
		;
	}

	public function test__toString()
	{
		$this
			->if($score = new \mock\mageekguy\atoum\score())
			->and($score->getMockController()->getTotalMemoryUsage = $totalMemoryUsage = rand(0, PHP_INT_MAX))
			->and($adapter = new adapter())
			->and($adapter->class_exists = true)
			->and($testController = new mock\controller())
			->and($testController->getTestedClassName = uniqid())
			->and($test = new \mock\mageekguy\atoum\test(null, null, $adapter, null, null, $testController))
			->and($test->getMockController()->getScore = $score)
			->and($dependencies = new dependencies())
			->and($dependencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($defaultField = new field())
			->and($customField = new field($prompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $memoryColorizer = new colorizer(uniqid(), uniqid()), $dependencies))
			->then
				->castToString($defaultField)->isEqualTo($defaultField->getPrompt() . $defaultField->getLocale()->_('unknown'))
				->castToString($customField)->isEqualTo(
						$prompt .
						sprintf(
							$locale->_('%s'),
							$memoryColorizer->colorize($locale->_('unknown'))
						)
					)
			->if($defaultField->handleEvent(test::runStart, $test))
			->then
				->castToString($defaultField)->isEqualTo($defaultField->getPrompt() . $defaultField->getLocale()->_('unknown'))
			->if($customField->handleEvent(test::runStart, $test))
			->then
				->castToString($customField)->isEqualTo(
						$prompt .
						sprintf(
							$locale->_('%s'),
							$memoryColorizer->colorize($locale->_('unknown'))
						)
					)
			->if($defaultField->handleEvent(test::runStop, $test))
			->then
				->castToString($defaultField)->isEqualTo($defaultField->getPrompt() . sprintf($defaultField->getLocale()->_('%4.2f Mb'), $totalMemoryUsage / 1048576))
			->if($customField->handleEvent(test::runStop, $test))
			->then
				->castToString($customField)->isEqualTo(
						$prompt .
						sprintf(
							$locale->_('%s'),
							$memoryColorizer->colorize(sprintf($locale->_('%4.2f Mb'), $totalMemoryUsage / 1048576))
						)
					)
		;
	}
}

?>
