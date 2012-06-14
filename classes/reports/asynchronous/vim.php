<?php

namespace mageekguy\atoum\reports\asynchronous;

use
	mageekguy\atoum,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\reports,
	mageekguy\atoum\report\fields\test,
	mageekguy\atoum\report\fields\runner
;

class vim extends reports\asynchronous
{
	public function __construct(atoum\dependencies $dependencies = null)
	{
		parent::__construct($dependencies);

		$ps1 = $this->dependencies['ps1']();
		$ps2 = $this->dependencies['ps2']();
		$ps3 = $this->dependencies['ps3']();

		$this
			->addField(new runner\atoum\cli($this->dependencies))
			->addField(new runner\php\path\cli($this->dependencies))
			->addField(new runner\php\version\cli(
						$ps1,
						null,
						$ps2
					)
				)
			->addField(new runner\tests\duration\cli(
						$ps1
					)
				)
			->addField(new runner\tests\memory\cli(
						$ps1
					)
				)
			->addField(new runner\tests\coverage\cli(
						$ps1,
						$ps2,
						$ps3
					)
				)
			->addField(new runner\duration\cli(
						$ps1
					)
				)
			->addField(new runner\result\cli(
					)
				)
			->addField(new runner\failures\cli(
						$ps1,
						null,
						$ps2
					)
				)
			->addField(new runner\errors\cli(
						$ps1,
						null,
						$ps2,
						null,
						$ps3
					)
				)
			->addField(new runner\exceptions\cli(
						$ps1,
						null,
						$ps2,
						null,
						$ps3
					)
				)
			->addField(new runner\tests\uncompleted\cli(
						$ps1,
						null,
						$ps2,
						null,
						$ps3
					)
				)
			->addField(new runner\outputs\cli(
					$ps1,
					null,
					$ps2
				)
			)
			->addField(new test\run\cli(
					$ps1
				)
			)
			->addField(new test\duration\cli(
					$ps2
				)
			)
			->addField(new test\memory\cli(
					$ps2
				)
			)
		;
	}

	public function setDepedencies(atoum\dependencies $dependencies)
	{
		parent::setDepedencies($dependencies);

		$this->dependencies->lock();
		$this->dependencies['ps1'] = function() { return new prompt('> '); };
		$this->dependencies['ps2'] = function() { return new prompt('=> '); };
		$this->dependencies['ps3'] = function() { return new prompt('==> '); };
		$this->dependencies->unlock();

		return $this;
	}
}

?>
