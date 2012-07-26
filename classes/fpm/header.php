<?php

namespace mageekguy\atoum\fpm;

class header
{
	protected $name = '';
	protected $value = '';

	public function __construct($name, $value)
	{
		$this->name = trim((string) $name);
		$this->value = trim((string) $value);
	}

	public function __toString()
	{
		return $this->name . ': ' . $this->value . "\r\n";
	}

	public function encode()
	{
		return self::encodeLength($this->name) . self::encodeLength($this->value) . $this->name . $this->value;
	}

	protected static function encodeLength($string)
	{
		$length = strlen($string);

		return ($length < 0x80 ? chr($length) : chr(($length >> 24) | 0x80) . chr(($length >> 16) & 0xff) . chr(($length >> 8) & 0xff) . chr($length & 0xff));
	}
}
