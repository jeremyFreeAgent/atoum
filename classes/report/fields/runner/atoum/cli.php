<?php

namespace mageekguy\atoum\report\fields\runner\atoum;

use
	mageekguy\atoum\report,
	mageekguy\atoum\depedencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer
;

class cli extends report\fields\runner\atoum
{
	protected $prompt = null;
	protected $colorizer = null;

	public function __construct(depedencies $depedencies = null)
	{
		parent::__construct($depedencies);

		$this
			->setPrompt($this->depedencies['prompt']())
			->setColorizer($this->depedencies['colorizer']())
		;
	}

	public function setDepedencies(depedencies $depedencies)
	{
		parent::setDepedencies($depedencies);

		$this->depedencies->lock();
		$this->depedencies['prompt'] = new prompt();
		$this->depedencies['colorizer'] = new colorizer();
		$this->depedencies->unlock();

		return $this;
	}

	public function setPrompt(prompt $prompt)
	{
		$this->prompt = $prompt;

		return $this;
	}

	public function getPrompt()
	{
		return $this->prompt;
	}

	public function setColorizer(colorizer $colorizer)
	{
		$this->colorizer = $colorizer;

		return $this;
	}

	public function getColorizer()
	{
		return $this->colorizer;
	}

	public function __toString()
	{
		return ($this->author === null || $this->version === null ? '' : $this->prompt . $this->colorizer->colorize(sprintf($this->locale->_('atoum version %s by %s (%s)'), $this->version, $this->author, $this->path)) . PHP_EOL);
	}
}

?>
