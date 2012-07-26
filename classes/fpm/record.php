<?php

namespace mageekguy\atoum\fpm;

abstract class record
{
	const version = 1;

	protected $type = '';
	protected $requestId = '';
	protected $contentData = '';

	public function __construct($type, $requestId = 1, $contentData = '')
	{
		$this->type = $type;
		$this->requestId = 1;
		$this->contentData = $contentData;
	}

	public function __toString()
	{
		return $this->encode();
	}

	public function getContentData()
	{
		return $this->contentData;
	}

	public function encode()
	{
		$encodedRecord = '';

		$data = (string) $this->contentData;

		$length = strlen($data);

		while ($length > 65535)
		{
			$encodedData .= $this->encodeData(substr($data, 0, 65534), 65535);
			$data = substr($data, 65535);

			$length = strlen($data);
		}

		$encodedRecord = $this->encodeData($data, $length);

		return $encodedRecord;
	}

	private function encodeData($data, $length)
	{
		return sprintf('%c%c%c%c%c%c%c%c%s%s', self::version, $this->type, ($this->requestId >> 8) & 0xff, $this->requestId & 0xff, ($length >> 8) & 0xff, $length & 0xff, 0, 0, $data, '');
	}
}
