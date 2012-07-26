<?php

namespace mageekguy\atoum\fpm\records\streams;

use
	mageekguy\atoum\fpm
;

class stderr extends fpm\records\stream
{
	const type = 7;

	public function __construct($stderr, $requestId = 1)
	{
		parent::__construct(self::type, $stderr, $requestId);
	}
}
