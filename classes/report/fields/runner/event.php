<?php

namespace mageekguy\atoum\report\fields\runner;

use
	mageekguy\atoum\test,
	mageekguy\atoum\runner,
	mageekguy\atoum\report,
	mageekguy\atoum\depedencies
;

abstract class event extends report\fields\event
{
	public function __construct(depedencies $depedencies = null)
	{
		parent::__construct(array(
				runner::runStart,
				test::fail,
				test::error,
				test::uncompleted,
				test::exception,
				test::success,
				runner::runStop
			),
			$depedencies
		);
	}
}

?>
