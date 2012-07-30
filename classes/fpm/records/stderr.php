<?php

namespace mageekguy\atoum\fpm\records;

use
	mageekguy\atoum\fpm
;

class stderr extends fpm\record
{
	const type = '7';

	public function __construct($stderr, $requestId)
	{
		parent::__construct(self::type, $requestId, $stderr);
	}

	public function addToResponse(fpm\response $response)
	{
		return $response->addToErrors($this->getContentData());
	}
}
