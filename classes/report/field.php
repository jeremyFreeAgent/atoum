<?php

namespace mageekguy\atoum\report;

use
	mageekguy\atoum\dependencies,
	mageekguy\atoum\observable,
	mageekguy\atoum\locale
;

abstract class field
{
	protected $events = array();
	protected $locale = null;
	protected $dependencies = null;

	public function __construct(array $events = array(), dependencies $dependencies = null)
	{
		$this
			->setEvents($events)
			->setDepedencies($dependencies ?: new dependencies())
			->setLocale($this->dependencies['locale']())
		;
	}

	public function setDepedencies(dependencies $dependencies)
	{
		$this->dependencies = $dependencies[$this];

		$this->dependencies->lock();
		$this->dependencies['locale'] = function() { return new locale(); };
		$this->dependencies->unlock();

		return $this;
	}

	public function getDepedencies()
	{
		return $this->dependencies;
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
