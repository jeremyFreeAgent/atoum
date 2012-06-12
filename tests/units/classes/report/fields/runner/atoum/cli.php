<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\atoum;

use
	mageekguy\atoum\test,
	mageekguy\atoum\score,
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\depedencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\report\fields\runner\atoum\cli as field
;

require_once __DIR__ . '/../../../../../runner.php';

class cli extends test
{
	public function testClass()
	{
		$this->class($this->getTestedClassName())->isSubclassOf('mageekguy\atoum\report\field');
	}

	public function test__construct()
	{
		$this
			->if($field = new field())
			->then
				->object($field->getPrompt())->isEqualTo(new prompt())
				->object($field->getColorizer())->isEqualTo(new colorizer())
				->object($field->getLocale())->isEqualTo(new locale())
				->variable($field->getAuthor())->isNull()
				->variable($field->getPath())->isNull()
				->variable($field->getVersion())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStart))
			->if($fieldClass = $this->getTestedClassName())
			->and($depedencies = new depedencies())
			->and($depedencies[$fieldClass]['locale'] = $locale = new locale())
			->and($depedencies[$fieldClass]['prompt'] = $prompt = new prompt())
			->and($depedencies[$fieldClass]['colorizer'] = $colorizer = new colorizer())
			->and($field = new field($depedencies))
			->then
				->object($field->getLocale())->isIdenticalTo($locale)
				->object($field->getPrompt())->isIdenticalTo($prompt)
				->object($field->getColorizer())->isIdenticalTo($colorizer)
				->variable($field->getAuthor())->isNull()
				->variable($field->getPath())->isNull()
				->variable($field->getVersion())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStart))
		;
	}

	public function testSetDepedencies()
	{
		$this
			->if($field = new field())
			->then
				->object($field->setDepedencies($depedencies = new depedencies()))->isIdenticalTo($field)
				->boolean(isset($depedencies[$field]['locale']))->isTrue()
				->boolean(isset($depedencies[$field]['prompt']))->isTrue()
				->boolean(isset($depedencies[$field]['colorizer']))->isTrue()
		;
	}

	public function testSetPrompt()
	{
		$this
			->if($field = new field())
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
		;
	}

	public function testHandleEvent()
	{
		$this
			->if($score = new score())
			->and($score
				->setAtoumPath($atoumPath = uniqid())
				->setAtoumVersion($atoumVersion = uniqid())
			)
			->and($runner = new runner())
			->and($runner->setScore($score))
			->and($field = new field())
			->then
				->variable($field->getAuthor())->isNull()
				->variable($field->getPath())->isNull()
				->variable($field->getVersion())->isNull()
				->boolean($field->handleEvent(runner::runStart, $runner))->isTrue()
				->string($field->getAuthor())->isEqualTo(\mageekguy\atoum\author)
				->string($field->getPath())->isEqualTo($atoumPath)
				->string($field->getVersion())->isEqualTo($atoumVersion)
		;
	}

	public function test__toString()
	{
		$this
			->if($score = new score())
			->and($score
				->setAtoumPath($atoumPath = uniqid())
				->setAtoumVersion($atoumVersion = uniqid())
			)
			->and($runner = new runner())
			->and($runner->setScore($score))
			->and($field = new field())
			->and($field->handleEvent(runner::runStop, $runner))
			->then
				->castToString($field)->isEmpty()
			->if($field->handleEvent(runner::runStart, $runner))
			->then
				->castToString($field)->isEqualTo($field->getPrompt() . $field->getColorizer()->colorize(sprintf($field->getLocale()->_('atoum version %s by %s (%s)'), $atoumVersion, \mageekguy\atoum\author, $atoumPath)) . PHP_EOL)
			->if($fieldClass = $this->getTestedClassName())
			->and($depedencies = new depedencies())
			->and($depedencies[$fieldClass]['locale'] = $locale = new locale())
			->and($depedencies[$fieldClass]['prompt'] = $prompt = new prompt())
			->and($depedencies[$fieldClass]['colorizer'] = $colorizer = new colorizer())
			->and($field = new field($depedencies))
			->and($field->handleEvent(runner::runStop, $runner))
			->then
				->castToString($field)->isEmpty()
			->if($field->handleEvent(runner::runStart, $runner))
			->then
				->castToString($field)->isEqualTo($prompt . $colorizer->colorize(sprintf($locale->_('atoum version %s by %s (%s)'), $atoumVersion, \mageekguy\atoum\author, $atoumPath)) . PHP_EOL)
		;
	}
}

?>
