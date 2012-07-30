<?php

namespace mageekguy\atoum\fcgi\records;

use
	mageekguy\atoum\fcgi
;

abstract class response extends fcgi\record
{
	public function addToResponse(fcgi\response $response)
	{
		return $response;
	}

	public function isEndOfRequest()
	{
		return false;
	}

	public static function getFromClient(fcgi\client $client)
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
				case stdout::type:
					return new stdout($contentData, $recordProperties['requestId']);

				case stderr::type:
					return new stderr($contentData, $recordProperties['requestId']);

				case end::type:
					return new end($contentData, $recordProperties['requestId']);

				default:
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

	protected static function decodeValue($valueB0, $valueB1)
	{
		return (ord($valueB0) << 8) + ord($valueB1);
	}
}
