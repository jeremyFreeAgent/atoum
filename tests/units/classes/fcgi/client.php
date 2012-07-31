<?php

namespace mageekguy\atoum\tests\units\fcgi;

use
	mageekguy\atoum,
	mageekguy\atoum\test,
	mageekguy\atoum\fcgi\client as testedClass
;

require_once __DIR__ . '/../../runner.php';

class client extends atoum\test
{
	public function test__construct()
	{
		$this
			->if($client = new testedClass())
			->then
				->string($client->getHost())->isEqualTo('127.0.0.1')
				->integer($client->getPort())->isEqualTo(9000)
				->object($client->getAdapter())->isInstanceOf('mageekguy\atoum\adapter')
		;
	}

	public function test__toString()
	{
		$this
			->if($client = new testedClass())
			->then
				->castToString($client)->isEqualTo('tcp://127.0.0.1:9000')
		;
	}

	public function testSetAdapter()
	{
		$this
			->if($client = new testedClass())
			->then
				->object($client->setAdapter($adapter = new test\adapter()))->isIdenticalTo($client)
				->object($client->getAdapter())->isIdenticalTo($adapter)
		;
	}
}
