<?php

namespace mageekguy\atoum\iterators\recursives;

use
	mageekguy\atoum,
	mageekguy\atoum\exceptions,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\iterators\filters
;

class directory implements \iteratorAggregate
{
	protected $path = null;
	protected $acceptDots = false;
	protected $acceptedExtensions = array('php');
	protected $dotFilterResolver = null;
	protected $extensionFilterResolver = null;
	protected $directoryIteratorResolver = null;

	public function __construct($path = null, dependencies\resolver $resolver = null)
	{
		if ($path !== null)
		{
			$this->setPath($path);
		}

		$resolver = $resolver ?: new dependencies\resolver();

		$this
			->setDirectoryIteratorResolver($resolver['@iterators\recursives\directory\resolver'] ?: $this->getDefaultDirectoryIteratorResolver($resolver))
			->setDotFilterResolver($resolver['@iterators\filters\recursives\dot\resolver'] ?: $this->getDefaultDotFilterResolver($resolver))
			->setExtensionFilterResolver($resolver['@iterators\filters\recursives\extension\resolver'] ?: $this->getDefaultExtensionFilterResolver($resolver))
		;
	}

	public function setPath($path)
	{
		$this->path = (string) $path;

		return $this;
	}

	public function setDirectoryIteratorResolver(dependencies\resolver $resolver)
	{
		$this->directoryIteratorResolver = $resolver;

		return $this;
	}

	public function getDirectoryIteratorResolver()
	{
		return $this->directoryIteratorResolver;
	}

	public function setDotFilterResolver(dependencies\resolver $resolver)
	{
		$this->dotFilterResolver = $resolver;

		return $this;
	}

	public function getDotFilterResolver()
	{
		return $this->dotFilterResolver;
	}

	public function setExtensionFilterResolver(dependencies\resolver $resolver)
	{
		$this->extensionFilterResolver = $resolver;

		return $this;
	}

	public function getExtensionFilterResolver()
	{
		return $this->extensionFilterResolver;
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

		$iterator = $this->directoryIteratorResolver->__invoke(array('directory' => $this->path));

		if ($this->acceptDots === false)
		{
			$iterator = $this->dotFilterResolver->__invoke(array('iterator' => $iterator));
		}

		if (sizeof($this->acceptedExtensions) > 0)
		{
			$iterator = $this->extensionFilterResolver->__invoke(array('iterator' => $iterator, 'extensions' => $this->acceptedExtensions));
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

	protected function getDefaultDirectoryIteratorResolver(dependencies\resolver $resolver)
	{
		return new dependencies\resolver(function($resolver) { return new \recursiveDirectoryIterator($resolver['@directory']); });
	}

	protected static function getDefaultDotFilterResolver(dependencies\resolver $resolver)
	{
		return new dependencies\resolver(function($resolver) { return new filters\recursives\dot($resolver['@iterator']); });
	}

	protected static function getDefaultExtensionFilterResolver(dependencies\resolver $resolver)
	{
		return new dependencies\resolver(function($resolver) { return new filters\recursives\extension($resolver['@iterator'], $resolver['@extensions']); });
	}
}
