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
			->if($response = new testedClass())
			->then
				->array($response->getHeaders())->isEmpty()
				->string($response->getOutput())->isEmpty()
				->string($response->getErrors())->isEmpty()
		;
	}

	public function testGetFromClient()
	{
		$this
			->if($client = new \mock\mageekguy\atoum\fcgi\client())
			->and($client->getMockController()->receiveData = false)
			->and($response = new testedClass())
			->then
				->exception(function() use ($response, $client) { $response->getFromClient($client); })
					->isInstanceOf('mageekguy\atoum\exceptions\runtime')
					->hasMessage('Unable to get data from server \'' . $client . '\'')
			->if($client->getMockController()->resetCalls()->receiveData = 'xxxxxx')
			->then
				->exception(function() use ($response, $client) { $response->getFromClient($client); })
					->isInstanceOf('mageekguy\atoum\exceptions\runtime')
					->hasMessage('Unable to get data from server \'' . $client . '\'')
			->if($client->getMockController()->resetCalls()->receiveData = 'xxxxxxx')
			->then
				->exception(function() use ($response, $client) { $response->getFromClient($client); })
					->isInstanceOf('mageekguy\atoum\exceptions\runtime')
					->hasMessage('Unable to get data from server \'' . $client . '\'')
			->if($client->getMockController()->resetCalls())
			->and($client->getMockController()->receiveData[1] = "\001\006\000\001\000b\006\000")
			->and($client->getMockController()->receiveData[2] = "X-Powered-By: PHP/5.4.5\r\nContent-type: text/html\r\n\r\narray(1) {\n  [\"query\"]=>\n  string(4) \"1234\"\n}\n\000\000\000\000\000\000")
			->and($client->getMockController()->receiveData[3] = "\001\003\000\001\000b\000\000")
			->and($client->getMockController()->receiveData[4] = "\000\000\000\000\000\000\000\000")
			->then
				->object($response->getFromClient($client))->isIdenticalTo($response)
				->string($response->getOutput())->isEqualTo("array(1) {\n  [\"query\"]=>\n  string(4) \"1234\"\n}\n")
				->array($response->getHeaders())->isEqualTo(array(
						'X-Powered-By' => 'PHP/5.4.5',
						'Content-type' => 'text/html'
					)
				)
		;
	}
}
