<?php

namespace mageekguy\atoum;

class factory
{
	protected $builder = null;

	public function __construct(\closure $builder)
	{
		$this->builder = $builder;
	}

	public function build()
	{
		return (is_callable($this->builder) === false ? $this->builder : call_user_func_array($this->builder, func_get_args()));
	}
}
