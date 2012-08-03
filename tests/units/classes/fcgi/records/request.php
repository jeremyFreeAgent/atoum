<?php

namespace mageekguy\atoum\tests\units\fcgi\records;

use
	mageekguy\atoum,
	mock\mageekguy\atoum\fcgi,
	mock\mageekguy\atoum\fcgi\records\request as testedClass
;

require_once __DIR__ . '/../../../runner.php';

class request extends atoum\test
{
	public function testClass()
	{
		$this
			->testedClass->isSubClassOf('mageekguy\atoum\fcgi\record')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass($type = rand(- 128, 127)))
			->then
				->string($record->getType())->isEqualTo($type)
				->string($record->getRequestId())->isEqualTo(1)
				->string($record->getContentData())->isEmpty()
			->if($record = new testedClass($type = rand(- 128, 127), $requestId = uniqid(), $contentData = uniqid()))
			->then
				->string($record->getType())->isEqualTo($type)
				->string($record->getRequestId())->isEqualTo($requestId)
				->string($record->getContentData())->isEqualTo($contentData)
		;
	}

	public function testSetRequestId()
	{
		$this
			->if($record = new testedClass(rand(- 128, 127)))
			->then
				->object($record->setRequestId($requestId = rand(- PHP_INT_MAX, PHP_INT_MAX)))->isIdenticalTo($record)
				->string($record->getRequestId())->isEqualTo($requestId)
				->object($record->setRequestId($otherRequestId = rand(- PHP_INT_MAX, PHP_INT_MAX)))->isIdenticalTo($record)
				->string($record->getRequestId())->isEqualTo($otherRequestId)
		;
	}

	public function testSetContentData()
	{
		$this
			->if($record = new testedClass($type = rand(- 128, 127)))
			->then
				->object($record->setContentData($contentData = uniqid()))->isIdenticalTo($record)
				->string($record->getContentData())->isEqualTo($contentData)
				->object($record->setContentData($otherContentData = uniqid()))->isIdenticalTo($record)
				->string($record->getContentData())->isEqualTo($otherContentData)
		;
	}

	public function testSendWithClient()
	{
		$this
			->if($record = new testedClass($type = rand(- 128, 127)))
			->and
				->mockGenerator->shunt('sendData')
			->and($client = new fcgi\client())
			->and($client->getMockController()->sendData = $client)
			->then
				->variable($record->sendWithClient($client))->isNull()
				->mock($client)->call('sendData')->withArguments("\001" . chr($type) . "\000\001\000\000\000\000")->once()
			->if($record = new testedClass($type = rand(- 128, 127), $requestId = rand(- PHP_INT_MAX, PHP_INT_MAX), $contentData = uniqid()))
			->then
				->variable($record->sendWithClient($client))->isNull()
				->mock($client)->call('sendData')->withArguments(sprintf('%c%c%c%c%c%c%c%c%s%s', fcgi\record::version, $type, ($requestId >> 8) & 0xff, $requestId & 0xff, (strlen($contentData) >> 8) & 0xff, strlen($contentData) & 0xff, 0, 0, $contentData, ''))->once()
			->if($record = new testedClass($type = rand(- 128, 127), $requestId = rand(- PHP_INT_MAX, PHP_INT_MAX), $contentData = str_repeat('0', 65536)))
			->then
				->exception(function() use ($record, $client) { $record->sendWithClient($client); })
					->isInstanceOf('mageekguy\atoum\exceptions\runtime')
					->hasMessage('Content length must be less than or equal to 65535')
		;
	}
}
