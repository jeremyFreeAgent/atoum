<?php

namespace mageekguy\atoum\fcgi\records\responses;

use
	mageekguy\atoum\fcgi
;

class stderr extends fcgi\records\response
{
	const type = '7';

	public function __construct($requestId, $stderr)
	{
		parent::__construct(self::type, $requestId, $stderr);
	}

	public function completeResponse(fcgi\response $response)
	{
		parent::completeResponse($response);

		$response->addToStderr($this);

		return false;
	}
}
