<?php

namespace mageekguy\atoum\fcgi\records;

use
	mageekguy\atoum\fcgi
;

class params extends fcgi\record
{
	const type = '4';

	protected $values = array();

	public function __construct(array $values = array(), $requestId = 1)
	{
		parent::__construct(self::type, $requestId);

		foreach ($values as $name => $value)
		{
			$this->addValue($name, $value);
		}
	}

	public function count()
	{
		return sizeof($this->values);
	}

	public function getValues()
	{
		return $this->values;
	}

	public function addValue($name, $value)
	{
		$this->values[trim((string) $name)] = trim((string) $value);

		return $this;
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

	public static function isRecord($data)
	{
	}

	protected static function encodeLength($string)
	{
		$length = strlen($string);

		return ($length < 128 ? sprintf('%c', $length) : sprintf('%c%c%c%c', ($length >> 24) | 0x80, ($length >> 16) & 0xff, ($length >> 8) & 0xff, $length & 0xff));
	}
}
