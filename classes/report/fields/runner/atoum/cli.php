<?php

namespace mageekguy\atoum\report\fields\runner\atoum;

use
	mageekguy\atoum\report,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer
;

class cli extends report\fields\runner\atoum
{
	protected $prompt = null;
	protected $colorizer = null;

	public function __construct(dependencies $dependencies = null)
	{
		parent::__construct($dependencies);

		$this
			->setPrompt($this->dependencies['prompt']())
			->setColorizer($this->dependencies['colorizer']())
		;
	}

	public function setDepedencies(dependencies $dependencies)
	{
		parent::setDepedencies($dependencies);

		$this->dependencies->lock();
		$this->dependencies['prompt'] = new prompt('> ');
		$this->dependencies['colorizer'] = new colorizer();
		$this->dependencies->unlock();

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
