<?php

namespace mageekguy\atoum\dependencies;

class resolver implements \arrayAccess
{
	protected $value = null;
	protected $dependencies = array();

	public function __construct($mixed = null)
	{
		$this->value = $mixed;
	}

	public function __invoke(array $dependencies = array())
	{
		foreach ($dependencies as $name => $value)
		{
			$this[$name] = $value;
		}

		return ($this->value instanceof \closure === false ? $this->value : $this->value->__invoke($this));
	}

	public function offsetGet($dependency)
	{
		$resolvedDependency = ltrim($dependency, '@');

//		if (isset($this->dependencies[$resolvedDependency]) === false)
//		{
//			$this->dependencies[$resolvedDependency] = new static();
//		}

		return (isset($this->dependencies[$resolvedDependency]) === false ? null : ($resolvedDependency === $dependency ? $this->dependencies[$resolvedDependency] : $this->dependencies[$resolvedDependency]()));
	}

	public function offsetSet($dependency, $mixed)
	{
		$this->dependencies[$dependency] = new static($mixed);

		return $this;
	}

	public function offsetUnset($dependency)
	{
		if (isset($this->dependencies[$dependency]) === true)
		{
			unset($this->dependencies[$dependency]);
		}

		return $this;
	}

	public function offsetExists($dependency)
	{
		return (isset($this->dependencies[$dependency]) === true && $this->dependencies[$dependency]->value !== null);
	}
}
