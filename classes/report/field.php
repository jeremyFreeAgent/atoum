<?php

namespace mageekguy\atoum\report;

use
	mageekguy\atoum\depedencies,
	mageekguy\atoum\observable,
	mageekguy\atoum\locale
;

abstract class field
{
	protected $events = array();
	protected $locale = null;
	protected $depedencies = null;

	public function __construct(array $events = array(), depedencies $depedencies = null)
	{
		$this
			->setEvents($events)
			->setDepedencies($depedencies ?: new depedencies())
			->setLocale($this->depedencies['locale']())
		;
	}

	public function setDepedencies(depedencies $depedencies)
	{
		$this->depedencies = $depedencies[$this];

		$this->depedencies->lock();
		$this->depedencies['locale'] = function() { return new locale(); };
		$this->depedencies->unlock();

		return $this;
	}

	public function getDepedencies()
	{
		return $this->depedencies;
	}

	public function setLocale(locale $locale)
	{
		$this->locale = $locale;

		return $this;
	}

	public function getLocale()
	{
		return $this->locale;
	}

	public function setEvents(array $events)
	{
		$this->events = $events;

		return $this;
	}

	public function getEvents()
	{
		return $this->events;
	}

	public function canHandleEvent($event)
	{
		return in_array($event, $this->events);
	}

	public function handleEvent($event, observable $observable)
	{
		return $this->canHandleEvent($event);
	}

	abstract public function __toString();
}

?>
