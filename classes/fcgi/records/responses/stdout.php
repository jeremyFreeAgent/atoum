<?php

namespace mageekguy\atoum\fcgi\records\responses;

use
	mageekguy\atoum\fcgi
;

class stdout extends fcgi\records\response
{
	const type = '6';

	public function __construct($stdout, $requestId)
	{
		parent::__construct(self::type, $requestId, $stdout);
	}

	public function addToResponse(fcgi\response $response)
	{
		return $response->addToOutput($this->getContentData());
	}
}
