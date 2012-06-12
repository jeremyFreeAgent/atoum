<?php

namespace mageekguy\atoum\report\fields\runner;

use
	mageekguy\atoum\depedencies,
	mageekguy\atoum\runner,
	mageekguy\atoum\report,
	mageekguy\atoum\observable
;

abstract class duration extends report\field
{
	protected $value = null;

	public function __construct(depedencies $depedencies = null)
	{
		parent::__construct(array(runner::runStop), $depedencies);
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
			$this->value = $observable->getRunningDuration();

			return true;
		}
	}
}

?>
