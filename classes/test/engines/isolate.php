<?php

namespace mageekguy\atoum\test\engines;

use
	mageekguy\atoum,
	mageekguy\atoum\test,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\test\engines
;

class isolate extends engines\concurrent
{
	protected $score = null;

	public function __construct(dependencies\resolver $resolver = null)
	{
		$resolver = $resolver ?: new dependencies\resolver();

		parent::__construct($resolver);

		$this->setDefaultScore($resolver);
	}

	public function run(atoum\test $test)
	{
		parent::run($test);

		$this->score = parent::getScore();

		while ($this->score === null)
		{
			$this->score = parent::getScore();
		}

		return $this;
	}

	public function getScore()
	{
		return $this->score;
	}

	protected function setDefaultScore(dependencies\resolver $resolver)
	{
		return $this->setScore($resolver['@test\score'] ?: new atoum\test\score($resolver));
	}
}
