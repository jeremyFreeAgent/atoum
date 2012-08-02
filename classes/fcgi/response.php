<?php

namespace mageekguy\atoum\fcgi;

use
	mageekguy\atoum\exceptions,
	mageekguy\atoum\fcgi\records
;

class response
{
	protected $requestId = '';
	protected $stdout = '';
	protected $stderr = '';

	public function __construct($requestId)
	{
		$this->requestId = (string) $requestId;
	}

	public function getRequestId()
	{
		return $this->requestId;
	}

	public function getStdout()
	{
		return $this->stdout;
	}

	public function addToStdout(records\response $record)
	{
		return $this->addToStream($this->stdout, $record);
	}

	public function getStderr()
	{
		return $this->stderr;
	}

	public function addToStderr(records\response $record)
	{
		return $this->addToStream($this->stderr, $record);
	}

	public function isCompletedByRecord(records\response $record)
	{
		return $record->completeResponse($this);
	}

	private function addToStream(& $stream, records\response $record)
	{
		if ($record->getRequestId() == $this->requestId)
		{
			$stream .= $record->getContentData();
		}

		return $this;
	}
}
