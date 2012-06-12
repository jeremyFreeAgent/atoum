<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\tests\uncompleted;

use
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\depedencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\test,
	mageekguy\atoum\tests\units,
	mageekguy\atoum\report\fields\runner\tests\uncompleted\cli as field,
	mock\mageekguy\atoum as mock
;

require_once __DIR__ . '/../../../../../../runner.php';

class cli extends test
{
	public function testClass()
	{
		$this->testedClass->isSubclassOf('mageekguy\atoum\report\fields\runner\tests\uncompleted');
	}

	public function test__construct()
	{
		$this
			->if($field = new field())
			->then
				->object($field->getTitlePrompt())->isEqualTo(new prompt())
				->object($field->getTitleColorizer())->isEqualTo(new colorizer())
				->object($field->getMethodPrompt())->isEqualTo(new prompt())
				->object($field->getMethodColorizer())->isEqualTo(new colorizer())
				->object($field->getOutputPrompt())->isEqualTo(new prompt())
				->object($field->getOutputColorizer())->isEqualTo(new colorizer())
				->object($field->getLocale())->isEqualTo(new locale())
				->variable($field->getRunner())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStop))
			->if($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->if($field = new field ($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(), $outputPrompt = new prompt(uniqid()), $outputColorizer = new colorizer(), $depedencies))
			->then
				->object($field->getTitlePrompt())->isIdenticalTo($titlePrompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getMethodPrompt())->isIdenticalTo($methodPrompt)
				->object($field->getMethodColorizer())->isIdenticalTo($methodColorizer)
				->object($field->getOutputPrompt())->isIdenticalTo($outputPrompt)
				->object($field->getOutputColorizer())->isIdenticalTo($outputColorizer)
				->object($field->getLocale())->isIdenticalTo($locale)
				->variable($field->getRunner())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStop))
		;
	}

	public function testSetTitlePrompt()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setTitlePrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getTitlePrompt())->isIdenticalTo($prompt)
			->if($field = new field(new prompt()))
			->then
				->object($field->setTitlePrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getTitlePrompt())->isIdenticalTo($prompt)
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

	public function testSetMethodPrompt()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setMethodPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getMethodPrompt())->isIdenticalTo($prompt)
			->if($field = new field(null, null, new prompt()))
			->then
				->object($field->setMethodPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getMethodPrompt())->isIdenticalTo($prompt)
		;
	}

	public function testSetMethodColorizer()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setMethodColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getMethodColorizer())->isIdenticalTo($colorizer)
			->if($field = new field(null, null, null, new colorizer()))
			->then
				->object($field->setMethodColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getMethodColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testSetOutputPrompt()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setOutputPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getOutputPrompt())->isIdenticalTo($prompt)
			->if($field = new field(null, null, null, null, new prompt()))
			->then
				->object($field->setOutputPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getOutputPrompt())->isIdenticalTo($prompt)
		;
	}

	public function testSetOutputColorizer()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setOutputColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getOutputColorizer())->isIdenticalTo($colorizer)
			->if($field = new field(null, null, null, null, null, new colorizer()))
			->then
				->object($field->setOutputColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getOutputColorizer())->isIdenticalTo($colorizer)
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
			->and($field = new field(null, null, null, null, null, null, $depedencies))
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
				->variable($field->getRunner())->isNull()
				->boolean($field->handleEvent(runner::runStop, $runner = new runner()))->isTrue()
				->object($field->getRunner())->isIdenticalTo($runner)
		;
	}

	public function test__toString()
	{
		$this
			->if($score = new mock\score())
			->and($score->getMockController()->getUncompletedMethods = array())
			->and($runner = new runner())
			->and($runner->setScore($score))
			->and($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($defaultField = new field())
			->then
				->castToString($defaultField)->isEmpty()
			->if($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $outputPrompt = new prompt(uniqid()), $outputColorizer = new colorizer(uniqid(), uniqid()), $depedencies))
			->then
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($defaultField)->isEmpty()
			->if($customField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEmpty()
			->if($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($customField)->isEmpty()
			->if($score->getMockController()->getUncompletedMethods = $allUncompletedMethods = array(
						array(
							'class' => $class = uniqid(),
							'method' => $method = uniqid(),
							'exitCode' => $exitCode = rand(1, PHP_INT_MAX),
							'output' => $output = uniqid()
						),
						array(
							'class' => $otherClass = uniqid(),
							'method' => $otherMethod = uniqid(),
							'exitCode' => $otherExitCode = rand(1, PHP_INT_MAX),
							'output' => ($otherOutputLine1 = uniqid()) . PHP_EOL . ($otherOutputLine2 = uniqid())
						),
						array(
							'class' => $anotherClass = uniqid(),
							'method' => $anotherMethod = uniqid(),
							'exitCode' => $anotherExitCode = rand(1, PHP_INT_MAX),
							'output' => ''
						)
					)
				)
			->and($defaultField = new field())
			->then
				->castToString($defaultField)->isEmpty()
			->if($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $outputPrompt = new prompt(uniqid()), $outputColorizer = new colorizer(uniqid(), uniqid()), $depedencies))
			->then
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($defaultField)->isEmpty()
			->if($customField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEqualTo(sprintf('There are %d uncompleted methods:', sizeof($allUncompletedMethods)) . PHP_EOL .
						sprintf('%s::%s() with exit code %d:', $class, $method, $exitCode) . PHP_EOL .
						'output(' . strlen($output) . ') "' . $output . '"' . PHP_EOL .
						sprintf('%s::%s() with exit code %d:', $otherClass, $otherMethod, $otherExitCode) . PHP_EOL .
						'output(' . (strlen($otherOutputLine1) + strlen($otherOutputLine2) + 1) . ') "' . $otherOutputLine1 . PHP_EOL .
						$otherOutputLine2 . '"' . PHP_EOL .
						sprintf('%s::%s() with exit code %d:', $anotherClass, $anotherMethod, $anotherExitCode) . PHP_EOL .
						'output(0) ""' . PHP_EOL
					)
			->if($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($customField)->isEqualTo(
					$titlePrompt .
					sprintf(
						$locale->_('%s:'),
						$titleColorizer->colorize(sprintf($locale->__('There is %d uncompleted method', 'There are %d uncompleted methods', sizeof($allUncompletedMethods)), sizeof($allUncompletedMethods)))
					) .
					PHP_EOL .
					$methodPrompt .
					sprintf(
						$locale->_('%s:'),
						$methodColorizer->colorize(sprintf('%s::%s() with exit code %d', $class, $method, $exitCode))
					) .
					PHP_EOL .
					$outputPrompt .
					'output(' . strlen($output) . ') "' . $output . '"' .
					PHP_EOL .
					$methodPrompt .
					sprintf(
						$locale->_('%s:'),
						$methodColorizer->colorize(sprintf('%s::%s() with exit code %d', $otherClass, $otherMethod, $otherExitCode))
					) .
					PHP_EOL .
					$outputPrompt .
					'output(' . (strlen($otherOutputLine1) + strlen($otherOutputLine2) + 1) . ') "' . $otherOutputLine1 .
					PHP_EOL .
					$outputPrompt .
					$otherOutputLine2 . '"' .
					PHP_EOL .
					$methodPrompt .
					sprintf(
						$locale->_('%s:'),
						$methodColorizer->colorize(sprintf('%s::%s() with exit code %d', $anotherClass, $anotherMethod, $anotherExitCode))
					) .
					PHP_EOL .
					$outputPrompt .
					'output(0) ""' .
					PHP_EOL
				)
		;
	}
}

?>
