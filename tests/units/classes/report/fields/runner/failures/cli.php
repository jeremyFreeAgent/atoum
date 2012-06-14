<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\failures;

use
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\test,
	mageekguy\atoum\tests\units,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\report\fields\runner\failures\cli as field
;

require_once __DIR__ . '/../../../../../runner.php';

class cli extends test
{
	public function testClass()
	{
		$this->testedClass->isSubclassOf('mageekguy\atoum\report\fields\runner\failures');
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
				->object($field->getLocale())->isEqualTo(new locale())
				->variable($field->getRunner())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStop))
			->if($dependencies = new dependencies())
			->and($dependencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($field = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(), $dependencies))
			->then
				->object($field->getTitlePrompt())->isIdenticalTo($titlePrompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getMethodPrompt())->isIdenticalTo($methodPrompt)
				->object($field->getMethodColorizer())->isIdenticalTo($methodColorizer)
				->variable($field->getRunner())->isNull()
				->object($field->getLocale())->isIdenticalTo($locale)
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
			->if($field = new field(new prompt(uniqid())))
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
				->object($field->setTitleColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getTitleColorizer())->isIdenticalTo($colorizer)
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
				->object($field->setTitleColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getTitleColorizer())->isIdenticalTo($colorizer)
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
			->if($score = new \mock\mageekguy\atoum\score())
			->and($score->getMockController()->getErrors = array())
			->and($runner = new runner())
			->and($runner->setScore($score))
			->and($dependencies = new dependencies())
			->and($dependencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($defaultField = new field())
			->and($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $dependencies))
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
			->if($score->getMockController()->getFailAssertions = $fails = array(
						array(
							'case' => null,
							'dataSetKey' => null,
							'class' => $class = uniqid(),
							'method' => $method = uniqid(),
							'file' => $file = uniqid(),
							'line' => $line = uniqid(),
							'asserter' => $asserter = uniqid(),
							'fail' => $fail = uniqid()
						),
						array(
							'case' => null,
							'dataSetKey' => null,
							'class' => $otherClass = uniqid(),
							'method' => $otherMethod = uniqid(),
							'file' => $otherFile = uniqid(),
							'line' => $otherLine = uniqid(),
							'asserter' => $otherAsserter = uniqid(),
							'fail' => $otherFail = uniqid()
						)
					)
				)
			->and($defaultField = new field())
			->and($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $dependencies))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->and($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEqualTo(sprintf('There are %d failures:', sizeof($fails)) . PHP_EOL .
					$class . '::' . $method . '():' . PHP_EOL .
					sprintf('In file %s on line %d, %s failed: %s', $file, $line, $asserter, $fail) . PHP_EOL .
					$otherClass . '::' . $otherMethod . '():' . PHP_EOL .
					sprintf('In file %s on line %d, %s failed: %s', $otherFile, $otherLine, $otherAsserter, $otherFail) . PHP_EOL
				)
				->castToString($customField)->isEqualTo(
					$titlePrompt .
					sprintf(
						$locale->_('%s:'),
						$titleColorizer->colorize(sprintf($locale->__('There is %d failure', 'There are %d failures', sizeof($fails)), sizeof($fails)))
					) .
					PHP_EOL .
					$methodPrompt .
					sprintf(
						$locale->_('%s:'),
						$methodColorizer->colorize($class . '::' . $method . '()')
					) .
					PHP_EOL .
					sprintf($locale->_('In file %s on line %d, %s failed: %s'), $file, $line, $asserter, $fail) .
					PHP_EOL .
					$methodPrompt .
					sprintf(
						$locale->_('%s:'),
						$methodColorizer->colorize($otherClass . '::' . $otherMethod . '()')
					) .
					PHP_EOL .
					sprintf($locale->_('In file %s on line %d, %s failed: %s'), $otherFile, $otherLine, $otherAsserter, $otherFail) .
					PHP_EOL
				)
			->if($score->getMockController()->getFailAssertions = $fails = array(
						array(
							'case' => $case =  uniqid(),
							'dataSetKey' => null,
							'class' => $class = uniqid(),
							'method' => $method = uniqid(),
							'file' => $file = uniqid(),
							'line' => $line = uniqid(),
							'asserter' => $asserter = uniqid(),
							'fail' => $fail = uniqid()
						),
						array(
							'case' => $otherCase =  uniqid(),
							'dataSetKey' => null,
							'class' => $otherClass = uniqid(),
							'method' => $otherMethod = uniqid(),
							'file' => $otherFile = uniqid(),
							'line' => $otherLine = uniqid(),
							'asserter' => $otherAsserter = uniqid(),
							'fail' => $otherFail = uniqid()
						)
					)
				)
			->and($defaultField = new field())
			->and($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $dependencies))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->and($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEqualTo(sprintf('There are %d failures:', sizeof($fails)) . PHP_EOL .
					$class . '::' . $method . '():' . PHP_EOL .
					sprintf('In file %s on line %d in case \'%s\', %s failed: %s', $file, $line, $case, $asserter, $fail) . PHP_EOL .
					$otherClass . '::' . $otherMethod . '():' . PHP_EOL .
					sprintf('In file %s on line %d in case \'%s\', %s failed: %s', $otherFile, $otherLine, $otherCase, $otherAsserter, $otherFail) . PHP_EOL
				)
				->castToString($customField)->isEqualTo(
					$titlePrompt .
					sprintf(
						$locale->_('%s:'),
						$titleColorizer->colorize(sprintf($locale->__('There is %d failure', 'There are %d failures', sizeof($fails)), sizeof($fails)))
					) .
					PHP_EOL .
					$methodPrompt .
					sprintf(
						$locale->_('%s:'),
						$methodColorizer->colorize($class . '::' . $method . '()')
					) .
					PHP_EOL .
					sprintf($locale->_('In file %s on line %d in case \'%s\', %s failed: %s'), $file, $line, $case, $asserter, $fail) .
					PHP_EOL .
					$methodPrompt .
					sprintf(
						$locale->_('%s:'),
						$methodColorizer->colorize($otherClass . '::' . $otherMethod . '()')
					) .
					PHP_EOL .
					sprintf($locale->_('In file %s on line %d in case \'%s\', %s failed: %s'), $otherFile, $otherLine, $otherCase, $otherAsserter, $otherFail) .
					PHP_EOL
				)
			->if($score->getMockController()->getFailAssertions = $fails = array(
						array(
							'case' => $case =  uniqid(),
							'dataSetKey' => $dataSetKey = rand(1, PHP_INT_MAX),
							'class' => $class = uniqid(),
							'method' => $method = uniqid(),
							'file' => $file = uniqid(),
							'line' => $line = uniqid(),
							'asserter' => $asserter = uniqid(),
							'fail' => $fail = uniqid()
						),
						array(
							'case' => $otherCase =  uniqid(),
							'dataSetKey' => $otherDataSetKey = rand(1, PHP_INT_MAX),
							'class' => $otherClass = uniqid(),
							'method' => $otherMethod = uniqid(),
							'file' => $otherFile = uniqid(),
							'line' => $otherLine = uniqid(),
							'asserter' => $otherAsserter = uniqid(),
							'fail' => $otherFail = uniqid()
						)
					)
				)
			->and($defaultField = new field())
			->and($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $methodPrompt = new prompt(uniqid()), $methodColorizer = new colorizer(uniqid(), uniqid()), $dependencies))
			->then
				->castToString($defaultField)->isEmpty()
				->castToString($customField)->isEmpty()
			->if($defaultField->handleEvent(runner::runStop, $runner))
			->and($customField->handleEvent(runner::runStop, $runner))
			->then
				->castToString($defaultField)->isEqualTo(sprintf('There are %d failures:', sizeof($fails)) . PHP_EOL .
					$class . '::' . $method . '():' . PHP_EOL .
					sprintf('In file %s on line %d in case \'%s\', %s failed for data set #%s: %s', $file, $line, $case, $asserter, $dataSetKey, $fail) . PHP_EOL .
					$otherClass . '::' . $otherMethod . '():' . PHP_EOL .
					sprintf('In file %s on line %d in case \'%s\', %s failed for data set #%s: %s', $otherFile, $otherLine, $otherCase, $otherAsserter, $otherDataSetKey, $otherFail) . PHP_EOL
				)
				->castToString($customField)->isEqualTo(
					$titlePrompt .
					sprintf(
						$locale->_('%s:'),
						$titleColorizer->colorize(sprintf($locale->__('There is %d failure', 'There are %d failures', sizeof($fails)), sizeof($fails)))
					) .
					PHP_EOL .
					$methodPrompt .
					sprintf(
						$locale->_('%s:'),
						$methodColorizer->colorize($class . '::' . $method . '()')
					) .
					PHP_EOL .
					sprintf($locale->_('In file %s on line %d in case \'%s\', %s failed for data set #%s: %s'), $file, $line, $case, $asserter, $dataSetKey, $fail) .
					PHP_EOL .
					$methodPrompt .
					sprintf(
						$locale->_('%s:'),
						$methodColorizer->colorize($otherClass . '::' . $otherMethod . '()')
					) .
					PHP_EOL .
					sprintf($locale->_('In file %s on line %d in case \'%s\', %s failed for data set #%s: %s'), $otherFile, $otherLine, $otherCase, $otherAsserter, $otherDataSetKey, $otherFail) .
					PHP_EOL
				)
		;
	}
}

?>
