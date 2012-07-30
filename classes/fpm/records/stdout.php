<?php

namespace mageekguy\atoum\fpm\records;

use
	mageekguy\atoum\fpm
;

class stdout extends fpm\record
{
	const type = '6';

	public function __construct($stdout, $requestId)
	{
		parent::__construct(self::type, $requestId, $stdout);
	}

	public function addToResponse(fpm\response $response)
	{
		return $response->addToOutput($this->getContentData());
	}
}
