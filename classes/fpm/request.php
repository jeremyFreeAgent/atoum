<?php

namespace mageekguy\atoum\fpm;

class request
{
	protected $headers = array();
	protected $content = '';

	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'content':
				$this->content = (string) $value;
				break;

			default:
				$this->headers[trim($name)] = trim($value);
		}
	}

	public function __get($name)
	{
		switch ($name)
		{
			case 'content':
				return $this->content;

			default:
				return (isset($this->headers[$name = trim($name)]) === false ? null : $this->headers[$name]);
		}
	}

	public function sendWithClient(client $client)
	{
		$begin = new records\begin();
		$params = new records\params($this->headers);
		$stdin = new records\streams\stdin($this->content);

		return $client->sendData($begin . $params . $stdin);
	}
}
