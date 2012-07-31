<?php

namespace mageekguy\atoum\fcgi;

use
	mageekguy\atoum\fcgi\records\requests
;

class request implements client\request
{
	protected $requestId = 1;
	protected $persistentConnection = false;
	protected $params = null;
	protected $stdin = null;

	public function __construct($requestId = 1, $persistentConnection = false)
	{
		$this->requestId = $requestId;
		$this->persistentConnection = $persistentConnection;
		$this->params = new requests\params();
		$this->stdin = new requests\stdin();
	}

	public function __set($name, $value)
	{
		if (self::cleanName($name) === 'STDIN')
		{
			$this->setStdin($value);
		}
		else
		{
			$this->params->{$name} = $value;
		}

		return $this;
	}

	public function __get($name)
	{
		return (strtoupper($name) === 'STDIN' ? $this->stdin->getContentData() : $this->params->{$name});
	}

	public function __isset($name)
	{
		return (self::cleanName($name) === 'STDIN' ? sizeof($this->stdin) > 0 : isset($this->params->{$name}));
	}

	public function __unset($name)
	{
		if (self::cleanName($name) === 'STDIN')
		{
			$this->setStdin('');
		}
		else
		{
			unset($this->params->{$name});
		}

		return $this;
	}

	public function __invoke(client $client, $requestId = 1)
	{
		return $this->sendWithClient($client, $requestId);
	}

	public function setRequestId($requestId)
	{
		$this->requestId = $requestId;

		return $this;
	}

	public function getRequestId()
	{
		return $this->requestId;
	}

	public function setStdin($stdin)
	{
		$this->stdin->setContentData($stdin);

		return $this;
	}

	public function getStdin()
	{
		return $this->stdin->getContentData();
	}

	public function getParams()
	{
		return $this->params->getValues();
	}

	public function connectionIsPersistent()
	{
		$this->persistentConnection = true;

		return $this;
	}

	public function sendWithClient(client $client)
	{
		$response = null;

		if (sizeof($this->params) > 0 || sizeof($this->stdin) > 0)
		{
			$client(new requests\begin(1, $this->requestId, $this->persistentConnection));

			if (sizeof($this->params) > 0)
			{
				$client($this->params->setRequestId($this->requestId));
				$client(new requests\params(array(), $this->requestId));
			}

			if (sizeof($this->stdin) > 0)
			{
				$client($this->stdin->setRequestId($this->requestId));
				$client(new requests\stdin('', $this->requestId));
			}

			$response = new response($client);
		}

		return $response;
	}

	private static function cleanName($name)
	{
		return strtoupper(trim($name));
	}
}
