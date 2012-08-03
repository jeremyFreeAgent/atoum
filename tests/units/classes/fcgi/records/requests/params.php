<?php

namespace mageekguy\atoum\tests\units\fcgi\records\requests;

use
	mageekguy\atoum,
	mageekguy\atoum\fcgi,
	mageekguy\atoum\fcgi\records\requests\params as testedClass
;

require_once __DIR__ . '/../../../../runner.php';

class params extends atoum\test
{
	public function testClassConstants()
	{
		$this
			->string(testedClass::type)->isEqualTo(4)
		;
	}

	public function testClass()
	{
		$this
			->testedClass->isSubClassOf('mageekguy\atoum\fcgi\records\request')
		;
	}

	public function test__construct()
	{
		$this
			->if($record = new testedClass())
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->string($record->getRequestId())->isEqualTo('1')
				->array($record->getValues())->isEmpty()
				->sizeOf($record)->isZero()
			->if($record = new testedClass($values = array('CONTENT_LENGTH' => uniqid())))
			->then
				->string($record->getType())->isEqualTo(testedClass::type)
				->string($record->getRequestId())->isEqualTo('1')
				->array($record->getValues())->isEqualTo($values)
				->sizeOf($record)->isEqualTo(sizeof($values))
		;
	}

	public function test__set()
	{
		$this
			->if($record = new testedClass())
			->and($record->CONTENT_LENGTH = $value = uniqid())
			->then
				->array($record->getValues())->isEqualTo(array('CONTENT_LENGTH' => $value))
			->if($record->CONTENT_LENGTH = $otherValue = uniqid())
			->then
				->array($record->getValues())->isEqualTo(array('CONTENT_LENGTH' => $otherValue))
			->if($record->PATH_INFO = $value)
			->then
				->array($record->getValues())->isEqualTo(array('CONTENT_LENGTH' => $otherValue, 'PATH_INFO' => $value))
		;
	}

	public function test__get()
	{
		$this
			->if($record = new testedClass())
			->then
				->variable($record->CONTENT_LENGTH)->isNull()
			->if($record->CONTENT_LENGTH = $value = uniqid())
			->then
				->string($record->CONTENT_LENGTH)->isEqualTo($value)
		;
	}

	public function test__isset()
	{
		$this
			->if($record = new testedClass())
			->then
				->boolean(isset($record->PATH_TRANSLATED))->isFalse()
			->if($record->PATH_TRANSLATED = uniqid())
			->then
				->boolean(isset($record->PATH_TRANSLATED))->isTrue()
		;
	}

	public function test__unset()
	{
		$this
			->if($record = new testedClass())
			->when(function() use ($record) { unset($record->PATH_TRANSLATED); })
			->then
				->boolean(isset($record->PATH_TRANSLATED))->isFalse()
			->if($record->PATH_TRANSLATED = uniqid())
			->when(function() use ($record) { unset($record->PATH_TRANSLATED); })
			->then
				->boolean(isset($record->PATH_TRANSLATED))->isFalse()
		;
	}

