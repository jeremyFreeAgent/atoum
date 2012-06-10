<?php

namespace mageekguy\atoum\tests\units\test;

use
	mageekguy\atoum
;

require_once __DIR__ . '/../../runner.php';

class adapter extends atoum\test
{
	public function test__construct()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->then
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
		;
	}

	public function test__set()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->and($adapter->md5 = $closure = function() {})
			->then
				->object($adapter->md5->getClosure())->isIdenticalTo($closure)
			->if($adapter->md5 = $return = uniqid())
			->then
				->object($adapter->md5)->isInstanceOf('mageekguy\atoum\test\adapter\invoker')
				->string($adapter->invoke('md5'))->isEqualTo($return)
				->object($adapter->MD5)->isInstanceOf('mageekguy\atoum\test\adapter\invoker')
				->string($adapter->invoke('MD5'))->isEqualTo($return)
			->if($adapter->MD5 = $return = uniqid())
			->then
				->object($adapter->md5)->isInstanceOf('mageekguy\atoum\test\adapter\invoker')
				->string($adapter->invoke('md5'))->isEqualTo($return)
				->object($adapter->MD5)->isInstanceOf('mageekguy\atoum\test\adapter\invoker')
				->string($adapter->invoke('MD5'))->isEqualTo($return)
		;
	}

	public function test__get()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->and($adapter->md5 = $closure = function() {})
			->then
				->object($adapter->md5->getClosure())->isIdenticalTo($closure)
				->object($adapter->MD5->getClosure())->isIdenticalTo($closure)
			->if($adapter->md5 = $return = uniqid())
			->then
				->object($adapter->md5->getClosure())->isInstanceOf('closure')
				->object($adapter->MD5->getClosure())->isInstanceOf('closure')
		;
	}

	public function test__isset()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->then
				->boolean(isset($adapter->md5))->isFalse()
			->if($adapter->{$function = strtolower(uniqid())} = function() {})
			->then
				->boolean(isset($adapter->{$function}))->isTrue()
				->boolean(isset($adapter->{strtoupper($function)}))->isTrue()
			->if($adapter->{$function = strtoupper(uniqid())} = function() {})
			->then
				->boolean(isset($adapter->{$function}))->isTrue()
				->boolean(isset($adapter->{strtolower($function)}))->isTrue()
			->if($adapter->{$function = strtolower(uniqid())} = uniqid())
			->then
				->boolean(isset($adapter->{$function}))->isTrue()
				->boolean(isset($adapter->{strtoupper($function)}))->isTrue()
			->if($adapter->{$function = strtoupper(uniqid())} = uniqid())
			->then
				->boolean(isset($adapter->{$function}))->isTrue()
				->boolean(isset($adapter->{strtolower($function)}))->isTrue()
		;
	}

	public function test__unset()
	{
		$this
			->when(function() use (& $adapter) { $adapter = new atoum\test\adapter(); })
			->then
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
			->when(function() use ($adapter) { unset($adapter->md5); })
			->then
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
			->when(function() use ($adapter) { unset($adapter->MD5); })
			->then
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
			->when(function() use ($adapter) { $adapter->md5 = uniqid(); $adapter->md5(uniqid()); })
			->then
				->array($adapter->getInvokers())->isNotEmpty()
				->array($adapter->getCalls())->isNotEmpty()
			->when(function() use ($adapter) { unset($adapter->{uniqid()}); })
			->then
				->array($adapter->getInvokers())->isNotEmpty()
				->array($adapter->getCalls())->isNotEmpty()
			->when(function() use ($adapter) { unset($adapter->md5); })
			->then
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
			->when(function() use ($adapter) { $adapter->MD5 = uniqid(); $adapter->MD5(uniqid()); })
			->then
				->array($adapter->getInvokers())->isNotEmpty()
				->array($adapter->getCalls())->isNotEmpty()
			->when(function() use ($adapter) { unset($adapter->{uniqid()}); })
			->then
				->array($adapter->getInvokers())->isNotEmpty()
				->array($adapter->getCalls())->isNotEmpty()
			->when(function() use ($adapter) { unset($adapter->MD5); })
			->then
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
		;
	}

	public function test__call()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->then
				->string($adapter->md5($hash = uniqid()))->isEqualTo(md5($hash))
				->string($adapter->MD5($hash = uniqid()))->isEqualTo(md5($hash))
			->if($adapter->md5 = $md5 = uniqid())
			->then
				->string($adapter->md5($hash))->isEqualTo($md5)
				->string($adapter->MD5($hash))->isEqualTo($md5)
				->exception(function() use ($adapter) {
							$adapter->require(uniqid());
						}
					)
					->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
					->hasMessage('Function \'require()\' is not invokable by an adapter')
				->exception(function() use ($adapter) {
							$adapter->REQUIRE(uNiqid());
						}
					)
					->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
					->hasMessage('Function \'REQUIRE()\' is not invokable by an adapter')
			->if($adapter->md5 = 0)
			->and($adapter->md5[1] = 1)
			->and($adapter->md5[2] = 2)
			->and($adapter->resetCalls())
			->then
				->integer($adapter->md5())->isEqualTo(1)
				->integer($adapter->md5())->isEqualTo(2)
				->integer($adapter->md5())->isEqualTo(0)
			->if($adapter->MD5 = 0)
			->and($adapter->MD5[1] = 1)
			->and($adapter->MD5[2] = 2)
			->and($adapter->resetCalls())
			->then
				->integer($adapter->md5())->isEqualTo(1)
				->integer($adapter->md5())->isEqualTo(2)
				->integer($adapter->md5())->isEqualTo(0)
		;
	}

	public function testGetCallsNumber()
	{
		$this
			->integer(atoum\test\adapter::getCallsNumber())->isZero()
			->if($adapter = new atoum\test\adapter())
			->and($adapter->md5(uniqid()))
			->then
				->integer(atoum\test\adapter::getCallsNumber())->isEqualTo(1)
			->if($adapter->md5(uniqid()))
			->then
				->integer(atoum\test\adapter::getCallsNumber())->isEqualTo(2)
			->if($otherAdapter = new atoum\test\adapter())
			->and($otherAdapter->sha1(uniqid()))
			->then
				->integer(atoum\test\adapter::getCallsNumber())->isEqualTo(3)
		;
	}

	public function testGetCalls()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->then
				->array($adapter->getCalls())->isEmpty()
			->when(function() use ($adapter, & $firstHash) { $adapter->md5($firstHash = uniqid()); })
			->then
				->array($adapter->getCalls())->isEqualTo(array('md5' => array(1 => array($firstHash))))
				->array($adapter->getCalls('md5'))->isEqualTo(array(1 => array($firstHash)))
				->array($adapter->getCalls('MD5'))->isEqualTo(array(1 => array($firstHash)))
			->when(function() use ($adapter, & $secondHash) { $adapter->md5($secondHash = uniqid()); })
			->then
				->array($adapter->getCalls())->isEqualTo(array('md5' => array(1 => array($firstHash), 2 => array($secondHash))))
				->array($adapter->getCalls('md5'))->isEqualTo(array(1 => array($firstHash), 2 => array($secondHash)))
				->array($adapter->getCalls('MD5'))->isEqualTo(array(1 => array($firstHash), 2 => array($secondHash)))
			->when(function() use ($adapter, & $thirdHash) {
					$adapter->md5 = function() {};
					$adapter->md5($thirdHash = uniqid());
				}
			)
			->then
				->array($adapter->getCalls())->isEqualTo(array('md5' => array(1 => array($firstHash), 2 => array($secondHash), 3 => array($thirdHash))))
				->array($adapter->getCalls('md5'))->isEqualTo(array(1 => array($firstHash), 2 => array($secondHash), 3 => array($thirdHash)))
				->array($adapter->getCalls('MD5'))->isEqualTo(array(1 => array($firstHash), 2 => array($secondHash), 3 => array($thirdHash)))
			->when(function() use ($adapter, & $haystack, & $needle, & $offset) {
					$haystack = uniqid();
					$needle = uniqid();
					$offset = rand(0, 12);

					$adapter->strpos($haystack, $needle, $offset);
				}
			)
			->then
				->array($adapter->getCalls())->isEqualTo(array(
							'md5' => array(
								1 => array($firstHash),
								2 => array($secondHash),
								3 => array($thirdHash)
							),
							'strpos' => array(
								4 => array($haystack, $needle, $offset)
							)
					)
				)
				->array($adapter->getCalls('md5'))->isEqualTo(array(1 => array($firstHash), 2 => array($secondHash), 3 => array($thirdHash)))
				->array($adapter->getCalls('MD5'))->isEqualTo(array(1 => array($firstHash), 2 => array($secondHash), 3 => array($thirdHash)))
				->array($adapter->getCalls('strpos'))->isEqualTo(array(4 => array($haystack, $needle, $offset)))
				->array($adapter->getCalls('STRPOS'))->isEqualTo(array(4 => array($haystack, $needle, $offset)))
		;
	}

	public function testResetCalls()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->and($adapter->md5(uniqid()))
			->then
				->array($adapter->getCalls())->isNotEmpty()
				->object($adapter->resetCalls())->isIdenticalTo($adapter)
				->array($adapter->getCalls())->isEmpty()
		;
	}

	public function testReset()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->then
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
				->object($adapter->reset())->isIdenticalTo($adapter)
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
			->if($adapter->md5(uniqid()))
			->then
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isNotEmpty()
				->object($adapter->reset())->isIdenticalTo($adapter)
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
			->if($adapter->md5 = uniqid())
			->then
				->array($adapter->getInvokers())->isNotEmpty()
				->array($adapter->getCalls())->isEmpty()
				->object($adapter->reset())->isIdenticalTo($adapter)
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
			->if($adapter->md5 = uniqid())
			->and($adapter->md5(uniqid()))
			->then
				->array($adapter->getInvokers())->isNotEmpty()
				->array($adapter->getCalls())->isNotEmpty()
				->object($adapter->reset())->isIdenticalTo($adapter)
				->array($adapter->getInvokers())->isEmpty()
				->array($adapter->getCalls())->isEmpty()
		;
	}

	public function testAddCall()
	{
		$this
			->if($adapter = new atoum\test\adapter())
			->then
				->array($adapter->getCalls())->isEmpty()
				->object($adapter->addCall($method = uniqid(), $args = array(uniqid())))->isIdenticalTo($adapter)
				->array($adapter->getCalls($method))->isEqualTo(array(1 => $args))
				->array($adapter->getCalls(strtoupper($method)))->isEqualTo(array(1 => $args))
		;
	}
}

?>
