<?php

namespace mageekguy\atoum\script;

use
	mageekguy\atoum,
	mageekguy\atoum\exceptions,
	mageekguy\atoum\dependencies
;

abstract class cli extends atoum\script
{
	public function __construct($name, dependencies\resolver $resolver = null)
	{
		parent::__construct($name, $resolver);

		if ($this->adapter->php_sapi_name() !== 'cli')
		{
			throw new exceptions\logic('\'' . $this->getName() . '\' must be used in CLI only');
	 	}
	}
}
