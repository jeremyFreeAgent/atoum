<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\outputs;

use
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\depedencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\test,
	mageekguy\atoum\tests\units,
	mageekguy\atoum\report\fields\runner\outputs\cli as field
;

require_once __DIR__ . '/../../../../../runner.php';

class cli extends test
{
	public function testClass()
	{
		$this->testedClass->isSubclassOf('mageekguy\atoum\report\fields\runner\outputs');
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
			->and($field = new field($titlePrompt = new prompt(), $titleColorizer = new colorizer(), $methodPrompt = new prompt(), $methodColorizer = new colorizer(), $outputPrompt = new prompt(), $outputColorizer = new colorizer(), $depedencies))
			->then
				->object($field->getTitlePrompt())->isIdenticalTo($titlePrompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getMethodPrompt())->isIdenticalTo($methodPrompt)
				->object($field->getMethodColorizer())->isIdenticalTo($methodColorizer)
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
				->object($field->setTitlePrompt($prompt = new prompt()))->isIdenticalTo($field)
				->object($field->getTitlePrompt())->isIdenticalTo($prompt)
			->if($field = new field(new prompt()))
			->then
				->object($field->setTitlePrompt($prompt = new prompt()))->isIdenticalTo($field)
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
				->object($field->setMethodPrompt($prompt = new prompt()))->isIdenticalTo($field)
				->object($field->getMethodPrompt())->isIdenticalTo($prompt)
			->if($field = new field(null, null, new prompt()))
			->then
				->object($field->setMethodPrompt($prompt = new prompt()))->isIdenticalTo($field)
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
				->object($field->setOutputPrompt($prompt = new prompt()))->isIdenticalTo($field)
				->object($field->getOutputPrompt())->isIdenticalTo($prompt)
			->if($field = new field(null, null, null, null, new prompt()))
			->then
				->object($field->setOutputPrompt($prompt = new prompt()))->isIdenticalTo($field)
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

	public function testHandleEvent()
	{
		$this
			->if($field = new field())
			->and($runner = new runner())
			->then
				->boolean($field->handleEvent(runner::runStart, $runner))->isFalse()
				->variable($field->getRunner())->isNull()
				->boolean($field->handleEvent(runner::runStop, $runner))->isTrue()
				->object($field->getRunner())->isIdenticalTo($runner)
		;
	}

	public function test__toString()
	{
		$this
			->if($score = new \mock\mageekguy\atoum\score())
			->and($score->getMockController()->getOutputs = array())
			->and($runner = new runner())
			->and($runner->setScore($score))
			->and($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($defaultField = new field())
			->and($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $outputPrompt = new prompt(uniqid()), $outputColorizer = new colorizer(uniqid(), uniqid()), $depedencies))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStart, $runner))
			->and($customField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->and($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($score->getMockController()->getOutputs = $fields = array(
						array(
							'class' => $class = uniqid(),
							'method' => $method = uniqid(),
							'value' => $value = uniqid()
						),
						array(
							'class' => $otherClass = uniqid(),
							'method' => $otherMethod = uniqid(),
							'value' => ($firstOtherValue = uniqid()) . PHP_EOL . ($secondOtherValue = uniqid())
						)
					)
				)
			->and($defaultField = new field())
			->and($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $outputPrompt = new prompt(uniqid()), $outputColorizer = new colorizer(uniqid(), uniqid()), $depedencies))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStart, $runner))
			->and($customField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->and($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEqualTo(sprintf('There are %d outputs:', sizeof($fields)) . PHP_EOL .
						'In ' . $class . '::' . $method . '():' . PHP_EOL .
						$value . PHP_EOL .
						'In ' . $otherClass . '::' . $otherMethod . '():' . PHP_EOL .
						$firstOtherValue . PHP_EOL .
						$secondOtherValue . PHP_EOL
					)
				->castToString($customField)->isEqualTo(
						$titlePrompt .
						sprintf(
							$locale->_('%s:'),
							$titleColorizer->colorize(sprintf($locale->__('There is %d output', 'There are %d outputs', sizeof($fields)), sizeof($fields)))
						) .
						PHP_EOL .
						$methodPrompt .
						sprintf(
							$locale->_('%s:'),
							$methodColorizer->colorize('In ' . $class . '::' . $method . '()')
						) .
						PHP_EOL .
						$outputPrompt .
						$outputColorizer->colorize($value) . PHP_EOL .
						$methodPrompt .
						sprintf(
							$locale->_('%s:'),
							$methodColorizer->colorize('In ' . $otherClass . '::' . $otherMethod . '()')
						) .
						PHP_EOL .
						$outputPrompt . $outputColorizer->colorize($firstOtherValue) . PHP_EOL .
						$outputPrompt . $outputColorizer->colorize($secondOtherValue) . PHP_EOL
					)
			->if($score->getMockController()->getOutputs = $fields = array(
						array(
							'class' => $class = uniqid(),
							'method' => $method = uniqid(),
							'value' => $value = uniqid()
						),
						array(
							'class' => $otherClass = uniqid(),
							'method' => $otherMethod = uniqid(),
							'value' => ($firstOtherValue = uniqid()) . PHP_EOL . ($secondOtherValue = uniqid())
						)
					)
				)
			->and($defaultField = new field())
			->and($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $outputPrompt = new prompt(uniqid()), $outputColorizer = new colorizer(uniqid(), uniqid()), $depedencies))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStart, $runner))
			->and($customField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->and($customField->handleEvent(runner::runStop, $runner))
				->castToString($defaultField)->isEqualTo(sprintf('There are %d outputs:', sizeof($fields)) . PHP_EOL .
						'In ' . $class . '::' . $method . '():' . PHP_EOL .
						$value . PHP_EOL .
						'In ' . $otherClass . '::' . $otherMethod . '():' . PHP_EOL .
						$firstOtherValue . PHP_EOL .
						$secondOtherValue . PHP_EOL
					)
			->then
				->castToString($customField)->isEqualTo(
						$titlePrompt .
						sprintf(
							$locale->_('%s:'),
							$titleColorizer->colorize(sprintf($locale->__('There is %d output', 'There are %d outputs', sizeof($fields)), sizeof($fields)))
						) .
						PHP_EOL .
						$methodPrompt .
						sprintf(
							$locale->_('%s:'),
							$methodColorizer->colorize('In ' . $class . '::' . $method . '()')
						) .
						PHP_EOL .
						$outputPrompt .
						$outputColorizer->colorize($value) . PHP_EOL .
						$methodPrompt .
						sprintf(
							$locale->_('%s:'),
							$methodColorizer->colorize('In ' . $otherClass . '::' . $otherMethod . '()')
						) .
						PHP_EOL .
						$outputPrompt . $outputColorizer->colorize($firstOtherValue) . PHP_EOL .
						$outputPrompt . $outputColorizer->colorize($secondOtherValue) . PHP_EOL
					)
		;
	}
}

?>
