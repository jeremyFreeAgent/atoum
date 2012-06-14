<?php

namespace mageekguy\atoum\tests\units\test;

require_once __DIR__ . '/../../runner.php';

use
	mageekguy\atoum
;

class engine extends atoum\test
{
	public function testClass()
	{
		$this->testedClass->isAbstract();
	}

	public function test__construct()
	{
		$this
			->if($engine = new \mock\mageekguy\atoum\test\engine())
			->then
				->object($engine->getDepedencies())->isInstanceOf('mageekguy\atoum\dependencies')
			->if($engine = new \mock\mageekguy\atoum\test\engine($dependencies = new atoum\dependencies()))
			->then
				->object($engine->getDepedencies())->isIdenticalTo($dependencies[$engine])
		;
	}

	public function testSetDepedencies()
	{
		$this
			->if($engine = new \mock\mageekguy\atoum\test\engine())
			->then
				->object($engine->setDepedencies($dependencies = new atoum\dependencies()))->isIdenticalTo($engine)
				->object($engine->getDepedencies())->isIdenticalTo($dependencies[$engine])
		;
	}
}

?>
