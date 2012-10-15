<?php

namespace mageekguy\atoum\test\engines;

use
	mageekguy\atoum,
	mageekguy\atoum\test,
	mageekguy\atoum\dependencies
;

class inline extends test\engine
{
	protected $score = null;

	public function __construct(dependencies\resolver $resolver = null)
	{
		$resolver = $resolver ?: new dependencies\resolver();

		$this->setDefaultScore($resolver);
	}

	public function setScore(atoum\score $score)
	{
		$this->score = $score;

		return $this;
	}

	public function isAsynchronous()
	{
		return false;
	}

	public function run(atoum\test $test)
	{
		$currentTestMethod = $test->getCurrentMethod();

		if ($currentTestMethod !== null)
		{
			$testScore = $test->getScore();

			$test
				->setScore($this->score->reset())
				->runTestMethod($test->getCurrentMethod())
				->setScore($testScore)
			;
		}

		return $this;
	}

	public function getScore()
	{
		return $this->score;
	}

	protected function setDefaultScore(dependencies\resolver $resolver)
	{
		return $this->setScore($resolver['@test\score'] ?: new test\score($resolver));
	}
}
