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

	public function __invoke(client\request $request = null)
	{
		return ($request === null ? $this->receiveResponses() : $this->sendRequest($request));
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
		$response = $request->sendWithClient($this)->getResponse();

		if ($response !== null && in_array($response, $this->responses, true) === false)
		{
			$requestId = $response->getRequestId();

			if (isset($this->responses[$requestId]) === true)
			{
				throw new client\exception('Client already wait response for request \'' . $requestId . '\'');
			}

			$this->responses[$requestId] = $response;
		}

		return $this;
	}

	public function receiveResponses()
	{
		$responses = array();

		if (sizeof($this->responses) > 0)
		{
			while (($record = records\response::getFromClient($this)) !== null)
			{
				$requestId = $record->getRequestId();

				if (isset($this->responses[$requestId]) === false)
				{
					throw new client\exception('Request \'' . $requestId . '\' is unknown');
				}

				if ($this->responses[$requestId]->isCompletedByRecord($record) === true)
				{
					$responses[$requestId] = $this->responses[$requestId];

					unset($this->responses[$requestId]);
				}
			}
		}

		return $responses;
	}

	public function getNextRequestId()
	{
		$id = 1;

		while (isset($this->responses[$id]) === true)
		{
			$id++;
		}

		$this->responses[$id] = null;

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
			while (sizeof($this->responses) > 0)
			{
				$this->receiveResponses();
			}

			$this->adapter->fclose($this->socket);

			$this->socket = null;
			$this->currentServer = null;
		}

		return $this;
	}

	public function sendData($data)
	{
		while ($data != '')
		{
			$dataWrited = $this->adapter->fwrite($this->openConnection()->socket, $data);

			if ($dataWrited === false)
			{
				throw new client\exception('Unable to send request to \'' . $this . '\'');
			}

			$data = substr($data, $dataWrited);
		}

		return $this;
	}

	public function receiveData($length)
	{
		if ($this->socket === null)
		{
			throw new client\exception('Unable to receive data because connection is not open');
		}

		return fread($this->socket, $length);
	}
}
