<?php

namespace mageekguy\atoum\fcgi\records\requests;

use
	mageekguy\atoum\fcgi,
	mageekguy\atoum\exceptions
;

class params extends fcgi\records\request
{
	const type = '4';

	protected $values = array();

	public function __construct(array $values = array(), $requestId = 1)
	{
		parent::__construct(self::type, $requestId);

		foreach ($values as $name => $value)
		{
			$this->setValue($name, $value);
		}
	}

	public function __set($name, $value)
	{
		return $this->setValue($name, $value);
	}

	public function __get($name)
	{
		return (isset($this->values[$name = self::cleanValueName($name)]) === false ? null : $this->values[$name]);
	}

	public function __isset($name)
	{
		return (isset($this->values[self::cleanValueName($name)]) === true);
	}

	public function __unset($name)
	{
		if (isset($this->values[$name = self::cleanValueName($name)]) === true)
		{
			unset($this->values[$name]);
		}

		return $this;
	}

	public function count()
	{
		return sizeof($this->values);
	}

	public function getValues()
	{
		return $this->values;
	}

	public function setValue($name, $value)
	{
		$this->values[self::cleanValueName($name)] = trim($value);

		return $this;
 	}

	public function getValue($name)
	{
		return (isset($this->values[$name = self::cleanValueName($name)]) === false ? null : $this->values[$name]);
	}

	public function encode()
	{
		$this->contentData = '';

		foreach($this->values as $name => $value)
		{
			$this->contentData .= self::encodeLength($name) . self::encodeLength($value) . $name . $value;
		}

		return ($this->contentData = '' ? '' : parent::encode());
	}

	protected static function encodeLength($string)
	{
		$length = strlen($string);

		return ($length < 128 ? sprintf('%c', $length) : sprintf('%c%c%c%c', ($length >> 24) | 0x80, ($length >> 16) & 0xff, ($length >> 8) & 0xff, $length & 0xff));
	}

	private static function cleanValueName($name)
	{
		$cleanName = strtoupper(trim($name));

		switch ($cleanName)
		{
			case 'AUTH_TYPE':
			case 'CONTENT_LENGTH':
			case 'CONTENT_TYPE':
			case 'GATEWAY_INTERFACE':
			case 'PATH_INFO':
			case 'PATH_TRANSLATED':
			case 'QUERY_STRING':
			case 'REMOTE_ADDR':
			case 'REMOTE_HOST':
			case 'REMOTE_IDENT':
			case 'REMOTE_USER':
			case 'REQUEST_METHOD':
			case 'SCRIPT_NAME':
			case 'SCRIPT_FILENAME':
			case 'SERVER_NAME':
			case 'SERVER_PORT':
			case 'SERVER_PROTOCOL':
			case 'SERVER_SOFTWARE':
				return $cleanName;

			default:
				throw new exceptions\logic\invalidArgument('Value \'' . $name . '\' is unknown');
		}
	}
}
