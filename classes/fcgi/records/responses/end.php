<?php

namespace mageekguy\atoum\fcgi\records\responses;

use
	mageekguy\atoum\fcgi,
	mageekguy\atoum\fcgi\records,
	mageekguy\atoum\fcgi\exceptions
;

class end extends records\response
{
	const type = 3;
	const requestComplete = 0;
	const serverCanNotMultiplexConnection = 1;
	const serverIsOverloaded = 2;
	const serverDoesNotKnowTheRole = 3;

	public function __construct($requestId, $contentData)
	{
		if (strlen($contentData) != 8)
		{
			throw new exceptions\runtime('Content data are invalid');
		}

		parent::__construct(self::type, $requestId, $contentData);

		switch (ord($this->contentData[4]))
		{
			case self::serverCanNotMultiplexConnection:
				throw new exceptions\runtime('Server can not multiplex connection');

			case self::serverIsOverloaded:
				throw new exceptions\runtime('Server is overloaded');

			case self::serverDoesNotKnowTheRole:
				throw new exceptions\runtime('Server does not know the role');
		}
	}

	public function completeResponse(fcgi\response $response)
	{
		return true;
	}
}
