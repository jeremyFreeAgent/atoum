<?php

namespace mageekguy\atoum\tests\units\fcgi\records\responses;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi,
	mageekguy\atoum\fcgi\records\responses\stdout as testedClass
;

require_once __DIR__ . '/../../../../runner.php';

class stdout extends atoum\test
{
	public function testClassConstants()
	{
		$this
			->string(testedClass::type)->isEqualTo(6)
		;
	}

	public function testClass()
	{
		$this
			->testedClass->isSubClassOf('mageekguy\atoum\fcgi\records\response')
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
				->string($response->getStdout())->isEqualTo($contentData)
				->string($response->getStderr())->isEmpty()
			->if($otherRecord = new testedClass($requestId, $otherContentData = uniqid()))
			->then
				->boolean($otherRecord->completeResponse($response))->isFalse()
				->string($response->getStdout())->isEqualTo($contentData . $otherContentData)
				->string($response->getStderr())->isEmpty()
				->boolean($record->completeResponse($response))->isFalse()
				->string($response->getStdout())->isEqualTo($contentData . $otherContentData . $contentData)
				->string($response->getStderr())->isEmpty()
				->exception(function() use ($record, & $requestId) { $record->completeResponse(new fcgi\response($requestId = uniqid())); })
					->isInstanceOf('mageekguy\atoum\fcgi\exceptions\runtime')
					->hasMessage('The response \'' . $requestId . '\' does not own the record \'' . $record->getRequestId() . '\'')
		;
	}
}
