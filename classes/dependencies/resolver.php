<?php

namespace mageekguy\atoum\dependencies;

class resolver implements \arrayAccess
{
	protected $name = null;
	protected $parent = null;
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

		if (isset($this->dependencies[$resolvedDependency]) === true)
		{
			$value = ($resolvedDependency === $dependency ? $this->dependencies[$resolvedDependency] : $this->dependencies[$resolvedDependency]());
		}
		else
		{
			$value = $this->getParentDependency($dependency);

			if ($value === null)
			{
				$this->dependencies[$resolvedDependency] = new static();
				$this->dependencies[$resolvedDependency]->name = $resolvedDependency;
				$this->dependencies[$resolvedDependency]->parent = $this;

				$value = ($resolvedDependency === $dependency ? $this->dependencies[$resolvedDependency] : $this->dependencies[$resolvedDependency]());
			}
		}

		return $value;
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

	protected function getParentDependency($dependency)
	{
		$resolvedDependency = ltrim($dependency, '@');

		$parent = $this->parent;

		while ($parent !== null)
		{
			if (isset($parent->dependencies[$resolvedDependency]) === false)
			{
				$parent = $parent->parent;
			}
			else
			{
				return ($resolvedDependency === $dependency ? $parent->dependencies[$resolvedDependency] : $parent->dependencies[$resolvedDependency]());
			}
		}

		return null;
	}
}
