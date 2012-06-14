<?php

namespace mageekguy\atoum\report\fields\runner;

use
	mageekguy\atoum\dependencies,
	mageekguy\atoum\runner,
	mageekguy\atoum\report,
	mageekguy\atoum\observable
;

abstract class duration extends report\field
{
	protected $value = null;

	public function __construct(dependencies $dependencies = null)
	{
		parent::__construct(array(runner::runStop), $dependencies);
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
