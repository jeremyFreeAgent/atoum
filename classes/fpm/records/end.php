<?php

namespace mageekguy\atoum\fpm\records;

use
	mageekguy\atoum\fpm
;

class end extends fpm\record
{
	const type = 3;

	protected $protocolStatus = 0;

	public function __construct($contentData, $requestId = 1)
	{
		parent::__construct(self::type, $requestId);

		$this->protocolStatus = ord($contentData[4]);
	}

	public function getProtocolStatus()
	{
		return $this->protocolStatus;
	}
}
