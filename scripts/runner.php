<?php

namespace mageekguy\atoum;

$start = memory_get_usage();

use
	mageekguy\atoum,
	mageekguy\atoum\scripts
;

require_once __DIR__ . '/../classes/autoloader.php';

if (defined(__NAMESPACE__ . '\scripts\runner') === false)
{
	define(__NAMESPACE__ . '\scripts\runner', __FILE__);
}

if (scripts\runner::autorunMustBeEnabled() === true)
{
	scripts\runner::enableAutorun(constant(__NAMESPACE__ . '\scripts\runner'), scripts\runner::useDependenciesFile(atoum\directory) ?: include __DIR__ . '/runner/dependencies.php');
}
