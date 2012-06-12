<?php

namespace mageekguy\atoum\tests\units\report\fields;

use
	mageekguy\atoum,
	mageekguy\atoum\report
;

require_once __DIR__ . '/../../../runner.php';

class event extends atoum\test
{
	public function testHandleEvent()
	{
		$this
			->if($field = new \mock\mageekguy\atoum\report\fields\event())
			->then
				->boolean($field->handleEvent(uniqid(), new \mock\mageekguy\atoum\observable()))->isFalse()
				->variable($field->getEvent())->isNull()
				->variable($field->getObservable())->isNull()
			->if($field = new \mock\mageekguy\atoum\report\fields\event(array($event = uniqid())))
			->then
				->boolean($field->handleEvent(uniqid(), new \mock\mageekguy\atoum\observable()))->isFalse()
				->variable($field->getEvent())->isNull()
				->variable($field->getObservable())->isNull()
				->boolean($field->handleEvent($event, $observable = new \mock\mageekguy\atoum\observable()))->isTrue()
				->string($field->getEvent())->isEqualTo($event)
				->object($field->getObservable())->isIdenticalTo($observable)
		;
	}
}
