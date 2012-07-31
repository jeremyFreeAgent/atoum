<?php

namespace mageekguy\atoum\fcgi\records\responses;

use
	mageekguy\atoum\fcgi
;

class end extends fcgi\records\response
{
	const type = 3;
	const requestComplete = 0;
	const canNotMultiplexConnection = 1;
	const serverIsOverloaded = 2;
	const unknownRole = 3;

	public function __construct($contentData, $requestId)
	{
		parent::__construct(self::type, $requestId, $contentData);
	}

	public function getProtocolStatus()
	{
		return ord($this->contentData[4]);
	}

	public function isEndOfRequest()
	{
		return true;
	}

	public function requestIsSuccessfullyCompleted()
	{
		return ($this->getProtocolStatus() === self::requestComplete);
	}

	public function serverCanNotMultiplexConnection()
	{
		return ($this->getProtocolStatus() === self::canNotMultiplexConnection);
	}

	public function serverIsOverloaded()
	{
		return ($this->getProtocolStatus() === self::serverIsOverloaded);
	}

	public function roleIsUnknown()
	{
		return ($this->getProtocolStatus() === self::unknownRole);
	}
}
