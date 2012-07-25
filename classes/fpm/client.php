<?php

namespace mageekguy\atoum\fpm;

use
	mageekguy\atoum\fpm\client
;

class client
{
	const begin = 1;
	const end = 3;
	const parameters = 4;
	const stdin = 5;
	const stdout = 6;
	const stderr = 7;
	const noMultiplex = 1;
	const overloaded = 2;
	const unknownRole = 3;

	protected $host = '';
	protected $port = 0;
	protected $timeout = 30;
	protected $socket = null;
	protected $headers = array();
	protected $stdout = '';
	protected $stderr = '';

	public function __construct($host, $port, $timeout = 30)
	{
		$this->host = (string) $host;
		$this->port = (int) $port;
		$this->timeout = (int) $timeout;
	}

	public function sendRequest(array $headers, $content = '')
	{
		$this->headers = array();
		$this->stdout = '';
		$this->stderr = '';

		$this->socket = stream_socket_client('tcp://' . $this->host . ':' . $this->port, $errorCode, $errorMessage, $this->timeout);

		if ($this->socket === false)
		{
			throw new client\exception($errorMessage, $errorCode);
		}

		if (stream_set_blocking($this->socket, 1) === false)
		{
			throw new client\exception('Unable to set blocking mode');
		}

		$chr0 = chr(0);
		$chr1 = chr(1);

		$request = self::pack(self::begin, $chr0 . str_repeat($chr1, 2) . str_repeat($chr0, 5)) . self::pack(self::parameters, self::packPairs($headers)) . self::pack(self::stdin, (string) $content);

		if (fwrite($this->socket, $request) === false)
		{
			throw new client\exception('Unable to send request to \'' . $this->host . '\' on port ' . $this->port);
		}

		do
		{
			if (sizeof($response = $this->unpackResponse()) <= 0)
			{
				throw new client\exception('Bad request');
			}

			switch ($response['type'])
			{
				case self::stdout:
					$this->stdout .= $response['content'];
					break;

				case self::stderr:
					$this->stderr .= $response['content'];
					break;
			}
		}
		while ($response['type'] != self::end);

		fclose($this->socket);

		switch(ord($response['content'][4]))
		{
			case self::noMultiplex:
				throw new client\exception('');

			case self::overloaded:
				throw new client\exception('');

			case self::unknownRole:
				throw new client\exception('');
		}

		list($headers, $this->stdout) = explode("\r\n\r\n", $this->stdout);

		foreach (explode("\r\n", $headers) as $header)
		{
			list($key, $value) = explode(':', $header);

			$this->headers[strtolower(trim($key))] = trim($value);
		}

		return $this;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function getStdout()
	{
		return $this->stdout;
	}

	public function getStderr()
	{
		return $this->stderr;
	}

	protected function unpackResponse()
	{
		$response = array();

		if (($pack = fread($this->socket, 8)) != '')
		{
			$response = array(
				'version' => ord($pack[0]),
				'type' => ord($pack[1]),
				'id' => (ord($pack[2]) << 8) + ord($pack[3]),
				'length' => (ord($pack[4]) << 8) + ord($pack[5]),
				'padding' => ord($pack[6]),
				'reserver' => ord($pack[7]),
				'content' => ''
			);

			$length = $response['length'] + $response['padding'];

			if($length > 0)
			{
				$response['content'] = fread($this->socket, $length);
			}
		}

		return $response;
	}

	protected static function pack($type, $content, $id = 1)
	{
		$length = strlen($content);

		return chr(1) .
			chr($type) .
			chr(($id >> 8) & 0xff) .
			chr( $id & 0xff) .
			chr(($length >> 8) & 0xff) .
			chr( $length & 0xff) .
			str_repeat(chr(0), 2) .
			$content;
	}

	protected static function packPairs(array $pairs)
	{
		$pack = '';

		foreach($pairs as $key => $value)
		{
			$pack .= self::packValue($key) . self::packValue($value) . $key . $value;
		}

		return $pack;
	}

	protected static function packValue($value)
	{
		$length = strlen((string) $value);

		return ($length < 0x80 ? chr($length) : chr(($length >> 24) | 0x80) . chr(($length >> 16) & 0xff) . chr(($length >> 8) & 0xff) . chr($length & 0xff));
	}
}
