<?php

namespace mageekguy\atoum\asserter;

use
	mageekguy\atoum,
	mageekguy\atoum\exceptions
;

class generator
{
	protected $dependencies = null;
	protected $locale = null;
	protected $aliases = array();

	public function __construct(atoum\dependencies $dependencies = null)
	{
		$this
			->setDepedencies($dependencies ?: new atoum\dependencies())
			->setLocale($this->dependencies['locale']())
		;
	}

	public function __set($asserter, $class)
	{
		$this->setAlias($asserter, $class);
	}

	public function __get($property)
	{
		return $this->getAsserterInstance($property);
	}

	public function __call($method, $arguments)
	{
		return $this->getAsserterInstance($method, $arguments);
	}

	public function setDepedencies(atoum\dependencies $dependencies)
	{
		$this->dependencies = $dependencies[$this];

		$this->dependencies->lock();
		$this->dependencies['locale'] = function() { return new atoum\locale(); };
		$this->dependencies->unlock();

		return $this;
	}

	public function getDepedencies()
	{
		return $this->dependencies;
	}

	public function setLocale(atoum\locale $locale)
	{
		$this->locale = $locale;

		return $this;
	}

	public function getLocale()
	{
		return $this->locale;
	}

	public function setAlias($alias, $asserterClass)
	{
		$this->aliases[$alias] = $asserterClass;

		return $this;
	}

	public function getAliases()
	{
		return $this->aliases;
	}

	public function resetAliases()
	{
		$this->aliases = array();

		return $this;
	}

	public function getAsserterClass($asserter)
	{
		$class = (isset($this->aliases[$asserter]) === false ? $asserter : $this->aliases[$asserter]);

		if (substr($class, 0, 1) != '\\')
		{
			$class = __NAMESPACE__ . 's\\' . $class;
		}

		if (class_exists($class, true) === false)
		{
			$class = null;
		}

		return $class;
	}

	public function getAsserterInstance($asserter, array $arguments = array())
	{
		if (($asserterClass = $this->getAsserterClass($asserter)) === null)
		{
			throw new exceptions\logic\invalidArgument('Asserter \'' . $asserter . '\' does not exist');
		}

		$asserterInstance = new $asserterClass($this);

		return $asserterInstance->setWithArguments($arguments);
	}
}

?>
