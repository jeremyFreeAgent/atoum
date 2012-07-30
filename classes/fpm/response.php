<?php

namespace mageekguy\atoum\fpm;

class response
{
	protected $headers = array();
	protected $output = '';
	protected $errors = '';

	public function __invoke(client $client)
	{
		return $this->getFromClient($client)->getOutput();
	}

	public function reset()
	{
		$this->headers = array();
		$this->output = '';
		$this->errors = '';

		return $this;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function getOutput()
	{
		return $this->output;
	}

	public function addToOutput($data)
	{
		$this->output .= $data;

		return $this;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function addToErrors($data)
	{
		$this->errors .= $data;

		return $this;
	}

	public function getFromClient(client $client)
	{
		$this->reset();

		while (($record = record::getFromClient($client)) && $record !== null && $record->isEndOfRequest() === false)
		{
			$record->addToResponse($this);
		}

		switch (true)
		{
			case $record === null:
				break;

			case $record->serverCanNotMultiplexConnection():
				break;

			case $record->serverIsOverloaded():
				break;

			case $record->roleIsUnknown():
				break;

			default:
				if ($this->output !== '')
				{
					list($headers, $this->output) = explode("\r\n\r\n", $this->output);

					foreach (explode("\r\n", $headers) as $header)
					{
						list($key, $value) = explode(':', $header);

						$this->headers[strtolower(trim($key))] = trim($value);
					}
				}
		}

		return $this;
	}
}
