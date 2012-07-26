<?php

namespace mageekguy\atoum\fpm\records\streams;

use
	mageekguy\atoum\fpm
;

class stdin extends fpm\records\stream
{
	const type = 5;

	public function __construct($stdin, $requestId = 1)
	{
		parent::__construct(self::type, $stdin, $requestId);
	}
}
