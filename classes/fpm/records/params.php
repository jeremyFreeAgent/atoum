<?php

namespace mageekguy\atoum\fpm\records;

use
	mageekguy\atoum\fpm
;

class params extends fpm\record
{
	protected $pairs = array();

	public function __construct(array $pairs = array())
	{
		parent::__construct(4);

		foreach ($pairs as $name => $value)
		{
			$this->addPair($name, $value);
		}
	}

	public function addPair($name, $value)
	{
		$this->pairs[trim((string) $name)] = trim((string) $value);

		return $this;
 	}

	public function encode()
	{
		$this->contentData = '';

		foreach($this->pairs as $name => $value)
		{
			$this->contentData .= self::encodeLength($name) . self::encodeLength($value) . $name . $value;
		}

		return ($this->contentData = '' ? '' : parent::encode());
	}

	protected static function encodeLength($string)
	{
		$length = strlen($string);

		return ($length < 0x80 ? sprintf('%c', $length) : sprintf('%c%c%c%c', ($length >> 24) | 0x80, ($length >> 16) & 0xff, ($length >> 8) & 0xff, $length & 0xff));
	}
}
