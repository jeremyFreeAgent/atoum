<?php

namespace mageekguy\atoum\report;

use
	mageekguy\atoum
;

abstract class field
{
	protected $events = array();
	protected $locale = null;

	public function __construct(array $events = array(), atoum\locale $locale = null)
	{
		$this->events = $events;
		$this->setLocale($locale ?: new atoum\locale());
	}

	public function setLocale(atoum\locale $locale)
	{
		$this->locale = $locale;

		return $this;
	}

	public function getLocale()
	{
		return $this->locale;
	}

	public function getEvents()
	{
		return $this->events;
	}

	public function canHandleEvent($event)
	{
		return in_array($event, $this->events);
	}

	public function handleEvent($event, atoum\observable $observable)
	{
		return $this->canHandleEvent($event);
	}

	abstract public function __toString();
}

?>
