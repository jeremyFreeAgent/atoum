<?php

namespace mageekguy\atoum\tests\units\script;

use
	mageekguy\atoum,
	mageekguy\atoum\dependencies,
	mock\mageekguy\atoum\script\cli as testedClass
;

require_once __DIR__ . '/../../runner.php';

class cli extends atoum\test
{
	public function testClass()
	{
		$this->testedClass
			->isAbstract()
			->extends('mageekguy\atoum\script')
		;
	}

	public function test__construct()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->and($adapter->php_sapi_name = uniqid())
			->and($resolver = new dependencies\resolver())
			->and($resolver['adapter'] = $adapter)
			->then
				->exception(function() use ($resolver, & $name) {
						new testedClass($name = uniqid(), $resolver);
					}
				)
					->isInstanceOf('mageekguy\atoum\exceptions\logic')
					->hasMessage('\'' . $name . '\' must be used in CLI only')
		;
	}
}
