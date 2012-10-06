<?php

namespace mageekguy\atoum\iterators\filters\recursives;

use
	mageekguy\atoum\dependencies
;

class dot extends \recursiveFilterIterator
{
	public function __construct($mixed, $resolver = null)
	{
		if ($mixed instanceof \recursiveIterator)
		{
			parent::__construct($mixed);
		}
		else
		{
			if ($resolver !== null && isset($resolver['iterator']) === true)
			{
				$resolver['iterator']['directory'] = (string) $mixed;
			}

			parent::__construct($resolver['@iterator'] ?: new \recursiveDirectoryIterator((string) $mixed));
		}
	}

	public function accept()
	{
		return (substr(basename((string) $this->getInnerIterator()->current()), 0, 1) != '.');
	}
}
