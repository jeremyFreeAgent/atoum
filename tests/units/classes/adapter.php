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
		$request->gateway_interface = 'FastCGI/1.0';
		$request->request_method = 'POST';
		$request->script_filename = __DIR__ . DIRECTORY_SEPARATOR . 'post.php';
		$request->stdin = 'query=1234';

		var_dump($request($client));
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
