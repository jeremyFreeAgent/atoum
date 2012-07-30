<?php

namespace mageekguy\atoum\tests\units\fpm\records;

use
	mageekguy\atoum,
	mageekguy\atoum\fpm,
	mageekguy\atoum\fpm\records\params as testedClass
;

require __DIR__ . '/../../../runner.php';

class params extends atoum\test
{
	public function testClass()
	{
		$this
			->string(testedClass::type)->isEqualTo(4)
			->testedClass->isSubClassOf('mageekguy\atoum\fpm\record')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass())
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->integer($record->getRequestId())->isEqualTo(1)
				->array($record->getValues())->isEmpty()
				->sizeOf($record)->isZero()
			->if($record = new testedClass($values = array(uniqid() => uniqid())))
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->integer($record->getRequestId())->isEqualTo(1)
				->array($record->getValues())->isEqualTo($values)
				->sizeOf($record)->isEqualTo(sizeof($values))
		;
	}

	public function test__toString()
	{
		$this
			->if($record = new testedClass())
			->then
				->castToString($record)->isEqualTo("\001\004\000\001\000\000\000\000")
			->if($record->addValue($name = uniqid(), $value = uniqid()))
			->then
				->castToString($record)->isEqualTo("\001\004\000\001\000\034\000\000\r\r" . $name . $value)
			->if($record->addValue($otherName = uniqid(), $otherValue = uniqid()))
			->then
				->castToString($record)->isEqualTo("\001\004\000\001\0008\000\000\r\r" . $name . $value . "\r\r" . $otherName . $otherValue)
		;
	}

	public function testCount()
	{
		$this
			->if($record = new testedClass())
			->then
				->sizeOf($record)->isZero()
			->if($record->addValue(uniqid(), uniqid()))
			->then
				->sizeOf($record)->isEqualTo(1)
			->if($record->addValue(uniqid(), uniqid()))
			->then
				->sizeOf($record)->isEqualTo(2)
		;
	}

	public function testGetValues()
	{
		$this
			->if($record = new testedClass())
			->then
				->object($record->addValue($name = uniqid(), $value = uniqid()))->isIdenticalTo($record)
				->array($record->getValues())->isEqualTo(array($name => $value))
				->object($record->addValue($name, $otherValue = uniqid()))->isIdenticalTo($record)
				->array($record->getValues())->isEqualTo(array($name => $otherValue))
				->object($record->addValue($otherName = uniqid(), $value))->isIdenticalTo($record)
				->array($record->getValues())->isEqualTo(array($name => $otherValue, $otherName => $value))
		;
	}

	public function testEncode()
	{
		$this
			->if($record = new testedClass())
			->then
				->string($record->encode())->isEqualTo("\001\004\000\001\000\000\000\000")
			->if($record->addValue($name = uniqid(), $value = uniqid()))
			->then
				->string($record->encode())->isEqualTo("\001\004\000\001\000\034\000\000\r\r" . $name . $value)
			->if($record->addValue($otherName = uniqid(), $otherValue = uniqid()))
			->then
				->string($record->encode())->isEqualTo("\001\004\000\001\0008\000\000\r\r" . $name . $value . "\r\r" . $otherName . $otherValue)
		;
	}
}
