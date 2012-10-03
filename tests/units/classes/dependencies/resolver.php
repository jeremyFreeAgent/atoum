<?php

namespace mageekguy\atoum\tests\units\dependencies;

use
	mageekguy\atoum,
	mageekguy\atoum\dependencies\resolver as testedClass
;

require_once __DIR__ . '/../../runner.php';

class resolver extends atoum\test
{
	public function testClass()
	{
		$this->testedClass->implements('arrayAccess');
	}

	public function test__construct()
	{
		$this
			->if($resolver = new testedClass())
			->then
				->castToString($resolver)->isEqualTo('/')
				->variable($resolver())->isNull()
			->if($resolver = new testedClass($value = uniqid()))
			->then
				->castToString($resolver)->isEqualTo('/')
				->string($resolver())->isEqualTo($value)
			->if($resolver = new testedClass(function() use (& $value) { return ($value = uniqid()); }))
			->then
				->castToString($resolver)->isEqualTo('/')
				->string($resolver())->isEqualTo($value)
		;
	}

	public function testOffsetSet()
	{
		$this
			->if($resolver = new testedClass())
			->and($resolver[$dependency = uniqid()] = $value = uniqid())
			->then
				->object($resolver[uniqid()])->isInstanceOf($this->getTestedClassName())
				->variable($resolver['@' . uniqid()])->isNull()
				->object($resolver[$dependency])->isInstanceOf($this->getTestedClassName())
				->string($resolver['@' . $dependency])->isEqualTo($value)
			->if($resolver[$dependency][$otherDependency = uniqid()] = $otherValue = uniqid())
			->then
				->object($resolver[uniqid()])->isInstanceOf($this->getTestedClassName())
				->variable($resolver['@' . uniqid()])->isNull()
				->object($resolver[$dependency])->isInstanceOf($this->getTestedClassName())
				->string($resolver['@' . $dependency])->isEqualTo($value)
				->object($resolver[$dependency][uniqid()])->isInstanceOf($this->getTestedClassName())
				->variable($resolver[$dependency]['@' . uniqid()])->isNull()
				->string($resolver[$dependency]['@' . $otherDependency])->isEqualTo($otherValue)
				->object($resolver[$dependency][$otherDependency])->isInstanceOf($this->getTestedClassName())
			->if($resolver[$unknownDependency = uniqid()][$dependency = uniqid()] = $value = uniqid())
			->then
				->object($resolver[uniqid()])->isInstanceOf($this->getTestedClassName())
				->variable($resolver['@' . uniqid()])->isNull()
		;
	}

	public function testOffsetGet()
	{
		$this
			->if($resolver = new testedClass())
			->then
				->object($resolver[$dependency = uniqid()])->isInstanceOf($this->getTestedClassName())
				->variable($resolver['@' . uniqid()])->isNull()
				->castToString($resolver)->isEqualTo('/')
				->castToString($resolver[$dependency])->isEqualTo('/' . $dependency)
				->object($resolver[$dependency][$otherDependency = uniqid()])->isInstanceOf($this->getTestedClassName())
				->variable($resolver['@' . uniqid()])->isNull()
				->castToString($resolver)->isEqualTo('/')
				->castToString($resolver[$dependency])->isEqualTo('/' . $dependency)
				->variable($resolver[$dependency]['@' . $otherDependency])->isNull()
				->castToString($resolver[$dependency])->isEqualTo('/' . $dependency)
				->castToString($resolver[$dependency][$otherDependency])->isEqualTo('/' . $dependency . '/' . $otherDependency)
			->if($resolver[$dependency = uniqid()] = function() use (& $value) { return ($value = uniqid()); })
			->then
				->object($resolver[$dependency])->isInstanceOf($this->getTestedClassName())
				->string($resolver['@' . $dependency])->isEqualTo($value)
		;
	}

	public function testOffsetExists()
	{
		$this
			->if($resolver = new testedClass())
			->then
				->boolean(isset($resolver[uniqid()]))->isFalse()
			->if($resolver[$dependency = uniqid()])
			->then
				->boolean(isset($resolver[uniqid()]))->isFalse()
				->boolean(isset($resolver[$dependency]))->isFalse()
			->if($resolver[$dependency] = uniqid())
			->then
				->boolean(isset($resolver[uniqid()]))->isFalse()
				->boolean(isset($resolver[$dependency]))->isTrue()
		;
	}

	public function testOffsetUnset()
	{
		$this
			->if($resolver = new testedClass())
			->then
				->when(function() use ($resolver, & $dependency) { unset($resolver[$dependency = uniqid()]); })
				->boolean(isset($resolver[$dependency]))->isFalse()
			->if($resolver[$dependency] = uniqid())
			->then
				->when(function() use ($resolver, $dependency) { unset($resolver[$dependency]); })
				->boolean(isset($resolver[$dependency]))->isFalse()
		;
	}
}
