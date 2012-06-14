<?php

namespace mageekguy\atoum\reports\realtime;

use
	mageekguy\atoum\dependencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\reports\realtime,
	mageekguy\atoum\report\fields\test,
	mageekguy\atoum\report\fields\runner
;

class cli extends realtime
{
	public function __construct(dependencies $dependencies = null)
	{
		parent::__construct($dependencies);

		$firstLevelPrompt = new prompt('> ');
		$firstLevelColorizer = new colorizer('1;36');

		$secondLevelPrompt = new prompt('=> ', $firstLevelColorizer);

		$thirdLevelPrompt = new prompt('==> ', $firstLevelColorizer);

		$failureColorizer = new colorizer('0;31');
		$failurePrompt = clone $secondLevelPrompt;
		$failurePrompt->setColorizer($failureColorizer);

		$errorColorizer = new colorizer('0;33');
		$errorMethodPrompt = clone $secondLevelPrompt;
		$errorMethodPrompt->setColorizer($errorColorizer);
		$errorPrompt = clone $thirdLevelPrompt;
		$errorPrompt->setColorizer($errorColorizer);

		$exceptionColorizer = new colorizer('0;35');
		$exceptionMethodPrompt = clone $secondLevelPrompt;
		$exceptionMethodPrompt->setColorizer($exceptionColorizer);
		$exceptionPrompt = clone $thirdLevelPrompt;
		$exceptionPrompt->setColorizer($exceptionColorizer);

		$uncompletedTestColorizer = new colorizer('0;37');
		$uncompletedTestMethodPrompt = clone $secondLevelPrompt;
		$uncompletedTestMethodPrompt->setColorizer($uncompletedTestColorizer);
		$uncompletedTestOutputPrompt = clone $thirdLevelPrompt;
		$uncompletedTestOutputPrompt->setColorizer($uncompletedTestColorizer);

		$this
			->addField(new runner\php\path\cli($this->dependencies))
			->addField(new runner\php\version\cli(
						$firstLevelPrompt,
						$firstLevelColorizer,
						$secondLevelPrompt
					)
				)
			->addField(new runner\tests\duration\cli(
						$firstLevelPrompt,
						$firstLevelColorizer
					)
				)
			->addField(new runner\tests\memory\cli(
						$firstLevelPrompt,
						$firstLevelColorizer
					)
				)
			->addField(new runner\tests\coverage\cli(
						$firstLevelPrompt,
						$secondLevelPrompt,
						new prompt('==> ', $firstLevelColorizer),
						$firstLevelColorizer
					)
				)
			->addField(new runner\duration\cli(
						$firstLevelPrompt,
						$firstLevelColorizer
					)
				)
			->addField(new runner\result\cli(
						null,
						new colorizer('0;37', '42'),
						new colorizer('0;37', '41')
					)
				)
			->addField(new runner\failures\cli(
						$firstLevelPrompt,
						$failureColorizer,
						$failurePrompt
					)
				)
			->addField(
				new runner\outputs\cli(
						$firstLevelPrompt,
						$firstLevelColorizer,
						$secondLevelPrompt
					)
				)
			->addField(new runner\errors\cli(
						$firstLevelPrompt,
						$errorColorizer,
						$errorMethodPrompt,
						null,
						$errorPrompt
					)
				)
			->addField(new runner\exceptions\cli(
						$firstLevelPrompt,
						$exceptionColorizer,
						$exceptionMethodPrompt,
						null,
						$exceptionPrompt
					)
				)
			->addField(new runner\tests\uncompleted\cli(
						$firstLevelPrompt,
						$uncompletedTestColorizer,
						$uncompletedTestMethodPrompt,
						null,
						$uncompletedTestOutputPrompt
					)
				)
			->addField(new test\run\cli(
						$firstLevelPrompt,
						$firstLevelColorizer
					)
				)
			->addField(new test\event\cli())
			->addField(new test\duration\cli(
						$secondLevelPrompt
					)
				)
			->addField(new test\memory\cli(
						$secondLevelPrompt
					)
				)
		;
	}

	public function setDepedencies(dependencies $dependencies)
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
