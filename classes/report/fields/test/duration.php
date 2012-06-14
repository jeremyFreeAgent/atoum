<?php

namespace mageekguy\atoum\report\fields\test;

use
	mageekguy\atoum\dependencies,
	mageekguy\atoum\observable,
	mageekguy\atoum\test,
	mageekguy\atoum\report
;

abstract class duration extends report\field
{
	protected $value = null;

	public function __construct(dependencies $dependencies = null)
	{
		parent::__construct(array(test::runStop), $dependencies);
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
