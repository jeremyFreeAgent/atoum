<?php

namespace mageekguy\atoum\fcgi\client;

use
	mageekguy\atoum\fcgi
;

interface request
{
	public function sendWithClient(fcgi\client $client);
	public function getResponse();
}
