<?php

namespace mageekguy\atoum\report\fields\runner\tests;

use
	mageekguy\atoum\dependencies,
	mageekguy\atoum\observable,
	mageekguy\atoum\report,
	mageekguy\atoum\runner
;

abstract class coverage extends report\field
{
	protected $coverage = null;

	public function __construct(dependencies $dependencies = null)
	{
		parent::__construct(array(runner::runStop), $dependencies);
	}

	public function getCoverage()
	{
		return $this->coverage;
	}

	public function handleEvent($event, observable $observable)
	{
		if (parent::handleEvent($event, $observable) === false)
		{
			return false;
		}
		else
		{
			$this->coverage = $observable->getScore()->getCoverage();

			return true;
		}
	}
}

?>
