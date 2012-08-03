<?php

namespace mageekguy\atoum\fcgi\requests;

use
	mageekguy\atoum\fcgi
;

class post extends fcgi\request implements \arrayAccess
{
	const contentType = 'application/x-www-form-urlencoded';
	const requestMethod = 'POST';

	public function offsetSet($name, $value)
	{
		$variables = $this->getVariablesFromStdin();

		$variables[$name] = $value;

		return $this->buildStdin($variables);
	}

	public function offsetGet($name)
	{
		$variables = $this->getVariablesFromStdin();

		return (isset($variables[$name]) === false ? null : $variables[$name]);
	}

	public function offsetUnset($name)
	{
		$variables = $this->getVariablesFromStdin();

		if (isset($variables[$name]) === true)
		{
			unset($variables[$name]);
		}

		return $this->buildStdin($variables);
	}

	public function offsetExists($name)
	{
		$variables = $this->getVariablesFromStdin();

		return isset($variables[$name]);
	}

	public function sendWithClient(fcgi\client $client)
	{
		$this->REQUEST_METHOD = self::contentType;
		$this->CONTENT_TYPE = self::requestMethod;

		return parent::sendWithClient($client);
	}

	private function buildStdin(array $variables)
	{
		$this->setStdin(http_build_query($variables, ''))->content_length = sizeof($this->stdin);

		return $this;
	}

	private function getVariablesFromStdin()
	{
		parse_str($this->getStdin(), $variables);

		return $variables;
	}
}
