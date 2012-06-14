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
				->object($field->getDepedencies())->isInstanceOf('mageekguy\atoum\dependencies')
				->object($field->getLocale())->isInstanceOf('mageekguy\atoum\locale')
				->boolean($field->canHandleEvent(uniqid()))->isFalse()
			->if($dependencies = new atoum\dependencies())
			->and($dependencies['mock\mageekguy\atoum\report\field']['locale'] = $locale = new atoum\locale())
			->and($field = new \mock\mageekguy\atoum\report\field($events = array(uniqid(), uniqid(), uniqid()), $dependencies))
			->then
				->array($field->getEvents())->isEqualTo($events)
				->object($field->getDepedencies())->isIdenticalTo($dependencies[$field])
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
				->object($field->setDepedencies($dependencies = new atoum\dependencies()))->isIdenticalTo($field)
				->object($fieldDepedencies = $field->getDepedencies())->isIdenticalTo($dependencies[$fieldClass])
				->boolean($fieldDepedencies->isLocked())->isFalse()
				->boolean(isset($fieldDepedencies['locale']))->isTrue()
			->if($dependencies = new atoum\dependencies())
			->and($dependencies[$fieldClass]['locale'] = $localeInjector = function() {})
			->then
				->object($field->setDepedencies($dependencies))->isIdenticalTo($field)
				->object($fieldDepedencies = $field->getDepedencies())->isIdenticalTo($dependencies[$fieldClass])
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
