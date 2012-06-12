<?php

namespace mageekguy\atoum\report\fields\test;

use
	mageekguy\atoum\depedencies,
	mageekguy\atoum\test,
	mageekguy\atoum\report,
	mageekguy\atoum\test\cli,
	mageekguy\atoum\exceptions
;

abstract class event extends report\fields\event
{
	public function __construct(depedencies $depedencies = null)
	{
		parent::__construct(array(
				test::runStart,
				test::fail,
				test::error,
				test::uncompleted,
				test::exception,
				test::runtimeException,
				test::success,
				test::runStop
			),
			$depedencies
		);
	}
}

?>
