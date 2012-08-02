<?php

namespace mageekguy\atoum\fcgi\records\responses;

use
	mageekguy\atoum\fcgi
;

class stdout extends fcgi\records\response
{
	const type = '6';

	public function __construct($requestId, $stdout)
	{
		parent::__construct(self::type, $requestId, $stdout);
	}

	public function completeResponse(fcgi\response $response)
	{
		parent::completeResponse($response);

		$response->addToStdout($this);

		return false;
	}
}
