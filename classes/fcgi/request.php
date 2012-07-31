<?php

namespace mageekguy\atoum\fcgi;

use
	mageekguy\atoum\fcgi\records\requests
;

class request
{
	protected $params = null;
	protected $stdin = null;

	public function __construct()
	{
		$this->params = new requests\params();
		$this->stdin = new requests\stdin();
	}

	public function __set($name, $value)
	{
		if (strtoupper($name) === 'STDIN')
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
		return (strtoupper($name) === 'STDIN' ? $this->stdin->getContentData() != '' : isset($this->params->{$name}));
	}

	public function __unset($name)
	{
		if (strtoupper($name) === 'STDIN')
		{
			$this->setStdin('');
		}
		else
		{
			unset($this->params->{$name});
		}

		return $this;
	}

	public function __invoke(client $client)
	{
		return $this->sendWithClient($client);
	}

	public function setStdin($stdin)
	{
		$this->stdin->setContentData($stdin);

		return $this;
	}

	public function sendWithClient(client $client)
	{
		$begin = new requests\begin();
		$begin->sendWithClient($client);

		if (sizeof($this->params) > 0)
		{
			$endOfParams = new requests\params();
			$endOfParams->sendWithClient($this->params->sendWithClient($client));
		}

		if (sizeof($this->stdin) > 0)
		{
			$endOfStdin = new requests\stdin();
			$endOfStdin->sendWithClient($this->stdin->sendWithClient($client));
		}

		return new response($client);
	}

	private static function cleanParamName($name)
	{
		return strtoupper(trim($name));
	}
}
