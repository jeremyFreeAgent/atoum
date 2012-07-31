<?php

namespace mageekguy\atoum\fcgi;

use
	mageekguy\atoum\fcgi\client
;

class client
{
	protected $host = '';
	protected $port = 0;
	protected $timeout = 30;
	protected $socket = null;

	public function __construct($host = '127.0.0.1', $port = 9000, $timeout = 30)
	{
		$this->host = (string) $host;
		$this->port = (int) $port;
		$this->timeout = (int) $timeout;
	}

	public function __destruct()
	{
		$this->closeConnection();
	}

	public function openConnection()
	{
		if ($this->socket === null)
		{
			$this->socket = stream_socket_client('tcp://' . $this->host . ':' . $this->port, $errorCode, $errorMessage, $this->timeout);

			if ($this->socket === false)
			{
				throw new client\exception($errorMessage, $errorCode);
			}

			if (stream_set_blocking($this->socket, 1) === false)
			{
				throw new client\exception('Unable to set blocking mode');
			}
		}

		return $this;
	}

	public function closeConnection()
	{
		if ($this->socket !== null)
		{
			fclose($this->socket);

			$this->socket = null;
		}

		return $this;
	}

	public function sendData($data)
	{
		if (fwrite($this->openConnection()->socket, $data) === false)
		{
			throw new client\exception('Unable to send request to \'' . $this->host . '\' on port ' . $this->port);
		}

		return $this;
	}

	public function receiveData($length)
	{
		return fread($this->socket, $length);
	}
}
