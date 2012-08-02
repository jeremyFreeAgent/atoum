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
	protected $responses = array();

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
		return $this->sendRequest($request);
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

	public function getServers()
	{
		return $this->servers;
	}

	public function sendRequest(client\request $request)
	{
		$request->sendWithClient($this);

		return $this;
	}

	public function receiveResponses()
	{
		$responses = array();

		while (($record = records\response::getFromClient($this)) !== null)
		{
			$requestId = $record->getRequestId();

			if ($this->responses[$requestId]->isCompletedByRecord($record) === true)
			{
				$responses[$requestId] = $this->responses[$requestId];

				unset($this->responses[$requestId]);
			}
		}

		return $responses;
	}

	public function generateRequestId()
	{
		$id = 1;

		while (isset($this->responses[$id]) === true)
		{
			$id++;
		}

		$this->responses[$id] = new response($id);

		return $id;
	}

	public function openConnection()
	{
		if ($this->socket === null)
		{
			if ((list($this->currentServer, $timeout) = each($this->servers)) === false)
			{
				reset($this->servers);
			}

			$socket = $this->adapter->invoke('stream_socket_client', array($this->currentServer, & $errorCode, & $errorMessage, $timeout, STREAM_CLIENT_PERSISTENT|STREAM_CLIENT_CONNECT));

			if ($socket === false)
			{
				throw new client\exception($errorMessage, $errorCode);
			}

			$this->socket = $socket;

			if ($this->adapter->stream_set_blocking($this->socket, 0) === false)
			{
				throw new client\exception('Unable to unset blocking mode');
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
		 return fread($this->openConnection()->socket, $length);
	}
}
