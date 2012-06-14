<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\php\path;

use
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\test,
	mageekguy\atoum\tests\units,
	mageekguy\atoum\report\fields\runner\php\path\cli as field,
	mock\mageekguy\atoum as mock
;

require_once __DIR__ . '/../../../../../../runner.php';

class cli extends test
{
	public function testClass()
	{
		$this->testedClass->isSubclassOf('mageekguy\atoum\report\fields\runner\php\path');
	}

	public function test__construct()
	{
		$this
			->if($field = new field())
			->then
				->object($field->getPrompt())->isEqualTo(new prompt())
				->object($field->getTitleColorizer())->isEqualTo(new colorizer())
				->object($field->getPathColorizer())->isEqualTo(new colorizer())
				->object($field->getLocale())->isEqualTo(new locale())
			->if($fieldClass = $this->getTestedClassName())
			->and($dependencies = new dependencies())
			->and($dependencies[$fieldClass]['locale'] = $locale = new locale())
			->and($dependencies[$fieldClass]['prompt'] = $prompt = new prompt())
			->and($dependencies[$fieldClass]['colorizers\title'] = $titleColorizer = new colorizer())
			->and($dependencies[$fieldClass]['colorizers\path'] = $pathColorizer = new colorizer())
			->and($field = new field($dependencies))
			->then
				->object($field->getLocale())->isIdenticalTo($locale)
				->object($field->getPrompt())->isIdenticalTo($prompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getPathColorizer())->isIdenticalTo($pathColorizer)
		;
	}

	public function testSetPrompt()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setPrompt($prompt = new prompt()))->isIdenticalTo($field)
				->object($field->getPrompt())->isIdenticalTo($prompt)
			->if($dependencies = new dependencies())
			->and($dependencies[$this->getTestedClassName()]['prompt'] = new prompt())
			->and($field = new field($dependencies))
			->then
				->object($field->setPrompt($prompt))->isIdenticalTo($field)
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

	public function testSetPathColorizer()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setPathColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getPathColorizer())->isIdenticalTo($colorizer)
			->if($field = new field(null, null, new colorizer()))
			->then
				->object($field->setPathColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getPathColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testHandleEvent()
	{
		$this
			->if($field = new field())
			->and($score = new mock\score())
			->and($score->getMockController()->getPhpPath = $phpPath = uniqid())
			->then
				->boolean($field->handleEvent(runner::runStop, $runner = new runner()))->isFalse()
				->variable($field->getPath())->isNull()
			->if($runner->setScore($score))
				->boolean($field->handleEvent(runner::runStart, $runner))->isTrue()
				->string($field->getPath())->isEqualTo($phpPath)
		;
	}

	public function test__toString()
	{
		$this
			->if($score = new mock\score())
			->and($score->getMockController()->getPhpPath = $phpPath = uniqid())
			->and($defaultField = new field())
			->then
				->castToString($defaultField)->isEqualTo('PHP path: ' . PHP_EOL)
			->if($runner = new runner())
			->and($runner->setScore($score))
			->and($dependencies = new dependencies())
			->and($dependencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($defaultField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($defaultField)->isEqualTo('PHP path:' . ' ' . $phpPath . PHP_EOL)
			->if($fieldClass = $this->getTestedClassName())
			->and($dependencies = new dependencies())
			->and($dependencies[$fieldClass]['prompt'] = $prompt = new prompt())
			->and($dependencies[$fieldClass]['colorizers\title'] = $titleColorizer = new colorizer())
			->and($dependencies[$fieldClass]['colorizers\path'] = $pathColorizer = new colorizer())
			->if($customField = new field($dependencies))
			->then
				->castToString($customField)->isEqualTo(
					$prompt .
					sprintf(
						$locale->_('%1$s: %2$s'),
						$titleColorizer->colorize($locale->_('PHP path')),
						$pathColorizer->colorize('')
					) .
					PHP_EOL
				)
			->if($customField->handleEvent(runner::runStart, $runner))
			->then
				->castToString($customField)->isEqualTo(
					$prompt .
					sprintf(
						$locale->_('%1$s: %2$s'),
						$titleColorizer->colorize($locale->_('PHP path')),
						$pathColorizer->colorize($phpPath)
					) .
					PHP_EOL
				)
		;
	}
}

?>
