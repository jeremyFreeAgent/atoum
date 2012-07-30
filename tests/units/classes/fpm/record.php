<?php

namespace mageekguy\atoum\tests\units\fpm;

use
	mageekguy\atoum,
	mock\mageekguy\atoum\fpm,
	mock\mageekguy\atoum\fpm\record as testedClass
;

require_once __DIR__ . '/../../runner.php';

class record extends atoum\test
{
	public function testClass()
	{
		$this
			->integer(fpm\record::version)->isEqualTo(1)
			->integer(fpm\record::maxContentLength)->isEqualTo(65535)
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

	public function test__toString()
	{
		$this
			->if($record = new testedClass($type = rand(- 128, 127)))
			->then
				->castToString($record)->isEqualTo(sprintf('%c%c%c%c%c%c%c%c%s%s', fpm\record::version, $type, 0, 0, 0, 0, 0, 0, '', ''))
			->if($record = new testedClass($type = rand(- 128, 127), $requestId = rand(- PHP_INT_MAX, PHP_INT_MAX), $contentData = uniqid()))
			->then
				->castToString($record)->isEqualTo(sprintf('%c%c%c%c%c%c%c%c%s%s', fpm\record::version, $type, ($requestId >> 8) & 0xff, $requestId & 0xff, (strlen($contentData) >> 8) & 0xff, strlen($contentData) & 0xff, 0, 0, $contentData, ''))
		;
	}

	public function testEncode()
	{
		$this
			->if($record = new testedClass($type = rand(- 128, 127)))
			->then
				->string($record->encode())->isEqualTo(sprintf('%c%c%c%c%c%c%c%c%s%s', fpm\record::version, $type, 0, 0, 0, 0, 0, 0, '', ''))
			->if($record = new testedClass($type = rand(- 128, 127), $requestId = rand(- PHP_INT_MAX, PHP_INT_MAX), $contentData = uniqid()))
			->then
				->string($record->encode())->isEqualTo(sprintf('%c%c%c%c%c%c%c%c%s%s', fpm\record::version, $type, ($requestId >> 8) & 0xff, $requestId & 0xff, (strlen($contentData) >> 8) & 0xff, strlen($contentData) & 0xff, 0, 0, $contentData, ''))
			->if($record = new testedClass($type = rand(- 128, 127), $requestId = rand(- PHP_INT_MAX, PHP_INT_MAX), $contentData = str_repeat('0', 65536)))
			->then
				->exception(function() use ($record) { $record->encode(); })
					->isInstanceOf('mageekguy\atoum\exceptions\runtime')
					->hasMessage('Content length must be less than or equal to 65535')
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
				->object($record->addToResponse($response = new fpm\response()))->isIdenticalTo($response)
		;
	}
}
