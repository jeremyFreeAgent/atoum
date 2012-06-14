<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\php\version;

use
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\test,
	mageekguy\atoum\tests\units,
	mageekguy\atoum\mock\mageekguy\atoum as mock,
	mageekguy\atoum\report\fields\runner\php\version\cli as field
;

require_once __DIR__ . '/../../../../../../runner.php';

class cli extends test
{
	public function testClass()
	{
		$this->testedClass->isSubclassOf('mageekguy\atoum\report\fields\runner\php\version');
	}

	public function test__construct()
	{
		$this
			->if($field = new field())
			->then
				->object($field->getTitlePrompt())->isEqualTo(new prompt())
				->object($field->getTitleColorizer())->isEqualTo(new colorizer())
				->object($field->getVersionPrompt())->isEqualTo(new prompt())
				->object($field->getVersionColorizer())->isEqualTo(new colorizer())
				->object($field->getLocale())->isEqualTo(new locale())
				->array($field->getEvents())->isEqualTo(array(runner::runStart))
			->if($dependencies = new dependencies())
			->and($dependencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->if($field = new field($titlePrompt = new prompt(), $titleColorizer = new colorizer(), $versionPrompt = new prompt(), $versionColorizer = new colorizer(), $dependencies))
			->then
				->object($field->getLocale())->isIdenticalTo($locale)
				->object($field->getTitlePrompt())->isIdenticalTo($titlePrompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getVersionPrompt())->isIdenticalTo($versionPrompt)
				->object($field->getVersionColorizer())->isIdenticalTo($versionColorizer)
				->array($field->getEvents())->isEqualTo(array(runner::runStart))
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

	public function testSetVersionPrompt()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setVersionPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getVersionPrompt())->isIdenticalTo($prompt)
			->if($field = new field(null, null, new prompt()))
			->then
				->object($field->setVersionPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getVersionPrompt())->isIdenticalTo($prompt)
		;
	}

	public function testSetVersionColorizer()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setVersionColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getVersionColorizer())->isIdenticalTo($colorizer)
			->if($field = new field(null, null, null, new colorizer()))
			->then
				->object($field->setVersionColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getVersionColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testHandleEvent()
	{
		$this
			->if($field = new field())
			->and($score = new \mock\mageekguy\atoum\score())
			->and($score->getMockController()->getPhpVersion = $phpVersion = uniqid())
			->and($runner = new runner())
			->and($runner->setScore($score))
			->then
				->boolean($field->handleEvent(runner::runStop, $runner))->isFalse()
				->variable($field->getVersion())->isNull()
				->boolean($field->handleEvent(runner::runStart, $runner))->isTrue()
				->string($field->getVersion())->isEqualTo($phpVersion)
		;
	}

	public function test__toString()
	{
		$this
			->if($score = new \mock\mageekguy\atoum\score())
			->and($score->getMockController()->getPhpVersion = $phpVersion = uniqid())
			->and($runner = new runner())
			->and($runner->setScore($score))
			->and($defaultField = new field())
			->and($defaultField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($defaultField)->isEqualTo(
						$defaultField->getLocale()->_('PHP version:') .
						PHP_EOL .
						$phpVersion .
						PHP_EOL
					)
			->if($dependencies = new dependencies())
			->and($dependencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $versionPrompt = new prompt(uniqid()), $versionColorizer = new colorizer(uniqid(), uniqid()), $dependencies))
			->and($customField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($customField)->isEqualTo(
					$titlePrompt .
					sprintf(
						$locale->_('%s:'),
						$titleColorizer->colorize($locale->_('PHP version'))
					) .
					PHP_EOL .
					$versionPrompt .
					$versionColorizer->colorize($phpVersion) .
					PHP_EOL
				)
			->if($score->getMockController()->getPhpVersion = ($phpVersionLine1 = uniqid()) . PHP_EOL . ($phpVersionLine2 = uniqid()))
			->and($defaultField = new field())
			->and($defaultField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($defaultField)->isEqualTo(
					'PHP version:' .
					PHP_EOL .
					$phpVersionLine1 .
					PHP_EOL .
					$phpVersionLine2 .
					PHP_EOL
				)
			->if($customField = new field($titlePrompt = new prompt(uniqid()), $titleColorizer = new colorizer(uniqid(), uniqid()), $versionPrompt = new prompt(uniqid()), $versionColorizer = new colorizer(uniqid(), uniqid()), $dependencies))
			->and($customField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($customField)->isEqualTo(
					$titlePrompt .
					sprintf(
						$locale->_('%s:'),
						$titleColorizer->colorize($locale->_('PHP version'))
					) .
					PHP_EOL .
					$versionPrompt .
					$versionColorizer->colorize($phpVersionLine1) .
					PHP_EOL .
					$versionPrompt .
					$versionColorizer->colorize($phpVersionLine2) .
					PHP_EOL
				)
		;
	}
}

?>
