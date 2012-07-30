<?php

namespace mageekguy\atoum\tests\units\fcgi\records;

use
	mageekguy\atoum,
	mock\mageekguy\atoum\fcgi,
	mock\mageekguy\atoum\fcgi\records\response as testedClass
;

require_once __DIR__ . '/../../runner.php';

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
				->integer($record->getRequestId())->isZero()
				->string($record->getContentData())->isEmpty()
				->sizeOf($record)->isZero()
			->if($record = new testedClass($type = rand(- 128, 127), $requestId = rand(- PHP_INT_MAX, PHP_INT_MAX), $contentData = uniqid()))
			->then
				->string($record->getType())->isEqualTo($type)
				->integer($record->getRequestId())->isEqualTo($requestId)
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

	public function testIsEndOfRequest()
	{
		$this
			->if($record = new testedClass(rand(- 128, 127)))
			->then
				->boolean($record->isEndOfRequest())->isFalse()
		;
	}

	public function testAddRoResponse()
	{
		$this
			->if($record = new testedClass(rand(- 128, 127)))
			->then
				->object($record->addToResponse($response = new fcgi\response()))->isIdenticalTo($response)
		;
	}
}
