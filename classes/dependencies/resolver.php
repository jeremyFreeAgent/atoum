<?php

namespace mageekguy\atoum\dependencies;

class resolver implements \arrayAccess
{
	protected $name = null;
	protected $parent = null;
	protected $value = null;
	protected $dependencies = array();

	public function __toString()
	{
		$path = $this->name;
		$parent = $this->parent;

		while ($parent !== null)
		{
			$path = $parent->name . '/' . $path;
			$parent = $parent->parent;
		}

		return ($path !== null ? '' : '/') . $path;
	}

	public function offsetGet($dependency)
	{
		$resolvedDependency = ltrim($dependency, '@');

		if (isset($this->dependencies[$resolvedDependency]) === false)
		{
			$this->dependencies[$resolvedDependency] = new static();
			$this->dependencies[$resolvedDependency]->name = $resolvedDependency;
			$this->dependencies[$resolvedDependency]->parent = $this;
		}

		switch (true)
		{
			case $resolvedDependency === $dependency:
				return $this->dependencies[$resolvedDependency];

			case $this->dependencies[$resolvedDependency]->value instanceof \closure:
				$dependency = $this->dependencies[$resolvedDependency]->value;
				return $dependency($this);

			default:
				return $this->dependencies[$resolvedDependency]->value;
		}
	}

	public function offsetSet($dependency, $mixed)
	{
		$this[$dependency]->value = $mixed;

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
