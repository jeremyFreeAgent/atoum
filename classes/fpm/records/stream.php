<?php

namespace mageekguy\atoum\fpm\records;

use
	mageekguy\atoum\fpm
;

abstract class stream extends fpm\record
{
	public function __construct($type, $stream, $requestId = 1)
	{
		parent::__construct($type, $requestId, $stream);
	}
}
