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
			->integer(testedClass::serverCanNotMultiplexConnection)->isEqualTo(1)
			->integer(testedClass::serverIsOverloaded)->isEqualTo(2)
			->integer(testedClass::serverDoesNotKnowTheRole)->isEqualTo(3)
			->testedClass->isSubClassOf('mageekguy\atoum\fcgi\records\response')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass($requestId = uniqid(), $contentData = "\000\000\000\000" . chr(testedClass::requestComplete) . "\000\000\000"))
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->string($record->getRequestId())->isEqualTo($requestId)
				->string($record->getContentData())->isEqualTo($contentData)
				->sizeOf($record)->isEqualTo(8)
			->exception(function() { new testedClass(uniqid(), ''); })
				->isInstanceOf('mageekguy\atoum\fcgi\exceptions\runtime')
				->hasMessage('Content data are invalid')
			->exception(function() { new testedClass(uniqid(), "\000\000\000\000" . chr(testedClass::serverCanNotMultiplexConnection) . "\000\000\000"); })
				->isInstanceOf('mageekguy\atoum\fcgi\exceptions\runtime')
				->hasMessage('Server can not multiplex connection')
			->exception(function() { new testedClass(uniqid(), "\000\000\000\000" . chr(testedClass::serverIsOverloaded) . "\000\000\000"); })
				->isInstanceOf('mageekguy\atoum\fcgi\exceptions\runtime')
				->hasMessage('Server is overloaded')
			->exception(function() { new testedClass(uniqid(), "\000\000\000\000" . chr(testedClass::serverDoesNotKnowTheRole) . "\000\000\000"); })
				->isInstanceOf('mageekguy\atoum\fcgi\exceptions\runtime')
				->hasMessage('Server does not know the role')
		;
	}
}
