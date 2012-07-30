<?php

namespace mageekguy\atoum\tests\units\fpm\records;

use
	mageekguy\atoum,
	mageekguy\atoum\fpm\records\end as testedClass
;

require_once __DIR__ . '/../../../runner.php';

class end extends atoum\test
{
	public function testClass()
	{
		$this
			->integer(testedClass::type)->isEqualTo(3)
		;
	}
}
