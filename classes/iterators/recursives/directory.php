<?php

namespace mageekguy\atoum\iterators\recursives;

use
	mageekguy\atoum,
	mageekguy\atoum\exceptions
;

class directory implements \iteratorAggregate
{
	protected $dependencies = null;
	protected $path = null;
	protected $acceptDots = false;
	protected $acceptedExtensions = array('php');

	public function __construct($path = null, atoum\dependencies $dependencies = null)
	{
		if ($path !== null)
		{
			$this->setPath($path);
		}

		$this->setDepedencies($dependencies ?: new atoum\dependencies());
	}

	public function setDepedencies(atoum\dependencies $dependencies)
	{
		$this->dependencies = $dependencies[$this];

		$this->dependencies->lock();
		$this->dependencies['directory\iterator'] = function($path) { return new \recursiveDirectoryIterator($path); };
		$this->dependencies['filters\dot'] = function($iterator, $dependencies) { return new atoum\iterators\filters\recursives\dot($iterator, $dependencies); };
		$this->dependencies['filters\extension'] = function($iterator, $extensions, $dependencies) { return new atoum\iterators\filters\recursives\extension($iterator, $extensions, $dependencies); };
		$this->dependencies->unlock();

		return $this;
	}

	public function setPath($path)
	{
		$this->path = (string) $path;

		return $this;
	}

	public function getDepedencies()
	{
		return $this->dependencies;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getIterator($path = null)
	{
		if ($path !== null)
		{
			$this->setPath($path);
		}
		else if ($this->path === null)
		{
			throw new exceptions\runtime('Path is undefined');
		}

		$iterator = $this->dependencies['directory\iterator']($this->path);

		if ($this->acceptDots === false)
		{
			$iterator = $this->dependencies['filters\dot']($iterator, $this->dependencies);
		}

		if (sizeof($this->acceptedExtensions) > 0)
		{
			$iterator = $this->dependencies['filters\extension']($iterator, $this->acceptedExtensions, $this->dependencies);
		}

		return $iterator;
	}

	public function dotsAreAccepted()
	{
		return $this->acceptDots;
	}

	public function acceptDots()
	{
		$this->acceptDots = true;

		return $this;
	}

	public function refuseDots()
	{
		$this->acceptDots = false;

		return $this;
	}

	public function getAcceptedExtensions()
	{
		return $this->acceptedExtensions;
	}

	public function acceptExtensions(array $extensions)
	{
		$this->acceptedExtensions = array();

		foreach ($extensions as $extension)
		{
			$this->acceptedExtensions[] = self::cleanExtension($extension);
		}

		return $this;
	}

	public function acceptAllExtensions()
	{
		return $this->acceptExtensions(array());
	}

	public function refuseExtension($extension)
	{
		$key = array_search(self::cleanExtension($extension), $this->acceptedExtensions);

		if ($key !== false)
		{
			unset($this->acceptedExtensions[$key]);

			$this->acceptedExtensions = array_values($this->acceptedExtensions);
		}

		return $this;
	}

	protected static function cleanExtension($extension)
	{
		return trim($extension, '.');
	}
}

?>
