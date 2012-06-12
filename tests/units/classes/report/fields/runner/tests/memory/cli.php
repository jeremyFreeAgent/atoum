<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\tests\memory;

use
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\depedencies,
	mageekguy\atoum\test,
	mageekguy\atoum\tests\units,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\report\fields\runner\tests\memory\cli as field,
	mock\mageekguy\atoum as mock
;

require_once __DIR__ . '/../../../../../../runner.php';

class cli extends test
{
	public function testClass()
	{
		$this->testedClass->isSubClassOf('mageekguy\atoum\report\fields\runner\tests\memory');
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
				->variable($field->getTestNumber())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStop))
			->if($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($field = new field($prompt = new prompt(uniqid()), $titleColorizer = new colorizer(), $memoryColorizer = new colorizer(), $depedencies))
			->then
				->object($field->getLocale())->isIdenticalTo($locale)
				->object($field->getPrompt())->isIdenticalTo($prompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getMemoryColorizer())->isIdenticalTo($memoryColorizer)
				->variable($field->getValue())->isNull()
				->variable($field->getTestNumber())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStop))
		;
	}

	public function testSetPrompt()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getPrompt())->isIdenticalTo($prompt)
			->if($field = new field(new prompt()))
			->then
				->object($field->setPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
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

	public function testSetMemoryColorizer()
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

	public function testSetLocale()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setLocale($locale = new locale()))->isIdenticalTo($field)
				->object($field->getLocale())->isIdenticalTo($locale)
			->if($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = new locale())
			->and($field = new field(null, null, null, $depedencies))
			->then
				->object($field->setLocale($locale = new locale()))->isIdenticalTo($field)
				->object($field->getLocale())->isIdenticalTo($locale)
		;
	}

	public function testHandleEvent()
	{
		$this
			->if($field = new field())
			->and($score = new mock\score())
			->and($score->getMockController()->getTotalMemoryUsage = $totalMemoryUsage = rand(1, PHP_INT_MAX))
			->and($runner = new mock\runner())
			->and($runner->setScore($score))
			->and($runner->getMockController()->getTestNumber = $testNumber = rand(0, PHP_INT_MAX))
			->then
				->boolean($field->handleEvent(runner::runStart, new runner()))->isFalse()
				->variable($field->getValue())->isNull()
				->variable($field->getTestNumber())->isNull()
				->boolean($field->handleEvent(runner::runStop, $runner))->isTrue()
				->integer($field->getValue())->isEqualTo($totalMemoryUsage)
				->integer($field->getTestNumber())->isEqualTo($testNumber)
		;
	}

	public function test__toString()
	{
		$this
			->if($score = new mock\score())
			->and($score->getMockController()->getTotalMemoryUsage = function() use (& $totalMemoryUsage) { return $totalMemoryUsage = rand(1, PHP_INT_MAX); })
			->and($runner = new mock\runner())
			->and($runner->setScore($score))
			->and($runner->getMockController()->getTestNumber = $testNumber = rand(1, PHP_INT_MAX))
			->and($defaultField = new field())
			->then
				->castToString($defaultField)->isEqualTo(
						$defaultField->getPrompt() . $defaultField->getTitleColorizer()->colorize($defaultField->getLocale()->__('Total test memory usage', 'Total tests memory usage', 0)) . ': ' . $defaultField->getMemoryColorizer()->colorize($defaultField->getLocale()->_('unknown')) . '.' . PHP_EOL
					)
			->if($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($customField = new field($prompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $memoryColorizer = new colorizer(uniqid(), uniqid()), $depedencies))
			->then
				->castToString($customField)->isEqualTo(
						$prompt . $titleColorizer->colorize($locale->__('Total test memory usage', 'Total tests memory usage', 0)) . ': ' . $memoryColorizer->colorize($locale->_('unknown')) . '.' . PHP_EOL
					)
			->if($defaultField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($defaultField)->isEqualTo(
						$defaultField->getPrompt() . $defaultField->getTitleColorizer()->colorize($defaultField->getLocale()->__('Total test memory usage', 'Total tests memory usage', 0)) . ': ' . $defaultField->getMemoryColorizer()->colorize($defaultField->getLocale()->_('unknown')) . '.' . PHP_EOL
					)
			->if($customField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($customField)->isEqualTo(
						$prompt . $titleColorizer->colorize($locale->__('Total test memory usage', 'Total tests memory usage', 0)) . ': ' . $memoryColorizer->colorize($locale->_('unknown')) . '.' . PHP_EOL
					)
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEqualTo($defaultField->getPrompt() . $defaultField->getTitleColorizer()->colorize($defaultField->getLocale()->__('Total test memory usage', 'Total tests memory usage', $testNumber)) . ': ' . $defaultField->getMemoryColorizer()->colorize(sprintf($defaultField->getLocale()->_('%4.2f Mb'), $totalMemoryUsage / 1048576)) . '.' . PHP_EOL)
			->if($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($customField)->isEqualTo($prompt . $titleColorizer->colorize($locale->__('Total test memory usage', 'Total tests memory usage', $testNumber)) . ': ' . $memoryColorizer->colorize(sprintf($locale->_('%4.2f Mb'), $totalMemoryUsage / 1048576)) . '.' . PHP_EOL)
		;
	}
}

?>
