<?php

namespace mageekguy\atoum\iterators\filters\recursives;

use
	mageekguy\atoum
;

class extension extends \recursiveFilterIterator
{
	protected $dependencies = null;
	protected $acceptedExtensions = array();

	public function __construct($mixed, array $acceptedExtensions, atoum\dependencies $dependencies = null)
	{
		$this
			->setDepedencies($dependencies ?: new atoum\dependencies())
			->setAcceptedExtensions($acceptedExtensions)
		;

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

	public function setAcceptedExtensions(array $extensions)
	{
		array_walk($extensions, function(& $extension) { $extension = trim($extension, '.'); });

		$this->acceptedExtensions = $extensions;

		return $this;
	}

	public function getAcceptedExtensions()
	{
		return $this->acceptedExtensions;
	}

	public function accept()
	{
		$path = basename((string) $this->getInnerIterator()->current());

		$extension = pathinfo($path, PATHINFO_EXTENSION);

		return ($extension == '' || in_array($extension, $this->acceptedExtensions) === true);
	}

	public function getChildren()
	{
		return new self($this->getInnerIterator()->getChildren(), $this->acceptedExtensions);
	}
}

?>
