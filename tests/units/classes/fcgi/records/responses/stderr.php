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
	public function testClassConstants()
	{
		$this
			->string(testedClass::type)->isEqualTo(7)
		;
	}

	public function testClass()
	{
		$this
			->testedClass->isSubClassOf('mageekguy\atoum\fcgi\record')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass($requestId = uniqid(), $contentData = uniqid()))
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->string($record->getRequestId())->isEqualTo($requestId)
				->string($record->getContentData())->isEqualTo($contentData)
		;
	}

	public function testCompleteResponse()
	{
		$this
			->if($record = new testedClass($requestId = uniqid(), $contentData = uniqid()))
			->then
				->boolean($record->completeResponse($response = new fcgi\response($requestId)))->isFalse()
				->string($response->getStdout())->isEmpty()
				->string($response->getStderr())->isEqualTo($contentData)
			->if($otherRecord = new testedClass($requestId, $otherContentData = uniqid()))
			->then
				->boolean($otherRecord->completeResponse($response))->isFalse()
				->string($response->getStdout())->isEmpty()
				->string($response->getStderr())->isEqualTo($contentData . $otherContentData)
				->boolean($record->completeResponse($response))->isFalse()
				->string($response->getStdout())->isEmpty()
				->string($response->getStderr())->isEqualTo($contentData . $otherContentData . $contentData)
				->exception(function() use ($record, & $requestId) { $record->completeResponse(new fcgi\response($requestId = uniqid())); })
					->isInstanceOf('mageekguy\atoum\fcgi\exceptions\runtime')
					->hasMessage('The response \'' . $requestId . '\' does not own the record \'' . $record->getRequestId() . '\'')
		;
	}
}
