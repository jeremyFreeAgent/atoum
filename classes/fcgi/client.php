<?php

namespace mageekguy\atoum\fcgi;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi\client
;

class client
{
	protected $servers = array();
	protected $currentServer = null;
	protected $socket = null;
	protected $adapter = null;

	public function __construct(array $servers = array(), atoum\adapter $adapter = null)
	{
		$this->setAdapter($adapter ?: new atoum\adapter());

		foreach ($servers as $url => $timeout)
		{
			$this->addServer($url, $timeout);
		}
	}

	public function __destruct()
	{
		$this->closeConnection();
	}

	public function __toString()
	{
		return (string) $this->currentServer;
	}

	public function __invoke(client\request $request)
	{
		return $request->sendWithClient($this);
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

	public function addServer($url, $timeout)
	{
		$this->servers[(string) $url] = (int) $timeout;

		return $this;
	}

	public function openConnection()
	{
		if ($this->socket === null)
		{
			if ((list($this->currentServer, $timeout) = each($this->servers)) === false)
			{
				reset($this->servers);
			}

			$socket = $this->adapter->invoke('stream_socket_client', array($this->currentServer, & $errorCode, & $errorMessage, $timeout));

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
			throw new client\exception('Unable to send request to \'' . $this . '\'');
		}

		return $this;
	}

	public function receiveData($length)
	{
		return fread($this->socket, $length);
	}
}
