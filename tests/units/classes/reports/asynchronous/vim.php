<?php

namespace mageekguy\atoum\tests\units\reports\asynchronous;

use
	mageekguy\atoum,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\reports\asynchronous
;

require_once __DIR__ . '/../../../runner.php';

class vim extends atoum\test
{
	public function testClass()
	{
		$this->testedClass->extends('mageekguy\atoum\reports\asynchronous');
	}

	public function test__construct()
	{
		$this
			->if($report = new asynchronous\vim())
			->then
				->object($report->getLocale())->isInstanceOf('mageekguy\atoum\locale')
				->object($report->getAdapter())->isInstanceOf('mageekguy\atoum\adapter')
			->if($resolver = new dependencies\resolver())
			->and($resolver['locale'] = $locale = new atoum\locale())
			->and($resolver['adapter'] = $adapter = new atoum\adapter())
			->and($report = new asynchronous\vim($resolver))
			->then
				->object($report->getLocale())->isIdenticalTo($locale)
				->object($report->getAdapter())->isIdenticalTo($adapter)
		;
	}
}
