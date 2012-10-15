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
			$resolver = $resolver ?: new dependencies\resolver();

			if (isset($resolver['iterators\recursives\directory']) === true)
			{
				$resolver['iterators\recursives\directory']['directory'] = (string) $mixed;
			}

			parent::__construct($resolver['@iterators\recursives\directory'] ?: new \recursiveDirectoryIterator((string) $mixed));
		}
	}

	public function accept()
	{
		return (substr(basename((string) $this->getInnerIterator()->current()), 0, 1) != '.');
	}
}
