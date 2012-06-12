<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\tests\duration;

use
	mageekguy\atoum\test,
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\depedencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\tests\units,
	mageekguy\atoum\report\fields\runner\tests\duration\cli as field,
	mock\mageekguy\atoum as mock
;

require_once __DIR__ . '/../../../../../../runner.php';

class cli extends test
{
	public function testClass()
	{
		$this->testedClass->isSubClassOf('mageekguy\atoum\report\fields\runner\tests\duration');
	}

	public function test__construct()
	{
		$this
			->if($field = new field())
			->then
				->object($field->getPrompt())->isEqualTo(new prompt())
				->object($field->getTitleColorizer())->isEqualTo(new colorizer())
				->object($field->getDurationColorizer())->isEqualTo(new colorizer())
				->object($field->getLocale())->isEqualTo(new locale())
				->variable($field->getValue())->isNull()
				->variable($field->getTestNumber())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStop))
			->if($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->if($field = new field($prompt = new prompt(), $titleColorizer = new colorizer(), $durationColorizer = new colorizer(), $depedencies))
			->then
				->object($field->getPrompt())->isIdenticalTo($prompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getDurationColorizer())->isIdenticalTo($durationColorizer)
				->object($field->getLocale())->isIdenticalTo($locale)
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
				->object($field->setDurationColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getDurationColorizer())->isIdenticalTo($colorizer)
			->if($field = new field(null, null, new colorizer()))
			->then
				->object($field->setDurationColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getDurationColorizer())->isIdenticalTo($colorizer)
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
			->then
				->boolean($field->handleEvent(runner::runStart, new runner()))->isFalse()
				->variable($field->getValue())->isNull()
				->variable($field->getTestNumber())->isNull()
			->if($score = new mock\score())
			->and($score->getMockController()->getTotalDuration = $totalDuration = (float) rand(1, PHP_INT_MAX))
			->and($runner = new mock\runner())
			->and($runner->setScore($score))
			->and($runner->getMockController()->getTestNumber = $testsNumber = rand(1, PHP_INT_MAX))
			->then
				->boolean($field->handleEvent(runner::runStop, $runner))->isTrue()
				->float($field->getValue())->isEqualTo($totalDuration)
				->integer($field->getTestNumber())->isEqualTo($testsNumber)
		;
	}

	public function test__toString()
	{
		$this
			->if($score = new mock\score())
			->and($score->getMockController()->getTotalDuration = $totalDuration = (rand(1, 100) / 1000))
			->and($runner = new mock\runner())
			->and($runner->setScore($score))
			->and($runner->getMockController()->getTestNumber = $testNumber = 1)
			->and($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($defaultField = new field())
			->and($customField = new field($prompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $durationColorizer = new colorizer(uniqid(), uniqid()), $depedencies))
			->then
				->castToString($defaultField)->isEqualTo($defaultField->getPrompt() . $defaultField->getLocale()->_('Total test duration: unknown.') . PHP_EOL)
				->castToString($customField)->isEqualTo($prompt . sprintf('%s: %s.', $titleColorizer->colorize($locale->_('Total test duration')), $durationColorizer->colorize($locale->_('unknown'))) . PHP_EOL)
			->if($defaultField->handleEvent(runner::runStart, new runner()))
			->and($customField->handleEvent(runner::runStart, new runner()))
			->then
				->castToString($defaultField)->isEqualTo($defaultField->getPrompt() . $defaultField->getLocale()->_('Total test duration: unknown.') . PHP_EOL)
				->castToString($customField)->isEqualTo($prompt . sprintf('%s: %s.', $titleColorizer->colorize($locale->_('Total test duration')), $durationColorizer->colorize($locale->_('unknown'))) . PHP_EOL)
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->and($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEqualTo(
						$defaultField->getPrompt() . sprintf($defaultField->getLocale()->__('Total test duration: %s.', 'Total tests duration: %s.', $testNumber), sprintf($defaultField->getLocale()->__('%4.2f second', '%4.2f seconds', $totalDuration), $totalDuration)) . PHP_EOL
					)
				->castToString($customField)->isEqualTo($prompt .
						sprintf(
							'%s: %s.',
							$titleColorizer->colorize($locale->__('Total test duration', 'Total tests duration', $testNumber)),
							$durationColorizer->colorize(sprintf($locale->__('%4.2f second', '%4.2f seconds', $totalDuration), $totalDuration))
						) .
						PHP_EOL
					)
			->if($runner->getMockController()->getTestNumber = $testNumber = rand(2, PHP_INT_MAX))
			->and($defaultField = new field())
			->and($customField = new field($prompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $durationColorizer = new colorizer(uniqid(), uniqid()), $depedencies))
			->then
				->castToString($defaultField)->isEqualTo($defaultField->getPrompt() . $defaultField->getLocale()->_('Total test duration: unknown.') . PHP_EOL)
				->castToString($customField)->isEqualTo($prompt . sprintf('%s: %s.', $titleColorizer->colorize($locale->_('Total test duration')), $durationColorizer->colorize($locale->_('unknown'))) . PHP_EOL)
			->if($defaultField->handleEvent(runner::runStart, new runner()))
			->and($customField->handleEvent(runner::runStart, new runner()))
			->then
				->castToString($defaultField)->isEqualTo($defaultField->getPrompt() . $defaultField->getLocale()->_('Total test duration: unknown.') . PHP_EOL)
				->castToString($customField)->isEqualTo($prompt . sprintf('%s: %s.', $titleColorizer->colorize($locale->_('Total test duration')), $durationColorizer->colorize($locale->_('unknown'))) . PHP_EOL)
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->and($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEqualTo(
						$defaultField->getPrompt() . sprintf($defaultField->getLocale()->__('Total test duration: %s.', 'Total tests duration: %s.', $testNumber), sprintf($defaultField->getLocale()->__('%4.2f second', '%4.2f seconds', $totalDuration), $totalDuration)) . PHP_EOL
					)
				->castToString($customField)->isEqualTo($prompt .
						sprintf(
							'%s: %s.',
							$titleColorizer->colorize($locale->__('Total test duration', 'Total tests duration', $testNumber)),
							$durationColorizer->colorize(sprintf($locale->__('%4.2f second', '%4.2f seconds', $totalDuration), $totalDuration))
						) .
						PHP_EOL
					)
		;
	}
}

?>
