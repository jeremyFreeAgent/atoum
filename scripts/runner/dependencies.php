<?php

use
	mageekguy\atoum,
	mageekguy\atoum\dependencies
;

$resolver = new dependencies\resolver();

$resolver['adapter'] = new atoum\adapter();
$resolver['locale'] = new atoum\locale();
$resolver['reflection\class\resolver'] = new dependencies\resolver(function($resolver) { return new \reflectionClass($resolver['@class']); });

return $resolver;
