<?php

namespace mageekguy\atoum\report\fields\test;

use
	mageekguy\atoum\depedencies,
	mageekguy\atoum\observable,
	mageekguy\atoum\test,
	mageekguy\atoum\report
;

abstract class duration extends report\field
{
	protected $value = null;

	public function __construct(depedencies $depedencies = null)
	{
		parent::__construct(array(test::runStop), $depedencies);
	}

	public function getValue()
	{
		return $this->value;
	}

	public function handleEvent($event, observable $observable)
	{
		if (parent::handleEvent($event, $observable) === false)
		{
			return false;
		}
		else
		{
			$this->value = $observable->getScore()->getTotalDuration();

			return true;
		}
	}
}

?>
