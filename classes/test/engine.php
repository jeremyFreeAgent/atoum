<?php

namespace mageekguy\atoum\test;

use
	mageekguy\atoum,
	mageekguy\atoum\dependencies
;

abstract class engine
{
	public abstract function isAsynchronous();
	public abstract function run(atoum\test $test);
	public abstract function getScore();
}
