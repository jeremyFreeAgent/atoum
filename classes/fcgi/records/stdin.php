<?php

namespace mageekguy\atoum\fcgi\records;

use
	mageekguy\atoum\fcgi
;

class stdin extends fcgi\record
{
	const type = '5';

	public function __construct($stdin = '', $requestId = 1)
	{
		parent::__construct(self::type, $requestId, $stdin);
	}
}
