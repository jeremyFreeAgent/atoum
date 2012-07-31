<?php

namespace mageekguy\atoum\fcgi;

use
	mageekguy\atoum,
	mageekguy\atoum\exceptions
;

abstract class record implements \countable
{
	const version = 1;
	const maxContentLength = 65535;

	protected $type = 0;
	protected $requestId = 0;
	protected $contentData = '';

	public function __construct($type, $requestId = '0', $contentData = '')
	{
		if ($type < -128 || $type > 127)
		{
			throw new exceptions\logic\invalidArgument('Type must be greater than or equal to -128 and less than or equal to 127');
		}

		if (strlen($requestId) > 65535)
		{
			throw new exceptions\logic\invalidArgument('Request ID length must be less than or equal to 65535');
		}

		$this
			->setRequestId($requestId)
			->setContentData($contentData)
			->type = (string) $type
		;
	}

	public function count()
	{
		return strlen($this->contentData);
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

	protected function setRequestId($requestId)
	{
		$this->requestId = (string) $requestId;

		return $this;
	}

	protected function setContentData($contentData)
	{
		$this->contentData = (string) $contentData;

		return $this;
	}
}
