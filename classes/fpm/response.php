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

	public function getErrors()
	{
		return $this->errors;
	}

	public function getFromClient(client $client)
	{
		$this->reset();

		while (($record = record::getFromClient($client)) !== null)
		{
			switch ($record->getType())
			{
				case records\streams\stdout::type:
					$this->output .= $record->getContentData();
					break;

				case records\streams\stderr::type:
					$this->errors .= $record->getContentData();
					break;

				case records\end::type:
					break 2;
			}
		}

		if ($this->output !== '')
		{
			list($headers, $this->output) = explode("\r\n\r\n", $this->output);

			foreach (explode("\r\n", $headers) as $header)
			{
				list($key, $value) = explode(':', $header);

				$this->headers[strtolower(trim($key))] = trim($value);
			}
		}

		return $this;
	}
}
