<?php

namespace mageekguy\atoum\fcgi\records;

use
	mageekguy\atoum\exceptions,
	mageekguy\atoum\fcgi,
	mageekguy\atoum\fcgi\records\responses
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
				case responses\stdout::type:
					return new responses\stdout($contentData, $recordProperties['requestId']);

				case responses\stderr::type:
					return new responses\stderr($contentData, $recordProperties['requestId']);

				case responses\end::type:
					return new responses\end($contentData, $recordProperties['requestId']);

				default:
					throw new exceptions\runtime('Type \'' . $recordProperties['type'] . '\' is unknown');
			}
		}

		return null;
	}

	protected static function getProperties($data)
	{
		$properties = null;

		$data = (string) $data;

		if (strlen($data) >= 7)
		{
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
		}

		return $properties;
	}

	protected static function decodeValue($valueB0, $valueB1)
	{
		return (ord($valueB0) << 8) + ord($valueB1);
	}
}
