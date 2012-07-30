<?php

namespace mageekguy\atoum\fpm\records;

use
	mageekguy\atoum\fpm
;

class end extends fpm\record
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
