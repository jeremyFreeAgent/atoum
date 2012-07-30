<?php

namespace mageekguy\atoum\tests\units\fpm\records;

use
	mageekguy\atoum,
	mageekguy\atoum\fpm,
	mageekguy\atoum\fpm\records\begin as testedClass
;

require_once __DIR__ . '/../../../runner.php';

class begin extends atoum\test
{
	public function testClass()
	{
		$this
			->integer(testedClass::type)->isEqualTo(1)
			->integer(testedClass::responder)->isEqualTo(1)
			->integer(testedClass::authorizer)->isEqualTo(2)
			->integer(testedClass::filter)->isEqualTo(3)
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass())
			->then
				->integer($record->getRole())->isEqualTo(testedClass::responder)
				->integer($record->getRequestId())->isEqualTo(1)
				->boolean($record->connectionIsPersistent())->isFalse()
			->if($record = new testedClass(testedClass::responder))
			->then
				->integer($record->getRole())->isEqualTo(testedClass::responder)
				->integer($record->getRequestId())->isEqualTo(1)
				->boolean($record->connectionIsPersistent())->isFalse()
			->if($record = new testedClass(testedClass::authorizer))
			->then
				->integer($record->getRole())->isEqualTo(testedClass::authorizer)
				->integer($record->getRequestId())->isEqualTo(1)
				->boolean($record->connectionIsPersistent())->isFalse()
			->if($record = new testedClass(testedClass::filter))
			->then
				->integer($record->getRole())->isEqualTo(testedClass::filter)
				->integer($record->getRequestId())->isEqualTo(1)
				->boolean($record->connectionIsPersistent())->isFalse()
			->exception(function() { new testedClass(rand(4, PHP_INT_MAX)); })
				->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
				->hasMessage('Role is invalid')
			->if($record = new testedClass(testedClass::responder, $requestId = rand(2, PHP_INT_MAX), true))
				->integer($record->getRole())->isEqualTo(testedClass::responder)
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
