<?php

namespace mageekguy\atoum\test\engines;

use
	mageekguy\atoum,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\test\engines
;

class isolate extends engines\concurrent
{
	protected $score = null;

	public function __construct(dependencies\resolver $resolver = null)
	{
		parent::__construct($resolver);

		$this->setScore($resolver['@score'] ?: static::getDefaultScore());
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

	protected static function getDefaultScore()
	{
		return new atoum\score();
	}
}