	public function testSetValue()
	{
		$this
			->if($record = new testedClass())
			->then
				->object($record->setValue('AUTH_TYPE', $AUTH_TYPE = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('AUTH_TYPE'))->isEqualTo($AUTH_TYPE)
				->object($record->setValue('CONTENT_LENGTH', $CONTENT_LENGTH = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('CONTENT_LENGTH'))->isEqualTo($CONTENT_LENGTH)
				->object($record->setValue('CONTENT_TYPE', $CONTENT_TYPE = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('CONTENT_TYPE'))->isEqualTo($CONTENT_TYPE)
				->object($record->setValue('GATEWAY_INTERFACE', $GATEWAY_INTERFACE = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('GATEWAY_INTERFACE'))->isEqualTo($GATEWAY_INTERFACE)
				->object($record->setValue('PATH_INFO', $PATH_INFO = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('PATH_INFO'))->isEqualTo($PATH_INFO)
				->object($record->setValue('PATH_TRANSLATED', $PATH_TRANSLATED = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('PATH_TRANSLATED'))->isEqualTo($PATH_TRANSLATED)
				->object($record->setValue('QUERY_STRING', $QUERY_STRING = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('QUERY_STRING'))->isEqualTo($QUERY_STRING)
				->object($record->setValue('REMOTE_ADDR', $REMOTE_ADDR = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('REMOTE_ADDR'))->isEqualTo($REMOTE_ADDR)
				->object($record->setValue('REMOTE_HOST', $REMOTE_HOST = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('REMOTE_HOST'))->isEqualTo($REMOTE_HOST)
				->object($record->setValue('REMOTE_IDENT', $REMOTE_IDENT = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('REMOTE_IDENT'))->isEqualTo($REMOTE_IDENT)
				->object($record->setValue('REMOTE_USER', $REMOTE_USER = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('REMOTE_USER'))->isEqualTo($REMOTE_USER)
				->object($record->setValue('REQUEST_METHOD', $REQUEST_METHOD = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('REQUEST_METHOD'))->isEqualTo($REQUEST_METHOD)
				->object($record->setValue('SCRIPT_NAME', $SCRIPT_NAME = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('SCRIPT_NAME'))->isEqualTo($SCRIPT_NAME)
				->object($record->setValue('SCRIPT_FILENAME', $SCRIPT_FILENAME = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('SCRIPT_FILENAME'))->isEqualTo($SCRIPT_FILENAME)
				->object($record->setValue('SERVER_NAME', $SERVER_NAME = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('SERVER_NAME'))->isEqualTo($SERVER_NAME)
				->object($record->setValue('SERVER_PORT', $SERVER_PORT = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('SERVER_PORT'))->isEqualTo($SERVER_PORT)
				->object($record->setValue('SERVER_PROTOCOL', $SERVER_PROTOCOL = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('SERVER_PROTOCOL'))->isEqualTo($SERVER_PROTOCOL)
				->object($record->setValue('SERVER_SOFTWARE', $SERVER_SOFTWARE = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('SERVER_SOFTWARE'))->isEqualTo($SERVER_SOFTWARE)
				->array($record->getValues())->isEqualTo(array(
						'AUTH_TYPE' => $AUTH_TYPE,
						'CONTENT_LENGTH' => $CONTENT_LENGTH,
						'CONTENT_TYPE' => $CONTENT_TYPE,
						'GATEWAY_INTERFACE' => $GATEWAY_INTERFACE,
						'PATH_INFO' => $PATH_INFO,
						'PATH_TRANSLATED' => $PATH_TRANSLATED,
						'QUERY_STRING' => $QUERY_STRING,
						'REMOTE_ADDR' => $REMOTE_ADDR,
						'REMOTE_HOST' => $REMOTE_HOST,
						'REMOTE_IDENT' => $REMOTE_IDENT,
						'REMOTE_USER' => $REMOTE_USER,
						'REQUEST_METHOD' => $REQUEST_METHOD,
						'SCRIPT_NAME' => $SCRIPT_NAME,
						'SCRIPT_FILENAME' => $SCRIPT_FILENAME,
						'SERVER_NAME' => $SERVER_NAME,
						'SERVER_PORT' => $SERVER_PORT,
						'SERVER_PROTOCOL' => $SERVER_PROTOCOL,
						'SERVER_SOFTWARE' => $SERVER_SOFTWARE
					)
				)
				->object($record->setValue('auth_type', $AUTH_TYPE = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('auth_type'))->isEqualTo($AUTH_TYPE)
				->object($record->setValue('content_length', $CONTENT_LENGTH = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('content_length'))->isEqualTo($CONTENT_LENGTH)
				->object($record->setValue('content_type', $CONTENT_TYPE = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('content_type'))->isEqualTo($CONTENT_TYPE)
				->object($record->setValue('gateway_interface', $GATEWAY_INTERFACE = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('gateway_interface'))->isEqualTo($GATEWAY_INTERFACE)
				->object($record->setValue('path_info', $PATH_INFO = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('path_info'))->isEqualTo($PATH_INFO)
				->object($record->setValue('path_translated', $PATH_TRANSLATED = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('path_translated'))->isEqualTo($PATH_TRANSLATED)
				->object($record->setValue('query_string', $QUERY_STRING = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('query_string'))->isEqualTo($QUERY_STRING)
				->object($record->setValue('remote_addr', $REMOTE_ADDR = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('remote_addr'))->isEqualTo($REMOTE_ADDR)
				->object($record->setValue('remote_host', $REMOTE_HOST = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('remote_host'))->isEqualTo($REMOTE_HOST)
				->object($record->setValue('remote_ident', $REMOTE_IDENT = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('remote_ident'))->isEqualTo($REMOTE_IDENT)
				->object($record->setValue('remote_user', $REMOTE_USER = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('remote_user'))->isEqualTo($REMOTE_USER)
				->object($record->setValue('request_method', $REQUEST_METHOD = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('request_method'))->isEqualTo($REQUEST_METHOD)
				->object($record->setValue('script_name', $SCRIPT_NAME = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('script_name'))->isEqualTo($SCRIPT_NAME)
				->object($record->setValue('script_filename', $SCRIPT_FILENAME = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('script_filename'))->isEqualTo($SCRIPT_FILENAME)
				->object($record->setValue('server_name', $SERVER_NAME = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('server_name'))->isEqualTo($SERVER_NAME)
				->object($record->setValue('server_port', $SERVER_PORT = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('server_port'))->isEqualTo($SERVER_PORT)
				->object($record->setValue('server_protocol', $SERVER_PROTOCOL = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('server_protocol'))->isEqualTo($SERVER_PROTOCOL)
				->object($record->setValue('server_software', $SERVER_SOFTWARE = uniqid()))->isIdenticalTo($record)
				->string($record->getValue('server_software'))->isEqualTo($SERVER_SOFTWARE)
				->array($record->getValues())->isEqualTo(array(
						'AUTH_TYPE' => $AUTH_TYPE,
						'CONTENT_LENGTH' => $CONTENT_LENGTH,
						'CONTENT_TYPE' => $CONTENT_TYPE,
						'GATEWAY_INTERFACE' => $GATEWAY_INTERFACE,
						'PATH_INFO' => $PATH_INFO,
						'PATH_TRANSLATED' => $PATH_TRANSLATED,
						'QUERY_STRING' => $QUERY_STRING,
						'REMOTE_ADDR' => $REMOTE_ADDR,
						'REMOTE_HOST' => $REMOTE_HOST,
						'REMOTE_IDENT' => $REMOTE_IDENT,
						'REMOTE_USER' => $REMOTE_USER,
						'REQUEST_METHOD' => $REQUEST_METHOD,
						'SCRIPT_NAME' => $SCRIPT_NAME,
						'SCRIPT_FILENAME' => $SCRIPT_FILENAME,
						'SERVER_NAME' => $SERVER_NAME,
						'SERVER_PORT' => $SERVER_PORT,
						'SERVER_PROTOCOL' => $SERVER_PROTOCOL,
						'SERVER_SOFTWARE' => $SERVER_SOFTWARE
					)
				)
			->exception(function() use ($record, & $name) { $record->setValue($name = uniqid(), uniqid()); })
				->isInstanceof('mageekguy\atoum\exceptions\logic\invalidArgument')
				->hasMessage('Value \'' . $name . '\' is unknown')
		;
	}

	public function testGetValue()
	{
		$this
			->if($record = new testedClass())
			->then
				->variable($record->getValue('CONTENT_LENGTH'))->isNull()
				->variable($record->getValue('content_length'))->isNull()
			->if($record->CONTENT_LENGTH = $value = uniqid())
			->then
				->variable($record->getValue('CONTENT_LENGTH'))->isEqualTo($value)
				->variable($record->getValue('content_length'))->isEqualTo($value)
		;
	}

	public function testValueIsSet()
	{
		$this
			->if($record = new testedClass())
			->then
				->boolean($record->valueIsSet('PATH_TRANSLATED'))->isFalse()
				->boolean($record->valueIsSet('path_translated'))->isFalse()
			->if($record->PATH_TRANSLATED = uniqid())
			->then
				->boolean($record->valueIsSet('PATH_TRANSLATED'))->isTrue()
				->boolean($record->valueIsSet('path_translated'))->isTrue()
		;
	}

	public function testUnsetValue()
	{
		$this
			->if($record = new testedClass())
			->then
				->object($record->unsetValue('PATH_TRANSLATED'))->isIdenticalTo($record)
				->boolean($record->valueIsSet('PATH_TRANSLATED'))->isFalse()
				->object($record->unsetValue('path_translated'))->isIdenticalTo($record)
				->boolean($record->valueIsSet('path_translated'))->isFalse()
			->if($record->PATH_TRANSLATED = uniqid())
			->then
				->object($record->unsetValue('PATH_TRANSLATED'))->isIdenticalTo($record)
				->boolean($record->valueIsSet('PATH_TRANSLATED'))->isFalse()
			->if($record->PATH_TRANSLATED = uniqid())
				->object($record->unsetValue('path_translated'))->isIdenticalTo($record)
				->boolean($record->valueIsSet('path_translated'))->isFalse()
		;
	}

	public function testCount()
	{
		$this
			->if($record = new testedClass())
			->then
				->sizeOf($record)->isZero()
			->if($record->setValue('CONTENT_LENGTH', uniqid()))
			->then
				->sizeOf($record)->isEqualTo(1)
			->if($record->setValue('PATH_INFO', uniqid()))
			->then
				->sizeOf($record)->isEqualTo(2)
		;
	}

	public function testGetValues()
	{
		$this
			->if($record = new testedClass())
			->then
				->object($record->setValue($name = 'CONTENT_LENGTH', $value = uniqid()))->isIdenticalTo($record)
				->array($record->getValues())->isEqualTo(array($name => $value))
				->object($record->setValue($name, $otherValue = uniqid()))->isIdenticalTo($record)
				->array($record->getValues())->isEqualTo(array($name => $otherValue))
				->object($record->setValue($otherName = 'PATH_INFO', $value))->isIdenticalTo($record)
				->array($record->getValues())->isEqualTo(array($name => $otherValue, $otherName => $value))
		;
	}

	public function testSendWithClient()
	{
		$this
			->if($record = new testedClass())
			->and
				->mockGenerator->shunt('sendData')
			->and($client = new \mock\mageekguy\atoum\fcgi\client())
			->and($client->getMockController()->sendData = $client)
			->then
				->object($record->sendWithClient($client))->isIdenticalTo($record)
				->mock($client)->call('sendData')->withArguments("\001\004\000\001\000\000\000\000")->once()
			->if($record->setValue($name = 'CONTENT_LENGTH', $value = uniqid()))
			->then
				->object($record->sendWithClient($client))->isIdenticalTo($record)
				->mock($client)->call('sendData')->withArguments("\001\004\000\001\000\035\000\000\016\r" . $name . $value)->once()
			->if($record->setValue($otherName = 'PATH_INFO', $otherValue = uniqid()))
			->then
				->object($record->sendWithClient($client))->isIdenticalTo($record)
				->mock($client)->call('sendData')->withArguments("\001\004\000\001\0005\000\000\016\r" . $name . $value . "\t\r" . $otherName . $otherValue)->once()
		;
	}
}
