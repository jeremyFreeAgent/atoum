<?php

namespace mageekguy\atoum\report\fields\runner;

use
	mageekguy\atoum\dependencies,
	mageekguy\atoum\observable,
	mageekguy\atoum\runner,
	mageekguy\atoum\report
;

abstract class result extends report\field
{
	protected $testNumber = null;
	protected $testMethodNumber = null;
	protected $assertionNumber = null;
	protected $failNumber = null;
	protected $errorNumber = null;
	protected $exceptionNumber = null;
	protected $uncompletedMethodNumber = null;

	public function __construct(dependencies $dependencies = null)
	{
		parent::__construct(array(runner::runStop), $dependencies);
	}

	public function getTestNumber()
	{
		return $this->testNumber;
	}

	public function getTestMethodNumber()
	{
		return $this->testMethodNumber;
	}

	public function getAssertionNumber()
	{
		return $this->assertionNumber;
	}

	public function getFailNumber()
	{
		return $this->failNumber;
	}

	public function getErrorNumber()
	{
		return $this->errorNumber;
	}

	public function getExceptionNumber()
	{
		return $this->exceptionNumber;
	}

	public function getUncompletedMethodNumber()
	{
		return $this->uncompletedMethodNumber;
	}

	public function handleEvent($event, observable $observable)
	{
		if (parent::handleEvent($event, $observable) === false)
		{
			return false;
		}
		else
		{
			$score = $observable->getScore();

			$this->testNumber = $observable->getTestNumber();
			$this->testMethodNumber = $observable->getTestMethodNumber();
			$this->assertionNumber = $score->getAssertionNumber();
			$this->failNumber = $score->getFailNumber();
			$this->errorNumber = $score->getErrorNumber();
			$this->exceptionNumber = $score->getExceptionNumber();
			$this->uncompletedMethodNumber = $score->getUncompletedMethodNumber();

			return true;
		}
	}
}

?>
