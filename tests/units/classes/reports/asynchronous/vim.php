<?php

namespace mageekguy\atoum\tests\units\reports\asynchronous;

use
	mageekguy\atoum,
	mageekguy\atoum\cli,
	mageekguy\atoum\reports\asynchronous
;

require_once __DIR__ . '/../../../runner.php';

class vim extends atoum\test
{
	public function testClass()
	{
		$this->testedClass->isSubclassOf('mageekguy\atoum\reports\asynchronous');
	}

	public function test__construct()
	{
		$this
			->if($report = new asynchronous\vim())
			->then
				->object($depedencies = $report->getDepedencies())->isInstanceOf('mageekguy\atoum\depedencies')
				->boolean(isset($depedencies['locale']))->isTrue()
				->boolean(isset($depedencies['adapter']))->isTrue()
				->object($report->getLocale())->isInstanceOf('mageekguy\atoum\locale')
				->object($report->getAdapter())->isInstanceOf('mageekguy\atoum\adapter')
			->if($depedencies = new atoum\depedencies())
			->and($depedencies['mageekguy\atoum\reports\asynchronous\vim']['locale'] = $localeInjector = function() use (& $locale) { return $locale = new atoum\locale(); })
			->and($depedencies['mageekguy\atoum\reports\asynchronous\vim']['adapter'] = $adapterInjector = function() use (& $adapter) { return $adapter = new atoum\adapter(); })
			->and($report = new asynchronous\vim($depedencies))
			->then
				->object($report->getDepedencies())->isIdenticalTo($depedencies[$report])
				->object($depedencies['mageekguy\atoum\reports\asynchronous\vim']['locale'])->isIdenticalTo($localeInjector)
				->object($depedencies['mageekguy\atoum\reports\asynchronous\vim']['adapter'])->isIdenticalTo($adapterInjector)
				->object($report->getLocale())->isIdenticalTo($locale)
				->object($report->getAdapter())->isIdenticalTo($adapter)
		;
	}

	public function testSetDepedencies()
	{
		$this
			->if($report = new asynchronous\vim())
			->and($reportClass = get_class($report))
			->then
				->object($report->setDepedencies($depedencies = new atoum\depedencies()))->isIdenticalTo($report)
				->object($reportDepedencies = $report->getDepedencies())->isIdenticalTo($depedencies[$reportClass])
				->boolean(isset($reportDepedencies['locale']))->isTrue()
				->object($reportDepedencies['locale']())->isEqualTo(new atoum\locale())
				->boolean(isset($reportDepedencies['adapter']))->isTrue()
				->object($reportDepedencies['adapter']())->isEqualTo(new atoum\adapter())
				->boolean(isset($reportDepedencies['ps1']))->isTrue()
				->object($reportDepedencies['ps1']())->isEqualTo(new cli\prompt('> '))
				->boolean(isset($reportDepedencies['ps2']))->isTrue()
				->object($reportDepedencies['ps2']())->isEqualTo(new cli\prompt('=> '))
				->boolean(isset($reportDepedencies['ps3']))->isTrue()
				->object($reportDepedencies['ps3']())->isEqualTo(new cli\prompt('==> '))
			->if($depedencies = new atoum\depedencies())
			->and($depedencies[$reportClass] = new atoum\depedencies())
			->and($depedencies[$reportClass]['locale'] = $localeInjector = function() { return new atoum\locale(); })
			->and($depedencies[$reportClass]['adapter'] = $adapterInjector = function() { return new atoum\adapter(); })
			->and($depedencies[$reportClass]['ps1'] = $ps1Injector = function() { return new cli\prompt(); })
			->and($depedencies[$reportClass]['ps2'] = $ps2Injector = function() { return new cli\prompt(); })
			->and($depedencies[$reportClass]['ps3'] = $ps3Injector = function() { return new cli\prompt(); })
			->then
				->object($report->setDepedencies($depedencies))->isIdenticalTo($report)
				->object($reportDepedencies = $report->getDepedencies())->isIdenticalTo($depedencies[$reportClass])
				->boolean(isset($reportDepedencies['locale']))->isTrue()
				->object($reportDepedencies['locale'])->isIdenticalTo($localeInjector)
				->boolean(isset($reportDepedencies['adapter']))->isTrue()
				->object($reportDepedencies['adapter'])->isIdenticalTo($adapterInjector)
				->boolean(isset($reportDepedencies['ps1']))->isTrue()
				->object($reportDepedencies['ps1'])->isIdenticalTo($ps1Injector)
				->boolean(isset($reportDepedencies['ps2']))->isTrue()
				->object($reportDepedencies['ps2'])->isIdenticalTo($ps2Injector)
				->boolean(isset($reportDepedencies['ps3']))->isTrue()
				->object($reportDepedencies['ps3'])->isIdenticalTo($ps3Injector)
		;
	}
}

?>
