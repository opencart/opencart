<?php

namespace Opencart\System\Library\Database\Query;

trait QueryEventTrait {
	private ?string $eventName = null;

	/**
	 * Set a logical query event name.
	 *
	 * Example: product.list
	 *
	 * @param string $name
	 */
	public function event(string $name): static {
		if (!preg_match('/^[a-z][a-z0-9_-]*(?:\.[a-z][a-z0-9_-]*)*$/D', $name)) {
			throw new \InvalidArgumentException('Invalid query event name: ' . $name);
		}

		$this->eventName = $name;

		return $this;
	}

	public function getEventName(): ?string {
		return $this->eventName;
	}
}
