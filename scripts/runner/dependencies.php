<?php

use
	mageekguy\atoum,
	mageekguy\atoum\dependencies
;

$resolver = new dependencies\resolver();

$resolver['adapter'] = $adapter = new atoum\adapter();

var_dump($adapter);

return $resolver;
