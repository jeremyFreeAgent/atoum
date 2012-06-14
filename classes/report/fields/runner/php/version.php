<?php

namespace mageekguy\atoum\report\fields\runner\php;

use
	mageekguy\atoum\dependencies,
	mageekguy\atoum\observable,
	mageekguy\atoum\report,
	mageekguy\atoum\runner
;

abstract class version extends report\field
{
	protected $version = null;

	public function __construct(dependencies $dependencies = null)
	{
		parent::__construct(array(runner::runStart), $dependencies);
	}

	public function getVersion()
	{
		return $this->version;
	}

	public function handleEvent($event, observable $observable)
	{
		if (parent::handleEvent($event, $observable) === false)
		{
			return false;
		}
		else
		{
			$this->version = $observable->getScore()->getPhpVersion();

			return true;
		}
	}
}

?>
