<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\duration;

use
	mageekguy\atoum\test,
	mageekguy\atoum\runner,
	mageekguy\atoum\locale,
	mageekguy\atoum\depedencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\report\fields\runner\duration
;

require_once __DIR__ . '/../../../../../runner.php';

class cli extends test
{
	public function testClass()
	{
		$this->testedClass->isSubclassOf('mageekguy\atoum\report\fields\runner\duration');
	}

	public function test__construct()
	{
		$this
			->if($field = new duration\cli())
			->then
				->object($field->getPrompt())->isEqualTo(new prompt())
				->object($field->getTitleColorizer())->isEqualTo(new colorizer())
				->object($field->getDurationColorizer())->isEqualTo(new colorizer())
				->object($field->getLocale())->isEqualTo(new locale())
				->variable($field->getValue())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStop))
			->if($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale = new locale())
			->and($field = new duration\cli($prompt = new prompt(), $titleColorizer = new colorizer(), $durationColorizer = new colorizer(), $depedencies))
			->then
				->object($field->getPrompt())->isIdenticalTo($prompt)
				->object($field->getTitleColorizer())->isIdenticalTo($titleColorizer)
				->object($field->getDurationColorizer())->isIdenticalTo($durationColorizer)
				->object($field->getLocale())->isIdenticalTo($locale)
				->variable($field->getValue())->isNull()
				->array($field->getEvents())->isEqualTo(array(runner::runStop))
		;
	}

	public function testSetPrompt()
	{

		$this
			->if($field = new duration\cli())
			->then
				->object($field->setPrompt($prompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getPrompt())->isIdenticalTo($prompt)
			->if($field = new duration\cli(new prompt(uniqid())))
			->then
				->object($field->setPrompt($otherPrompt = new prompt(uniqid())))->isIdenticalTo($field)
				->object($field->getPrompt())->isIdenticalTo($otherPrompt)
		;
	}

	public function testSetTitleColorizer()
	{
		$this
			->if($field = new duration\cli())
			->then
				->object($field->setTitleColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getTitleColorizer())->isIdenticalTo($colorizer)
			->if($field = new duration\cli(null, new colorizer()))
			->then
				->object($field->setTitleColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getTitleColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testSetDurationColorizer()
	{
		$this
			->if($field = new duration\cli())
			->then
				->object($field->setDurationColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getDurationColorizer())->isIdenticalTo($colorizer)
			->if($field = new duration\cli(null, null, new colorizer()))
			->then
				->object($field->setDurationColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getDurationColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testHandleEvent()
	{
		$this
			->if($field = new duration\cli())
			->then
				->boolean($field->handleEvent(runner::runStart, new runner()))->isFalse()
				->variable($field->getValue())->isNull()
			->if($runner = new \mock\mageekguy\atoum\runner())
			->and($runner->getMockController()->getRunningDuration = $runningDuration = rand(0, PHP_INT_MAX))
			->then
				->boolean($field->handleEvent(runner::runStop, $runner))->isTrue()
				->integer($field->getValue())->isEqualTo($runningDuration)
		;
	}

	public function test__toString()
	{
		$this
			->if($runner = new \mock\mageekguy\atoum\runner())
			->and($runner->getMockController()->getRunningDuration = 1)
			->and($prompt = new \mock\mageekguy\atoum\cli\prompt())
			->and($prompt->getMockController()->__toString = $promptString = uniqid())
			->and($titleColorizer = new \mock\mageekguy\atoum\cli\colorizer())
			->and($titleColorizer->getMockController()->colorize = $colorizedTitle = uniqid())
			->and($durationColorizer = new \mock\mageekguy\atoum\cli\colorizer())
			->and($durationColorizer->getMockController()->colorize = $colorizedDuration = uniqid())
			->and($locale = new \mock\mageekguy\atoum\locale())
			->and($locale->getMockController()->_ = function($string) { return $string; })
			->and($depedencies = new depedencies())
			->and($depedencies[$this->getTestedClassName()]['locale'] = $locale)
			->and($field = new duration\cli($prompt, $titleColorizer, $durationColorizer, $depedencies))
			->then
				->castToString($field)->isEqualTo($promptString . $colorizedTitle . ': ' . $colorizedDuration . '.' . PHP_EOL)
				->mock($locale)
					->call('_')->withArguments('Running duration')->once()
					->call('_')->withArguments('unknown')->once()
					->call('_')->withArguments('%1$s: %2$s.')->once()
				->mock($titleColorizer)
					->call('colorize')->withArguments('Running duration')->once()
				->mock($durationColorizer)
					->call('colorize')->withArguments('unknown')->once()
			->if($titleColorizer->getMockController()->resetCalls())
			->and($durationColorizer->getMockController()->resetCalls())
			->and($locale->getMockController()->resetCalls())
			->and($field->handleEvent(runner::runStart, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $colorizedTitle . ': ' . $colorizedDuration . '.' . PHP_EOL)
				->mock($locale)
					->call('_')->withArguments('Running duration')->once()
					->call('_')->withArguments('unknown')->once()
					->call('_')->withArguments('%1$s: %2$s.')->once()
				->mock($titleColorizer)
					->call('colorize')->withArguments('Running duration')->once()
				->mock($durationColorizer)
					->call('colorize')->withArguments('unknown')->once()
			->if($titleColorizer->getMockController()->resetCalls())
			->and($durationColorizer->getMockController()->resetCalls())
			->and($locale->getMockController()->resetCalls())
			->and($field->handleEvent(runner::runStop, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $colorizedTitle . ': ' . $colorizedDuration . '.' . PHP_EOL)
				->mock($locale)
					->call('_')->withArguments('Running duration')->once()
					->call('__')->withArguments('%4.2f second', '%4.2f seconds', 1)->once()
					->call('_')->withArguments('%1$s: %2$s.')->once()
				->mock($titleColorizer)
					->call('colorize')->withArguments('Running duration')->once()
				->mock($durationColorizer)
					->call('colorize')->withArguments('1.00 second')->once()
			->if($titleColorizer->getMockController()->resetCalls())
			->and($durationColorizer->getMockController()->resetCalls())
			->and($locale->getMockController()->resetCalls())
			->and($runner->getMockController()->getRunningDuration = $runningDuration = rand(2, PHP_INT_MAX))
			->and($field = new duration\cli($prompt, $titleColorizer, $durationColorizer, $depedencies))
			->and($field->handleEvent(runner::runStart, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $colorizedTitle . ': ' . $colorizedDuration . '.' . PHP_EOL)
				->mock($locale)
					->call('_')->withArguments('Running duration')->once()
					->call('_')->withArguments('unknown')->once()
					->call('_')->withArguments('%1$s: %2$s.')->once()
				->mock($titleColorizer)
					->call('colorize')->withArguments('Running duration')->once()
				->mock($durationColorizer)
					->call('colorize')->withArguments('unknown')->once()
			->if($titleColorizer->getMockController()->resetCalls())
			->and($durationColorizer->getMockController()->resetCalls())
			->and($locale->getMockController()->resetCalls())
			->and($field->handleEvent(runner::runStop, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $colorizedTitle . ': ' . $colorizedDuration . '.' . PHP_EOL)
				->mock($locale)
					->call('_')->withArguments('Running duration')->once()
					->call('__')->withArguments('%4.2f second', '%4.2f seconds', $runningDuration)->once()
					->call('_')->withArguments('%1$s: %2$s.')->once()
				->mock($titleColorizer)
					->call('colorize')->withArguments('Running duration')->once()
				->mock($durationColorizer)
					->call('colorize')->withArguments(sprintf('%4.2f', $runningDuration) . ' seconds')->once()
		;
	}
}

?>
