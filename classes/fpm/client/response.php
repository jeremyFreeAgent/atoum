<?php

namespace mageekguy\atoum\fpm\client;

use
	mageekguy\atoum\fpm
;

class response
{
	protected $headers;
	protected $output;
	protected $errors;

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

	public static function getFromClient(fpm\client $client)
	{
		$response = new self();
		$response->headers = array();
		$response->output = '';
		$response->errors = '';

		do
		{
			$fpmResponse = fpm\response::getFromClient($client);

			switch (true)
			{
				case $fpmResponse->isOutput():
					$response->output .= $fpmResponse->getContent();
					break;

				case $fpmResponse->isError():
					$response->errors .= $fpmResponse->getContent();
			}
		}
		while ($fpmResponse !== null && $fpmResponse->isNotTheLast());

		list($headers, $response->output) = explode("\r\n\r\n", $response->output);

		foreach (explode("\r\n", $headers) as $header)
		{
			list($key, $value) = explode(':', $header);

			$response->headers[strtolower(trim($key))] = trim($value);
		}

		return $response;
	}
}
