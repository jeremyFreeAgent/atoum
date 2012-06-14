<?php

namespace mageekguy\atoum\iterators\filters\recursives;

use
	mageekguy\atoum
;

class dot extends \recursiveFilterIterator
{
	protected $dependencies = null;

	public function __construct($mixed, atoum\dependencies $dependencies = null)
	{
		$this->setDepedencies($dependencies ?: new atoum\dependencies());

		if ($mixed instanceof \recursiveIterator === false)
		{
			$mixed = $this->dependencies['directory\iterator']((string) $mixed);
		}

		parent::__construct($mixed);
	}

	public function setDepedencies(atoum\dependencies $dependencies)
	{
		$this->dependencies = $dependencies[$this];

		$this->dependencies->lock();
		$this->dependencies['directory\iterator'] = function($path) { return new \recursiveDirectoryIterator($path); };
		$this->dependencies->unlock();

		return $this;
	}

	public function getDepedencies()
	{
		return $this->dependencies;
	}

	public function accept()
	{
		return (substr(basename((string) $this->getInnerIterator()->current()), 0, 1) != '.');
	}
}

?>
