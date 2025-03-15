<?php

namespace Opencart\System\Engine;

class Middleware
{
	private Registry $registry;

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->registry = $registry;
	}

	public function execute($route) {
		$args = [];

		$params = [&$route, &$args];
		$event = new \Opencart\System\Engine\Action('event/modification.controller');
		$event->execute($this->registry, $params);

		$action = new \Opencart\System\Engine\Action($route);
		return $action->execute($this->registry, $args);
	}
}
