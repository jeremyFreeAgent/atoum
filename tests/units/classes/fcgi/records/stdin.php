<?php

namespace mageekguy\atoum\tests\units\fcgi\records;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi\records\stdin as testedClass
;

require_once __DIR__ . '/../../../runner.php';

class stdin extends atoum\test
{
	public function testClass()
	{
		$this
			->string(testedClass::type)->isEqualTo(5)
			->testedClass->isSubclassOf('mageekguy\atoum\fcgi\record')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass())
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->integer($record->getRequestId())->isEqualTo(1)
				->string($record->getContentData())->isEmpty()
			->if($record = new testedClass($contentData = uniqid()))
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->integer($record->getRequestId())->isEqualTo(1)
				->string($record->getContentData())->isEqualTo($contentData)
			->if($record = new testedClass($contentData = uniqid(), $requestId = rand(2, 128)))
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->integer($record->getRequestId())->isEqualTo($requestId)
				->string($record->getContentData())->isEqualTo($contentData)
		;
	}
}
