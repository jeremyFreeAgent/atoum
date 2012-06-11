<?php

namespace mageekguy\atoum\tests\units\reports\asynchronous;

use
	mageekguy\atoum,
	mageekguy\atoum\cli,
	mageekguy\atoum\report\fields\test,
	mageekguy\atoum\report\fields\runner,
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
			->and($reportClass = get_class($report))
			->then
				->object($depedencies = $report->getDepedencies())->isInstanceOf('mageekguy\atoum\depedencies')
				->boolean(isset($depedencies['locale']))->isTrue()
				->boolean(isset($depedencies['adapter']))->isTrue()
				->object($report->getLocale())->isInstanceOf('mageekguy\atoum\locale')
				->object($report->getAdapter())->isInstanceOf('mageekguy\atoum\adapter')
			->if($depedencies = new atoum\depedencies())
			->and($depedencies[$reportClass]['locale'] = $localeInjector = function() use (& $locale) { return $locale = new atoum\locale(); })
			->and($depedencies[$reportClass]['adapter'] = $adapterInjector = function() use (& $adapter) { return $adapter = new atoum\adapter(); })
			->and($depedencies[$reportClass]['ps1'] = $ps1Injector = function() use (& $ps1) { return $ps1 = new cli\prompt(); })
			->and($depedencies[$reportClass]['ps2'] = $ps2Injector = function() use (& $ps2) { return $ps2 = new cli\prompt(); })
			->and($depedencies[$reportClass]['ps3'] = $ps3Injector = function() use (& $ps3) { return $ps3 = new cli\prompt(); })
			->and($report = new asynchronous\vim($depedencies))
			->then
				->object($reportDepedencies = $report->getDepedencies())->isIdenticalTo($depedencies[$reportClass])
				->object($reportDepedencies['locale'])->isIdenticalTo($localeInjector)
				->object($report->getLocale())->isIdenticalTo($locale)
				->object($reportDepedencies['adapter'])->isIdenticalTo($adapterInjector)
				->object($report->getAdapter())->isIdenticalTo($adapter)
				->array($report->getFields())->isEqualTo(array(
						new runner\atoum\cli($ps1),
						new runner\php\path\cli($ps1),
						new runner\php\version\cli($ps1, null, $ps2),
						new runner\tests\duration\cli($ps1),
						new runner\tests\memory\cli($ps1),
						new runner\tests\coverage\cli($ps1, $ps2, $ps3),
						new runner\duration\cli($ps1),
						new runner\result\cli(),
						new runner\failures\cli($ps1, null, $ps2),
						new runner\errors\cli($ps1, null, $ps2, null, $ps3),
						new runner\exceptions\cli($ps1, null, $ps2, null, $ps3),
						new runner\tests\uncompleted\cli($ps1, null, $ps2, null, $ps3),
						new runner\outputs\cli($ps1, null, $ps2),
						new test\run\cli($ps1),
						new test\duration\cli($ps2),
						new test\memory\cli($ps2)
					)
				)
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
			->and($depedencies[$reportClass]['locale'] = $localeInjector = function() {})
			->and($depedencies[$reportClass]['adapter'] = $adapterInjector = function() {})
			->and($depedencies[$reportClass]['ps1'] = $ps1Injector = function() {})
			->and($depedencies[$reportClass]['ps2'] = $ps2Injector = function() {})
			->and($depedencies[$reportClass]['ps3'] = $ps3Injector = function() {})
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
