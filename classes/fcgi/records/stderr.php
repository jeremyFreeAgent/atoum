<?php

namespace mageekguy\atoum\fcgi\records;

use
	mageekguy\atoum\fcgi
;

class stderr extends fcgi\records\response
{
	const type = '7';

	public function __construct($stderr, $requestId)
	{
		parent::__construct(self::type, $requestId, $stderr);
	}

	public function addToResponse(fcgi\response $response)
	{
		return $response->addToErrors($this->getContentData());
	}
}
