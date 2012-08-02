<?php

namespace mageekguy\atoum\tests\units\fcgi;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi\response as testedClass
;

require_once __DIR__ . '/../../runner.php';

class response extends atoum\test
{
	public function test__construct()
	{
		$this
			->if($response = new testedClass($requestId = uniqid()))
			->then
				->string($response->getRequestId())->isEqualTo($requestId)
				->string($response->getStdout())->isEmpty()
				->string($response->getStderr())->isEmpty()
		;
	}
}
