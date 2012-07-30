<?php

namespace mageekguy\atoum\fpm;

class request
{
	protected $params = array();
	protected $stdin = '';

	public function __set($name, $value)
	{
		switch ($name = self::cleanParamName($name))
		{
			case 'AUTH_TYPE':
			case 'CONTENT_LENGTH':
			case 'CONTENT_TYPE':
			case 'GATEWAY_INTERFACE':
			case 'HTTP_*':
			case 'PATH_INFO':
			case 'PATH_TRANSLATED':
			case 'QUERY_STRING':
			case 'REMOTE_ADDR':
			case 'REMOTE_HOST':
			case 'REMOTE_IDENT':
			case 'REMOTE_USER':
			case 'REQUEST_METHOD':
			case 'SCRIPT_NAME':
			case 'SCRIPT_FILENAME':
			case 'SERVER_NAME':
			case 'SERVER_PORT':
			case 'SERVER_PROTOCOL':
			case 'SERVER_SOFTWARE':
				$this->params[$name] = trim($value);
				break;

			case 'STDIN':
				$this->setStdin($value);
				break;
		}

		return $this;
	}

	public function __get($name)
	{
		$name = self::cleanParamName($name);

		return ($name == 'STDIN' ? $this->stdin : (isset($this->params[$name]) === false ? null : $this->params[$name]));
	}

	public function __isset($name)
	{
		$name = self::cleanParamName($name);

		return ($name == 'STDIN' ? $this->stdin != '' : (isset($this->params[self::cleanParamName($name)]) === true));
	}

	public function __unset($name)
	{
		$name = self::cleanParamName($name);

		if ($name == 'STDIN')
		{
			$this->stdin = '';
		}
		else if (isset($this->params[$name = self::cleanParamName($name)]) === true)
		{
			unset($this->params[$name]);
		}

		return $this;
	}

	public function __invoke(client $client)
	{
		return $this->sendWithClient($client);
	}

	public function setStdin($stdin)
	{
		$this->stdin = (string) $stdin;

		return $this;
	}

	public function sendWithClient(client $client)
	{
		$data = (string) new records\begin();

		if (sizeof($this->params) > 0)
		{
			$data .= new records\params($this->params);
			$data .= new records\params();
		}

		if ($this->stdin != '')
		{
			$data .= new records\stdin($this->stdin);
			$data .= new records\stdin();
		}

		$response = new response();

		return $response($client->sendData($data));
	}

	private static function cleanParamName($name)
	{
		return strtoupper(trim($name));
	}
}
