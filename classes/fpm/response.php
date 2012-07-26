<?php

namespace mageekguy\atoum\fpm;

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

	public static function getFromClient(client $client)
	{
		$response = new self();

		do
		{
			if (($data = $client->receiveData(8)) != '')
			{
				$length = (ord($data[4]) << 8) + ord($data[5]);
				$padding = ord($data[6]);
				$content = $client->receiveData($length + $padding);

				switch ($type = ord($data[1]))
				{
					case records\streams\stdout::type:
						$record = new records\streams\stdout($content, ord($data[2] << 8) + ord($data[3]));
						$response->output .= $record->getContentData();
						break;

					case records\streams\stderr::type:
						$record = new records\streams\stderr($content, ord($data[2] << 8) + ord($data[3]));
						$response->errors .= $record->getContentData();
						break;

					case records\end::type:
						$record = new records\end(substr($data, 9), ord($data[2] << 8) + ord($data[3]));
						break;

					default:
						throw new response\exception('Type \'' . $type . '\' is unknown');
				}
			}
		}
		while ($record instanceof records\end === false);

		list($headers, $response->output) = explode("\r\n\r\n", $response->output);

		foreach (explode("\r\n", $headers) as $header)
		{
			list($key, $value) = explode(':', $header);

			$response->headers[strtolower(trim($key))] = trim($value);
		}

		return $response;
	}
}
