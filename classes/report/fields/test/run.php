<?php

namespace mageekguy\atoum\report\fields\test;

use
	mageekguy\atoum\depedencies,
	mageekguy\atoum\observable,
	mageekguy\atoum\test,
	mageekguy\atoum\report
;

abstract class run extends report\field
{
	protected $testClass = null;

	public function __construct(depedencies $depedencies = null)
	{
		parent::__construct(array(test::runStart), $depedencies);
	}

	public function getTestClass()
	{
		return $this->testClass;
	}

	public function handleEvent($event, observable $observable)
	{
		if (parent::handleEvent($event, $observable) === false)
		{
			return false;
		}
		else
		{
			$this->testClass = $observable->getClass();

			return true;
		}
	}
}

?>
