<?php

namespace mageekguy\atoum\tests\units\report;

use
	mageekguy\atoum,
	mageekguy\atoum\report
;

require_once __DIR__ . '/../../runner.php';

class field extends atoum\test
{
	public function test__construct()
	{
		$this
			->if($field = new \mock\mageekguy\atoum\report\field())
			->then
				->array($field->getEvents())->isEmpty()
				->object($field->getDepedencies())->isInstanceOf('mageekguy\atoum\depedencies')
				->object($field->getLocale())->isInstanceOf('mageekguy\atoum\locale')
				->boolean($field->canHandleEvent(uniqid()))->isFalse()
			->if($depedencies = new atoum\depedencies())
			->and($depedencies['mock\mageekguy\atoum\report\field']['locale'] = $locale = new atoum\locale())
			->and($field = new \mock\mageekguy\atoum\report\field($events = array(uniqid(), uniqid(), uniqid()), $depedencies))
			->then
				->array($field->getEvents())->isEqualTo($events)
				->object($field->getDepedencies())->isIdenticalTo($depedencies[$field])
				->object($field->getLocale())->isIdenticalTo($locale)
				->boolean($field->canHandleEvent(uniqid()))->isFalse()
		;
	}

	public function testSetDepedencies()
	{
		$this
			->if($field = new \mock\mageekguy\atoum\report\field())
			->and($fieldClass = get_class($field))
			->then
				->object($field->setDepedencies($depedencies = new atoum\depedencies()))->isIdenticalTo($field)
				->object($fieldDepedencies = $field->getDepedencies())->isIdenticalTo($depedencies[$fieldClass])
				->boolean($fieldDepedencies->isLocked())->isFalse()
				->boolean(isset($fieldDepedencies['locale']))->isTrue()
			->if($depedencies = new atoum\depedencies())
			->and($depedencies[$fieldClass]['locale'] = $localeInjector = function() {})
			->then
				->object($field->setDepedencies($depedencies))->isIdenticalTo($field)
				->object($fieldDepedencies = $field->getDepedencies())->isIdenticalTo($depedencies[$fieldClass])
				->boolean($fieldDepedencies->isLocked())->isFalse()
				->object($fieldDepedencies['locale'])->isIdenticalTo($localeInjector)
		;
	}

	public function testCanHandleEvent()
	{
		$this
			->if($field = new \mock\mageekguy\atoum\report\field())
			->then
				->boolean($field->canHandleEvent(uniqid()))->isFalse()
			->if($field = new \mock\mageekguy\atoum\report\field(array($event = uniqid())))
			->then
				->boolean($field->canHandleEvent(uniqid()))->isFalse()
				->boolean($field->canHandleEvent($event))->isTrue()
		;
	}

	public function testHandleEvent()
	{
		$this
			->if($field = new \mock\mageekguy\atoum\report\field())
			->and($observable = new \mock\mageekguy\atoum\observable())
			->then
				->boolean($field->handleEvent(uniqid(), $observable))->isFalse()
			->if($field = new \mock\mageekguy\atoum\report\field(array($event = uniqid())))
			->then
				->boolean($field->handleEvent(uniqid(), $observable))->isFalse()
				->boolean($field->handleEvent($event, $observable))->isTrue()
		;
	}
}

?>
