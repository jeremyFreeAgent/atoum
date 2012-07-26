<?php

namespace mageekguy\atoum\fpm;

class request
{
	protected $headers = array();
	protected $content = '';

	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'content':
				$this->content = (string) $value;
				break;

			default:
				$this->headers[trim($name)] = trim($value);
		}
	}

	public function __get($name)
	{
		switch ($name)
		{
			case 'content':
				return $this->content;

			default:
				return (isset($this->headers[$name = trim($name)]) === false ? null : $this->headers[$name]);
		}
	}

	public function encode()
	{
		return self::begin() . $this->encodeHeaders() . $this->encodeContent();
	}

	public function sendWithClient(client $client)
	{
		return $client->sendData($this->encode());
	}

	protected function encodeHeaders()
	{
		$encodedHeaders = '';

		foreach($this->headers as $name => $value)
		{
			$encodedHeaders .= self::encodeLength($name) . self::encodeLength($value) . $name . $value;
		}

		return self::encodeString(4, $encodedHeaders);
	}

	protected function encodeContent()
	{
		return self::encodeString(5, $this->content);
	}

	protected static function begin()
	{
		return self::encodeString(1, sprintf('%c%c%c%c%c%c%c%c', 0, 1, 1, 0, 0, 0, 0, 0));
	}

	protected static function encodeLength($string)
	{
		$length = strlen($string);

		return ($length < 0x80 ? sprintf('%c', $length) : sprintf('%c%c%c%c', ($length >> 24) | 0x80, ($length >> 16) & 0xff, ($length >> 8) & 0xff, $length & 0xff));
	}

	protected static function encodeString($type, $string, $id = 1)
	{
		$length = strlen($string);

		return sprintf('%c%c%c%c%c%c%c%c%s', 1, $type, ($id >> 8) & 0xff, $id & 0xff, ($length >> 8) & 0xff, $length & 0xff, 0, 0, $string);
	}

}
