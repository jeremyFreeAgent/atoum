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
				->array($client->getServers())->isEmpty()
				->object($client->getAdapter())->isInstanceOf('mageekguy\atoum\adapter')
			->if($client = new testedClass($servers = array('tcp://127.0.0.1:9000' => 30), $adapter = new atoum\adapter()))
			->then
				->array($client->getServers())->isEqualTo($servers)
				->object($client->getAdapter())->isIdenticalTo($adapter)
		;
	}

	public function test__toString()
	{
		$this
			->if($client = new testedClass())
			->then
				->castToString($client)->isEmpty()
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
