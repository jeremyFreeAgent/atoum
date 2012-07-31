<?php

namespace mageekguy\atoum\fcgi;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi\client
;

class client
{
	protected $host = '';
	protected $port = 0;
	protected $timeout = 30;
	protected $socket = null;
	protected $adapter = null;

	public function __construct($host = '127.0.0.1', $port = 9000, $timeout = 30, atoum\adapter $adapter = null)
	{
		$this->host = (string) $host;
		$this->port = (int) $port;
		$this->timeout = (int) $timeout;

		$this->setAdapter($adapter ?: new atoum\adapter());
	}

	public function __destruct()
	{
		$this->closeConnection();
	}

	public function getHost()
	{
		return $this->host;
	}

	public function getPort()
	{
		return $this->port;
	}

	public function setAdapter(atoum\adapter $adapter)
	{
		$this->adapter = $adapter;

		return $this;
	}

	public function getAdapter()
	{
		return $this->adapter;
	}

	public function openConnection()
	{
		if ($this->socket === null)
		{
			$socket = @$this->adapter->invoke('stream_socket_client', array('tcp://' . $this->host . ':' . $this->port, & $errorCode, & $errorMessage, $this->timeout));

			if ($socket === false)
			{
				throw new client\exception($errorMessage, $errorCode);
			}

			$this->socket = $socket;

			if ($this->adapter->stream_set_blocking($this->socket, 1) === false)
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
			$this->adapter->fclose($this->socket);

			$this->socket = null;
		}

		return $this;
	}

	public function sendData($data)
	{
		if ($this->adapter->fwrite($this->openConnection()->socket, $data) === false)
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
