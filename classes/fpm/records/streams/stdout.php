<?php

namespace mageekguy\atoum\fpm\records\streams;

use
	mageekguy\atoum\fpm
;

class stdout extends fpm\records\stream
{
	const type = 6;

	public function __construct($stdout, $requestId = 1)
	{
		parent::__construct(self::type, $stdout, $requestId);
	}
}
