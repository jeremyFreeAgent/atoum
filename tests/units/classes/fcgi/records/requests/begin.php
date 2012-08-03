<?php

namespace mageekguy\atoum\tests\units\fcgi\records\requests;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi,
	mageekguy\atoum\fcgi\records\requests\begin as testedClass
;

require_once __DIR__ . '/../../../../runner.php';

class begin extends atoum\test
{
	public function testClassConstants()
	{
		$this
			->string(testedClass::type)->isEqualTo(1)
			->string(testedClass::responder)->isEqualTo(1)
			->string(testedClass::authorizer)->isEqualTo(2)
			->string(testedClass::filter)->isEqualTo(3)
		;
	}

	public function testClass()
	{
		$this
			->testedClass->isSubClassOf('mageekguy\atoum\fcgi\records\request')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass())
			->then
				->string($record->getRole())->isEqualTo(testedClass::responder)
				->string($record->getRequestId())->isEqualTo('1')
				->boolean($record->connectionIsPersistent())->isFalse()
			->if($record = new testedClass(testedClass::responder))
			->then
				->string($record->getRole())->isEqualTo(testedClass::responder)
				->string($record->getRequestId())->isEqualTo('1')
				->boolean($record->connectionIsPersistent())->isFalse()
			->if($record = new testedClass(testedClass::authorizer))
			->then
				->string($record->getRole())->isEqualTo(testedClass::authorizer)
				->string($record->getRequestId())->isEqualTo('1')
				->boolean($record->connectionIsPersistent())->isFalse()
			->if($record = new testedClass(testedClass::filter))
			->then
				->string($record->getRole())->isEqualTo(testedClass::filter)
				->string($record->getRequestId())->isEqualTo('1')
				->boolean($record->connectionIsPersistent())->isFalse()
			->exception(function() { new testedClass(rand(4, PHP_INT_MAX)); })
				->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
				->hasMessage('Role is invalid')
			->if($record = new testedClass(testedClass::responder, $requestId = uniqid(), true))
				->string($record->getRole())->isEqualTo(testedClass::responder)
				->string($record->getRequestId())->isEqualTo($requestId)
				->boolean($record->connectionIsPersistent())->isTrue()
		;
	}

	public function testSendWithClient()
	{
		$this
			->if($record = new testedClass())
			->and
				->mockGenerator->shunt('sendData')
			->and($client = new \mock\mageekguy\atoum\fcgi\client())
			->and($client->getMockController()->sendData = $client)
			->then
				->variable($record->sendWithClient($client))->isNull()
				->mock($client)->call('sendData')->withArguments("\001\001\000\001\000" . chr('8') . "\000\000\000\001\000\000\000\000\000\000")->once()
			->if($record = new testedClass(testedClass::responder, $requestId = rand(2, 128), true))
			->then
				->variable($record->sendWithClient($client))->isNull()
				->mock($client)->call('sendData')->withArguments("\001\001\000" . chr($requestId) . "\000" . chr('8') . "\000\000\000\001\001\000\000\000\000\000")->once()
		;
	}
}
