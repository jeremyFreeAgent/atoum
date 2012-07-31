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

	protected $protocolStatus = 0;

	public function __construct($contentData, $requestId)
	{
		parent::__construct(self::type, $requestId);

		$this->protocolStatus = ord($contentData[4]);
	}

	public function getProtocolStatus()
	{
		return $this->protocolStatus;
	}

	public function isEndOfRequest()
	{
		return true;
	}

	public function requestIsSuccessfullyCompleted()
	{
		return ($this->protocolStatus === self::requestComplete);
	}

	public function serverCanNotMultiplexConnection()
	{
		return ($this->protocolStatus === self::canNotMultiplexConnection);
	}

	public function serverIsOverloaded()
	{
		return ($this->protocolStatus === self::serverIsOverloaded);
	}

	public function roleIsUnknown()
	{
		return ($this->protocolStatus === self::unknownRole);
	}
}
