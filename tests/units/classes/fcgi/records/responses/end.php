<?php

namespace mageekguy\atoum\tests\units\fcgi\records\responses;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi\records\responses\end as testedClass
;

require_once __DIR__ . '/../../../../runner.php';

class end extends atoum\test
{
	public function testClass()
	{
		$this
			->integer(testedClass::type)->isEqualTo(3)
			->integer(testedClass::requestComplete)->isZero()
			->integer(testedClass::canNotMultiplexConnection)->isEqualTo(1)
			->integer(testedClass::serverIsOverloaded)->isEqualTo(2)
			->integer(testedClass::unknownRole)->isEqualTo(3)
			->testedClass->isSubClassOf('mageekguy\atoum\fcgi\record')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass("\000\000\000\000" . chr(testedClass::requestComplete) . "\000", $requestId = uniqid()))
			->then
				->string($record->getRequestId())->isEqualTo($requestId)
				->integer($record->getProtocolStatus())->isEqualTo(testedClass::requestComplete)
			->if($record = new testedClass("\000\000\000\000" . chr(testedClass::canNotMultiplexConnection) . "\000", $requestId = uniqid()))
			->then
				->string($record->getRequestId())->isEqualTo($requestId)
				->integer($record->getProtocolStatus())->isEqualTo(testedClass::canNotMultiplexConnection)
			->if($record = new testedClass("\000\000\000\000" . chr(testedClass::serverIsOverloaded) . "\000", $requestId = uniqid()))
			->then
				->string($record->getRequestId())->isEqualTo($requestId)
				->integer($record->getProtocolStatus())->isEqualTo(testedClass::serverIsOverloaded)
			->if($record = new testedClass("\000\000\000\000" . chr(testedClass::unknownRole) . "\000", $requestId = uniqid()))
			->then
				->string($record->getRequestId())->isEqualTo($requestId)
				->integer($record->getProtocolStatus())->isEqualTo(testedClass::unknownRole)
		;
	}

	public function testIsEndOfRequest()
	{
		$this
			->if($record = new testedClass("\000\000\000\000" . chr(testedClass::requestComplete) . "\000", $requestId = rand(1, 128)))
			->then
				->boolean($record->isEndOfRequest())->isTrue()
			->if($record = new testedClass("\000\000\000\000" . chr(testedClass::canNotMultiplexConnection) . "\000", $requestId = rand(1, 128)))
			->then
				->boolean($record->isEndOfRequest())->isTrue()
			->if($record = new testedClass("\000\000\000\000" . chr(testedClass::serverIsOverloaded) . "\000", $requestId = rand(1, 128)))
			->then
				->boolean($record->isEndOfRequest())->isTrue()
			->if($record = new testedClass("\000\000\000\000" . chr(testedClass::unknownRole) . "\000", $requestId = rand(1, 128)))
			->then
				->boolean($record->isEndOfRequest())->isTrue()
		;
	}
}
