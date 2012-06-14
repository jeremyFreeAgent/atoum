<?php

namespace mageekguy\atoum\test;

use
	mageekguy\atoum
;

abstract class engine
{
	protected $dependencies = null;

	public function __construct(atoum\dependencies $dependencies = null)
	{
		$this->setDepedencies($dependencies ?: new atoum\dependencies());
	}

	public function setDepedencies(atoum\dependencies $dependencies)
	{
		$this->dependencies = $dependencies[$this];

		$this->dependencies->lock();
		$this->dependencies['score'] = function($dependencies) { return new atoum\score($dependencies); };
		$this->dependencies->unlock();

		return $this;
	}

	public function getDepedencies()
	{
		return $this->dependencies;
	}

	public abstract function isAsynchronous();
	public abstract function run(atoum\test $test);
	public abstract function getScore();
}

?>
