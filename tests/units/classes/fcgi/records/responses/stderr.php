<?php

namespace mageekguy\atoum\tests\units\fcgi\records\responses;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi,
	mageekguy\atoum\fcgi\records\responses\stderr as testedClass
;

require_once __DIR__ . '/../../../../runner.php';

class stderr extends atoum\test
{
	public function testClass()
	{
		$this
			->string(testedClass::type)->isEqualTo(7)
			->testedClass->isSubClassOf('mageekguy\atoum\fcgi\record')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass($contentData = uniqid(), $requestId = uniqid()))
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->string($record->getRequestId())->isEqualTo($requestId)
				->string($record->getContentData())->isEqualTo($contentData)
		;
	}

	public function testIsEndOfRequest()
	{
		$this
			->if($record = new testedClass(uniqid(), rand(1, 128)))
			->then
				->boolean($record->isEndOfRequest())->isFalse()
		;
	}

	public function testAddToResponse()
	{
		$this
			->if($record = new testedClass($contentData = uniqid(), rand(1, 128)))
			->then
				->object($record->addToResponse($response = new fcgi\response()))->isIdenticalTo($response)
				->string($response->getErrors())->isEqualTo($contentData)
			->if($otherRecord = new testedClass($otherContentData = uniqid(), rand(1, 128)))
			->then
				->object($otherRecord->addToResponse($response))->isIdenticalTo($response)
				->string($response->getErrors())->isEqualTo($contentData . $otherContentData)
				->object($record->addToResponse($response))->isIdenticalTo($response)
				->string($response->getErrors())->isEqualTo($contentData . $otherContentData . $contentData)
		;
	}
}
