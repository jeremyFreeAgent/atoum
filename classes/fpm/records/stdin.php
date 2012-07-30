<?php

namespace mageekguy\atoum\fpm\records;

use
	mageekguy\atoum\fpm
;

class stdin extends fpm\record
{
	const type = '5';

	public function __construct($stdin = '', $requestId = 1)
	{
		parent::__construct(self::type, $requestId, $stdin);
	}
}
