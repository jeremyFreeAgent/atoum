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

	public function __invoke(client $client, array $params = array(), $stdin = '')
	{
		foreach ($params as $name => $value)
		{
			$this[$name] = $value;
		}

		return $this->setStdin($stdin)->sendWithClient($client);
	}

	public function setStdin($stdin)
	{
		$this->stdin = (string) $stdin;

		return $this;
	}

	public function sendWithClient(client $client)
	{
		$begin = new records\begin();
		$params = new records\params($this->params);
		$endOfParams = new records\params();
		$stdin = new records\streams\stdin($this->stdin);
		$endOfStdin = new records\streams\stdin('');

		$response = new response();

		return $response($client->sendData($begin . $params . $endOfParams . $stdin . $endOfStdin));
	}

	private static function cleanParamName($name)
	{
		return strtoupper(trim($name));
	}
}
