<?php

namespace mageekguy\atoum\tests\units\report\fields\test\duration;

use
	mageekguy\atoum\mock,
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\depedencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\test,
	mageekguy\atoum\test\adapter,
	mageekguy\atoum\report\fields\test\duration\phing as field
;

require_once __DIR__ . '/../../../../../runner.php';

class phing extends test
{
	public function testClass()
	{
	  $this->testedClass->isSubClassOf('mageekguy\atoum\report\fields\test\duration\cli');
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
				->array($field->getEvents())->isEqualTo(array(test::runStop))
			->if($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->if($field = new field($prompt = new prompt(), $titleColorizer = new colorizer(), $durationColorizer = new colorizer(), $depedencies))
			->then
				->object($field->getPrompt())->isIdenticalTo($prompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getDurationColorizer())->isIdenticalTo($durationColorizer)
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
			->and($score = new \mock\mageekguy\atoum\score())
			->and($score->getMockController()->getTotalDuration = function() use (& $runningDuration) { return $runningDuration = rand(0, PHP_INT_MAX); })
			->and($adapter = new adapter())
			->and($adapter->class_exists = true)
			->and($testController = new mock\controller())
			->and($testController->getTestedClassName = uniqid())
			->and($testController->getScore = $score)
			->and($test = new \mock\mageekguy\atoum\test(null, null, $adapter, null, null, $testController))
			->then
				->boolean($field->handleEvent(runner::runStop, $test))->isFalse()
				->variable($field->getValue())->isNull()
				->boolean($field->handleEvent(test::runStop, $test))->isTrue()
				->integer($field->getValue())->isEqualTo($runningDuration)
		;
	}

	public function test__toString()
	{
		$this
			->if($adapter = new adapter())
			->and($adapter->class_exists = true)
			->and($score = new \mock\mageekguy\atoum\score())
			->and($score->getMockController()->getTotalDuration = $runningDuration = rand(1, 1000) / 1000)
			->and($testController = new mock\controller())
			->and($testController->getTestedClassName = uniqid())
			->and($testController->getScore = $score)
			->and($test = new \mock\mageekguy\atoum\test(null, null, $adapter, null, null, $testController))
			->and($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($defaultField = new field())
			->and($customField = new field($prompt = new prompt(), $titleColorizer = new colorizer(), $durationColorizer = new colorizer(), $depedencies))
			->then
				->castToString($defaultField)->isEqualTo('unknown')
				->castToString($customField)->isEqualTo(
						$prompt .
						sprintf(
							'%s',
							$locale->_('unknown')
						)
					)
			->if($defaultField->handleEvent(runner::runStop, $test))
			->then
				->castToString($defaultField)->isEqualTo('unknown')
			->if($customField->handleEvent(runner::runStop, $test))
			->then
				->castToString($customField)->isEqualTo(
						$prompt .
						sprintf(
							'%s',
							$locale->_('unknown')
						)
					)
			->if($defaultField->handleEvent(test::runStop, $test))
			->then
				->castToString($defaultField)->isEqualTo(sprintf('%4.2f s', $runningDuration))
			->if($customField->handleEvent(test::runStop, $test))
			->then
				->castToString($customField)->isEqualTo(
						$prompt .
						sprintf(
							'%s',
							$durationColorizer->colorize(sprintf($locale->__('%4.2f s', '%4.2f s', $runningDuration), $runningDuration))
						)
					)
			->if($score->getMockController()->getTotalDuration = $runningDuration = rand(2, PHP_INT_MAX))
			->and($defaultField = new field())
			->and($customField = new field($prompt = new prompt(), $titleColorizer = new colorizer(), $durationColorizer = new colorizer(), $depedencies))
			->then
				->castToString($defaultField)->isEqualTo('unknown')
				->castToString($customField)->isEqualTo(
						$prompt .
						sprintf(
							'%s',
							$locale->_('unknown')
						)
					)
			->if($defaultField->handleEvent(runner::runStop, $test))
			->then
				->castToString($defaultField)->isEqualTo('unknown')
			->if($customField->handleEvent(runner::runStop, $test))
			->then
				->castToString($customField)->isEqualTo(
						$prompt .
						sprintf(
							'%s',
							$locale->_('unknown')
						)
					)
			->if($defaultField->handleEvent(test::runStop, $test))
			->then
				->castToString($defaultField)->isEqualTo(sprintf('%4.2f s', $runningDuration))
			->if($customField->handleEvent(test::runStop, $test))
			->then
				->castToString($customField)->isEqualTo(
						$prompt .
						sprintf(
							'%s',
							$durationColorizer->colorize(sprintf($locale->__('%4.2f s', '%4.2f s', $runningDuration), $runningDuration))
						)
					)
		;
	}
}

?>
