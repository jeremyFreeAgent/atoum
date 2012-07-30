<?php

namespace mageekguy\atoum\fpm\records;

use
	mageekguy\atoum\fpm,
	mageekguy\atoum\exceptions
;

class begin extends fpm\record
{
	const type = '1';
	const responder = '1';
	const authorizer = '2';
	const filter = '3';

	protected $role = '1';
	protected $persistentConnection = 0;

	public function __construct($role = self::responder, $requestId = 1, $persistentConnection = 0)
	{
		switch ($role)
		{
			case self::responder:
			case self::authorizer:
			case self::filter:
				parent::__construct(self::type, $requestId);

				$this->role = (string) $role;
				$this->persistentConnection = (boolean) $persistentConnection;
				break;

			default:
				throw new exceptions\logic\invalidArgument('Role is invalid');
		}
	}

	public function getRole()
	{
		return $this->role;
	}

	public function connectionIsPersistent()
	{
		return $this->persistentConnection;
	}

	public function encode()
	{
		list($roleB0, $roleB1) = self::encodeValue($this->role);

		$this->contentData = sprintf('%c%c%c%c%c%c%c%c', $roleB0, $roleB1, ($this->persistentConnection ? 1 : 0), 0, 0, 0, 0, 0);

		return parent::encode();
	}
}
