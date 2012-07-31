<?php

namespace mageekguy\atoum\fcgi;

use
	mageekguy\atoum\exceptions,
	mageekguy\atoum\fcgi\records
;

class response
{
	protected $headers = array();
	protected $output = '';
	protected $errors = '';

	public function __construct(client $client = null)
	{
		if ($client !== null)
		{
			$this($client);
		}
	}

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

		while (($record = records\response::getFromClient($client)) && $record !== null && $record->isEndOfRequest() === false)
		{
			$record->addToResponse($this);
		}

		switch (true)
		{
			case $record === null:
				throw new exceptions\runtime('Unable to get data from server \'' . $client . '\'');

			case $record->serverCanNotMultiplexConnection():
				throw new exceptions\runtime('Server \'' . $client . '\' can not multiplex connection');

			case $record->serverIsOverloaded():
				throw new exceptions\runtime('Server \'' . $client . '\' is overloaded');

			case $record->roleIsUnknown():
				throw new exceptions\runtime('Role is unknown for server \'' . $client . '\'');

			default:
				if ($this->output !== '')
				{
					list($headers, $this->output) = explode("\r\n\r\n", $this->output);

					foreach (explode("\r\n", $headers) as $header)
					{
						list($key, $value) = explode(':', $header);

						$this->headers[trim($key)] = trim($value);
					}
				}
		}

		return $this;
	}
}
