<?php

namespace mageekguy\atoum\fpm\records;

use
	mageekguy\atoum\fpm
;

class begin extends fpm\record
{
	const responder = 1;
	const authorizer = 2;
	const filter = 3;

	protected $role = 1;
	protected $persistentConnection = 0;

	public function __construct($role = self::responder, $persistentConnection = 0, $requestId = 1)
	{
		parent::__construct(1, $requestId);

		$this->role = $role;
		$this->persistentConnection = (boolean) $persistentConnection;
	}

	public function encode()
	{
		$this->contentData = sprintf('%c%c%c%c%c%c%c%c', ($this->role >> 8) & 0xff, $this->role & 0xff, $this->persistentConnection, 0, 0, 0, 0, 0);

		return parent::encode();
	}
}
