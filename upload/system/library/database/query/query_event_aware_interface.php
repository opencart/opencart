<?php

namespace Opencart\System\Library\Database\Query;

interface QueryEventAwareInterface {
	public function event(string $name): static;

	public function getEventName(): ?string;
}
