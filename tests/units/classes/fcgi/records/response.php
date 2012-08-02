<?php

namespace mageekguy\atoum\tests\units\fcgi\records;

use
	mageekguy\atoum,
	mock\mageekguy\atoum\fcgi,
	mock\mageekguy\atoum\fcgi\records\response as testedClass
;

require_once __DIR__ . '/../../../runner.php';

class response extends atoum\test
{
	public function testClass()
	{
		$this
			->integer(fcgi\record::version)->isEqualTo(1)
			->integer(fcgi\record::maxContentLength)->isEqualTo(65535)
			->testedClass->hasInterface('countable')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass($type = rand(- 128, 127)))
			->then
				->string($record->getType())->isEqualTo($type)
				->string($record->getRequestId())->isEqualTo('0')
				->string($record->getContentData())->isEmpty()
				->sizeOf($record)->isZero()
			->if($record = new testedClass($type = rand(- 128, 127), $requestId = uniqid(), $contentData = uniqid()))
			->then
				->string($record->getType())->isEqualTo($type)
				->string($record->getRequestId())->isEqualTo($requestId)
				->string($record->getContentData())->isEqualTo($contentData)
				->sizeOf($record)->isEqualTo(strlen($contentData))
			->exception(function() { new testedClass(rand(128, PHP_INT_MAX)); })
				->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
				->hasMessage('Type must be greater than or equal to -128 and less than or equal to 127')
			->exception(function() { new testedClass(rand(- PHP_INT_MAX, -128)); })
				->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
				->hasMessage('Type must be greater than or equal to -128 and less than or equal to 127')
			->exception(function() { new testedClass(rand(- 128, 127), str_repeat('0', 65536)); })
				->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
				->hasMessage('Request ID length must be less than or equal to 65535')
		;
	}

	public function testCompleteResponse()
	{
		$this
			->if($record = new testedClass(rand(- 128, 127), 1))
			->then
				->boolean($record->completeResponse($response = new fcgi\response(1)))->isFalse()
				->exception(function() use ($record, & $requestId) { $record->completeResponse(new fcgi\response($requestId = uniqid())); })
					->isInstanceOf('mageekguy\atoum\fcgi\exceptions\runtime')
					->hasMessage('The response \'' . $requestId . '\' does not own the record \'' . $record->getRequestId() . '\'')
		;
	}

	public function testGetFromClient()
	{
		$this
			->if($client = new \mock\mageekguy\atoum\fcgi\client())
			->and($client->getMockController()->receiveData = false)
			->then
				->variable(testedClass::getFromClient($client))->isNull()
				->mock($client)->call('receiveData')->withArguments(8)->once()
			->if($client->getMockController()->resetCalls()->receiveData = 'xxxxxx')
			->then
				->variable(testedClass::getFromClient($client))->isNull()
				->mock($client)->call('receiveData')->withArguments(8)->once()
			->if($client->getMockController()->resetCalls()->receiveData = 'xxxxxxx')
			->then
				->variable(testedClass::getFromClient($client))->isNull()
				->mock($client)->call('receiveData')->withArguments(8)->once()
			->if($client->getMockController()->resetCalls())
			->and($client->getMockController()->receiveData[1] = "\001\006\000\001\000b\006\000")
			->and($client->getMockController()->receiveData[2] = ($contentData = "X-Powered-By: PHP/5.4.5\r\nContent-type: text/html\r\n\r\narray(1) {\n  [\"query\"]=>\n  string(4) \"1234\"\n}\n") . "\000\000\000\000\000\000")
			->then
				->object($stdout = testedClass::getFromClient($client))->isInstanceOf('mageekguy\atoum\fcgi\records\responses\stdout')
				->string($stdout->getContentData())->isEqualTo($contentData)
				->mock($client)
					->call('receiveData')
						->withArguments(8)->once()
						->withArguments(strlen($contentData) + 6)->once()
			->if($client->getMockController()->resetCalls())
			->and($client->getMockController()->receiveData[1] = "\001\007\000\001\000b\006\000")
			->and($client->getMockController()->receiveData[2] = ($contentData = "X-Powered-By: PHP/5.4.5\r\nContent-type: text/html\r\n\r\narray(1) {\n  [\"query\"]=>\n  string(4) \"1234\"\n}\n") . "\000\000\000\000\000\000")
			->then
				->object($stdout = testedClass::getFromClient($client))->isInstanceOf('mageekguy\atoum\fcgi\records\responses\stderr')
				->string($stdout->getContentData())->isEqualTo($contentData)
				->mock($client)
					->call('receiveData')
						->withArguments(8)->once()
						->withArguments(strlen($contentData) + 6)->once()
			->if($client->getMockController()->resetCalls())
			->and($client->getMockController()->receiveData[1] = "\001\003\000\001\000b\000\000")
			->and($client->getMockController()->receiveData[2] = $contentData = "\000\000\000\000\000\000\000\000")
			->then
				->object($end = testedClass::getFromClient($client))->isInstanceOf('mageekguy\atoum\fcgi\records\responses\end')
				->string($end->getContentData())->isEqualTo($contentData)
				->mock($client)->call('receiveData')->withArguments(8)->once()
			->if($client->getMockController()->resetCalls())
			->and($client->getMockController()->receiveData[1] = "\001\009\000\001\000b\000\000")
			->then
				->exception(function() use ($client) { testedClass::getFromClient($client); })
					->isInstanceOf('mageekguy\atoum\fcgi\exceptions\runtime')
					->hasMessage('Type \'' . ord("\009") . '\' is unknown')
		;
	}
}
