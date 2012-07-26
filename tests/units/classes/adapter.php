<?php

namespace mageekguy\atoum\tests\units;

use
	mageekguy\atoum
;

require_once __DIR__ . '/../runner.php';

class adapter extends atoum\test
{
	/** @engine inline */
	public function testFpm()
	{
		$client = new atoum\fpm\client('127.0.0.1', '9000');

		$request = new atoum\fpm\request();
		$request->REQUEST_METHOD = 'GET';
		$request->SCRIPT_FILENAME = __DIR__ . DIRECTORY_SEPARATOR . 'cli.php';

		$response = atoum\fpm\client\response::getFromClient($request->sendWithClient($client));

		var_dump($response->getHeaders(), $response->getOutput(), $response->getErrors());
	}

	/*
	public function test__construct()
	{
		$this
			->if($asserter = new \mock\mageekguy\atoum\asserter($generator = new atoum\asserter\generator()))
			->then
				->object($asserter->getGenerator())->isIdenticalTo($generator)
		;
	}

	public function test__call()
	{
		$this
			->if($adapter = new atoum\adapter())
			->then
				->string($adapter->md5($hash = uniqid()))->isEqualTo(md5($hash))
		;
	}
	*/
}
