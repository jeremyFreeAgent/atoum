<?php

namespace mageekguy\atoum\fpm\records\streams;

use
	mageekguy\atoum\fpm
;

class data extends fpm\records\stream
{
	const type = 8;

	public function __construct($data, $requestId = 1)
	{
		parent::__construct(self::type, $data, $requestId);
	}
}
