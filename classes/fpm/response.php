<?php

namespace mageekguy\atoum\fpm;

class response
{
	protected $version = 0;
	protected $type = '';
	protected $id = '';
	protected $length = 0;
	protected $padding = 0;
	protected $reserver = 0;
	protected $content = '';

	public function isOutput()
	{
		return ($this->type == 6);
	}

	public function isError()
	{
		return ($this->type == 7);
	}

	public function isNotTheLast()
	{
		return ($this->type != 3);
	}

	public function getContent()
	{
		return $this->content;
	}

	public static function getFromClient(client $client)
	{
		$response = null;

		if (($data = $client->receiveData(8)) != '')
		{
			$length = (ord($data[4]) << 8) + ord($data[5]);
			$padding = ord($data[6]);
			$content = $client->receiveData($length + $padding);

			switch (ord($content[4]))
			{
				case 1:
					throw new response\exception('Server does not support multiplexing');

				case 2:
					throw new response\exception('Server is too buzy');

				case 3:
					throw new response\exception('Role is unknown');
			}

			$response = new self();
			$response->version = ord($data[0]);
			$response->type = ord($data[1]);
			$response->id = ord($data[2] << 8) + ord($data[3]);
			$response->reserver = ord($data[7]);
			$response->length = $length;
			$response->padding = $padding;
			$response->content = $content;
		}

		return $response;
	}
}
