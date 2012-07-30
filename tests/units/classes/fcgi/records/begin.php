<?php

namespace mageekguy\atoum\tests\units\fcgi\records;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi,
	mageekguy\atoum\fcgi\records\begin as testedClass
;

require_once __DIR__ . '/../../../runner.php';

class begin extends atoum\test
{
	public function testClass()
	{
		$this
			->string(testedClass::type)->isEqualTo(1)
			->string(testedClass::responder)->isEqualTo(1)
			->string(testedClass::authorizer)->isEqualTo(2)
			->string(testedClass::filter)->isEqualTo(3)
			->testedClass->isSubClassOf('mageekguy\atoum\fcgi\record')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass())
			->then
				->string($record->getRole())->isEqualTo(testedClass::responder)
				->integer($record->getRequestId())->isEqualTo(1)
				->boolean($record->connectionIsPersistent())->isFalse()
			->if($record = new testedClass(testedClass::responder))
			->then
				->string($record->getRole())->isEqualTo(testedClass::responder)
				->integer($record->getRequestId())->isEqualTo(1)
				->boolean($record->connectionIsPersistent())->isFalse()
			->if($record = new testedClass(testedClass::authorizer))
			->then
				->string($record->getRole())->isEqualTo(testedClass::authorizer)
				->integer($record->getRequestId())->isEqualTo(1)
				->boolean($record->connectionIsPersistent())->isFalse()
			->if($record = new testedClass(testedClass::filter))
			->then
				->string($record->getRole())->isEqualTo(testedClass::filter)
				->integer($record->getRequestId())->isEqualTo(1)
				->boolean($record->connectionIsPersistent())->isFalse()
			->exception(function() { new testedClass(rand(4, PHP_INT_MAX)); })
				->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
				->hasMessage('Role is invalid')
			->if($record = new testedClass(testedClass::responder, $requestId = rand(2, PHP_INT_MAX), true))
				->string($record->getRole())->isEqualTo(testedClass::responder)
				->integer($record->getRequestId())->isEqualTo($requestId)
				->boolean($record->connectionIsPersistent())->isTrue()
		;
	}

	public function test__toString()
	{
		$this
			->if($record = new testedClass())
			->then
				->castToString($record)->isEqualTo("\001\001\000\001\000" . chr('8') . "\000\000\000\001\000\000\000\000\000\000")
			->if($record = new testedClass(testedClass::responder, $requestId = rand(2, 128), true))
			->then
				->castToString($record)->isEqualTo("\001\001\000" . chr($requestId) . "\000" . chr('8') . "\000\000\000\001\001\000\000\000\000\000")
		;
	}

	public function testEncode()
	{
		$this
			->if($record = new testedClass())
			->then
				->string($record->encode())->isEqualTo("\001\001\000\001\000" . chr('8') . "\000\000\000\001\000\000\000\000\000\000")
			->if($record = new testedClass(testedClass::responder, $requestId = rand(2, 128), true))
			->then
				->string($record->encode())->isEqualTo("\001\001\000" . chr($requestId) . "\000" . chr('8') . "\000\000\000\001\001\000\000\000\000\000")
		;
	}
}
