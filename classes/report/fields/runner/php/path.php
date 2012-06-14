<?php

namespace mageekguy\atoum\report\fields\runner\php;

use
	mageekguy\atoum\observable,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\report,
	mageekguy\atoum\runner
;

abstract class path extends report\field
{
	protected $path = null;

	public function __construct(dependencies $dependencies = null)
	{
		parent::__construct(array(runner::runStart), $dependencies);
	}

	public function getPath()
	{
		return $this->path;
	}

	public function handleEvent($event, observable $observable)
	{
		if (parent::handleEvent($event, $observable) === false)
		{
			return false;
		}
		else
		{
			$this->path = $observable->getScore()->getPhpPath();

			return true;
		}
	}
}

?>
