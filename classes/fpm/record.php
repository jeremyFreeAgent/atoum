<?php

namespace mageekguy\atoum\fpm;

use
	mageekguy\atoum\exceptions
;

abstract class record
{
	const version = 1;
	const maxContentLength = 65535;

	protected $type = 0;
	protected $requestId = 0;
	protected $contentData = '';

	public function __construct($type, $requestId = 0, $contentData = '')
	{
		$type = (int) $type;

		if ($type < -128 || $type > 127)
		{
			throw new exceptions\logic\invalidArgument('Type must be greater than or equal to -128 and less than or equal to 127');
		}

		if (strlen($requestId) > 65535)
		{
			throw new exceptions\logic\invalidArgument('Request ID length must be less than or equal to 65535');
		}

		$this->type = $type;
		$this->requestId = (int) $requestId;
		$this->contentData = (string) $contentData;
	}

	public function __toString()
	{
		return $this->encode();
	}

	public function getType()
	{
		return $this->type;
	}

	public function getRequestId()
	{
		return $this->requestId;
	}

	public function getContentData()
	{
		return $this->contentData;
	}

	public function encode()
	{
		$contentLength = strlen($this->contentData);

		if ($contentLength > self::maxContentLength)
		{
			throw new exceptions\runtime('Content length must be less than or equal to 65535');
		}

		list($requestIdB0, $requestIdB1) = self::encodeValue($this->requestId);
		list($contentLengthB0, $contentLengthB1) = self::encodeValue($contentLength);

		return sprintf('%c%c%c%c%c%c%c%c%s%s', self::version, $this->type, $requestIdB0, $requestIdB1, $contentLengthB0, $contentLengthB1, 0, 0, $this->contentData, '');
	}

	public static function getFromClient(client $client)
	{
		$recordProperties = self::getProperties($client->receiveData(8));

		if ($recordProperties !== null)
		{
			if (isset($recordProperties['contentLength']) === true)
			{
				$contentData = $client->receiveData($recordProperties['contentLength'] + $recordProperties['paddingLength']);

				if ($recordProperties['paddingLength'] > 0)
				{
					$contentData = substr($contentData, 0, - $recordProperties['paddingLength']);
				}
			}

			switch ($recordProperties['type'])
			{
				case records\streams\stdout::type:
					return new records\streams\stdout($contentData, $recordProperties['requestId']);

				case records\streams\stderr::type:
					return new records\streams\stderr($contentData, $recordProperties['requestId']);

				case records\end::type:
					return new records\end($contentData, $recordProperties['requestId']);
			}
		}

		return null;
	}

	protected static function getProperties($data)
	{
		$properties = null;

		if (ord($data[0]) == self::version)
		{
			$properties = array(
				'type' => ord($data[1]),
				'requestId' => self::decodeValue($data[2], $data[3])
			);

			$contentLength = self::decodeValue($data[4], $data[5]);

			if ($contentLength > 0)
			{
				$properties['contentLength'] = $contentLength;
				$properties['paddingLength'] = ord($data[6]);
			}
		}

		return $properties;
	}

	protected static function encodeValue($value)
	{
		return array(($value >> 8) & 0xff, $value & 0xff);
	}

	protected static function decodeValue($valueB0, $valueB1)
	{
		return (ord($valueB0) << 8) + ord($valueB1);
	}
}
