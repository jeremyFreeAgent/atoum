<?php

namespace mageekguy\atoum\fcgi\records;

use
	mageekguy\atoum\fcgi,
	mageekguy\atoum\exceptions
;

abstract class request extends fcgi\record implements fcgi\client\request
{
	public function __construct($type, $requestId = 1, $contentData = '')
	{
		parent::__construct($type, $requestId, $contentData);
	}

	public function __invoke(fcgi\client $client)
	{
		return $this->sendWithClient($client);
	}

	public function setRequestId($requestId)
	{
		return parent::setRequestId($requestId);
	}

	public function setContentData($contentData)
	{
		return parent::setContentData($contentData);
	}

	public function sendWithClient(fcgi\client $client)
	{
		$contentLength = strlen($this->contentData);

		if ($contentLength > self::maxContentLength)
		{
			throw new exceptions\runtime('Content length must be less than or equal to 65535');
		}

		list($requestIdB0, $requestIdB1) = self::encodeValue($this->requestId);
		list($contentLengthB0, $contentLengthB1) = self::encodeValue($contentLength);

		$client->sendData(sprintf('%c%c%c%c%c%c%c%c%s%s', self::version, $this->type, $requestIdB0, $requestIdB1, $contentLengthB0, $contentLengthB1, 0, 0, $this->contentData, ''));

		return null;
	}

	protected static function encodeValue($value)
	{
		return array(($value >> 8) & 0xff, $value & 0xff);
	}
}
