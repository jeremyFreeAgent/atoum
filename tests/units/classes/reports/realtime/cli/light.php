<?php

namespace mageekguy\atoum\tests\units\reports\realtime\cli;

use
	mageekguy\atoum,
	mageekguy\atoum\reports,
	mageekguy\atoum\dependencies,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\report\fields
;

require_once __DIR__ . '/../../../../runner.php';

class light extends atoum\test
{
	public function test__construct()
	{
		$this
			->if($report = new reports\realtime\cli\light())
			->then
				->object($report->getLocale())->isInstanceOf('mageekguy\atoum\locale')
				->object($report->getAdapter())->isInstanceOf('mageekguy\atoum\adapter')
				->array($report->getFields())->isEqualTo(array(
						new fields\runner\event\cli(),
						new fields\runner\result\cli(
							null,
							new colorizer('0;37', '42'),
							new colorizer('0;37', '41')
						),
						new fields\runner\failures\cli(
							new prompt('> '),
							new colorizer('0;31'),
							new prompt('=> ', new colorizer('0;31'))
						),
						new fields\runner\outputs\cli(
							new prompt('> '),
							new colorizer('1;36'),
							new prompt('=> ', new colorizer('1;36'))
						),
						new fields\runner\errors\cli(
							new prompt('> '),
							new colorizer('0;33'),
							new prompt('=> ', new colorizer('0;33')),
							null,
							new prompt('==> ', new colorizer('0;33'))
						),
						new fields\runner\exceptions\cli(
							new prompt('> '),
							new colorizer('0;35'),
							new prompt('=> ', new colorizer('0;35')),
							null,
							new prompt('==> ', new colorizer('0;35'))
						),
						new fields\runner\tests\uncompleted\cli(
							new prompt('> '),
							new colorizer('0;37'),
							new prompt('=> ', new colorizer('0;37')),
							null,
							new prompt('==> ', new colorizer('0;37'))
						)
					)
				)
			->if($resolver = new dependencies\resolver())
			->and($resolver['locale'] = $locale = new atoum\locale())
			->and($resolver['adapter'] = $adapter = new atoum\adapter())
			->and($report = new reports\realtime\cli\light($resolver))
			->then
				->object($report->getAdapter())->isIdenticalTo($adapter)
				->object($report->getLocale())->isIdenticalTo($locale)
				->array($report->getFields())->isEqualTo(array(
						new fields\runner\event\cli(),
						new fields\runner\result\cli(
							null,
							new colorizer('0;37', '42'),
							new colorizer('0;37', '41')
						),
						new fields\runner\failures\cli(
							new prompt('> '),
							new colorizer('0;31'),
							new prompt('=> ', new colorizer('0;31'))
						),
						new fields\runner\outputs\cli(
							new prompt('> '),
							new colorizer('1;36'),
							new prompt('=> ', new colorizer('1;36'))
						),
						new fields\runner\errors\cli(
							new prompt('> '),
							new colorizer('0;33'),
							new prompt('=> ', new colorizer('0;33')),
							null,
							new prompt('==> ', new colorizer('0;33'))
						),
						new fields\runner\exceptions\cli(
							new prompt('> '),
							new colorizer('0;35'),
							new prompt('=> ', new colorizer('0;35')),
							null,
							new prompt('==> ', new colorizer('0;35'))
						),
						new fields\runner\tests\uncompleted\cli(
							new prompt('> '),
							new colorizer('0;37'),
							new prompt('=> ', new colorizer('0;37')),
							null,
							new prompt('==> ', new colorizer('0;37'))
						)
					)
				)
		;
	}
}
