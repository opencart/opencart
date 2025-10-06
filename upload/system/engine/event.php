<?php
/**
 * @package		OpenCart
 *
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 *
 * @see		https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Event
 *
 * https://github.com/opencart/opencart/wiki/Events-(script-notifications)-2.2.x.x
 */
class Event {
	/**
	 * @var \Opencart\System\Engine\Registry
	 */
	protected \Opencart\System\Engine\Registry $registry;
	/**
	 * @var array<int, array<string, mixed>>
	 */
	protected array $data = [];

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->registry = $registry;
	}

	/**
	 * Register
	 *
	 * @param string                         $trigger
	 * @param \Opencart\System\Engine\Action $action
	 * @param int                            $priority
	 *
	 * @return void
	 */
	public function register(string $trigger, \Opencart\System\Engine\Action $action, int $priority = 0): void {
		$this->data[] = [
			'trigger'  => $trigger,
			'action'   => $action,
			'priority' => $priority
		];

		$sort_order = [];

		foreach ($this->data as $key => $value) {
			$sort_order[$key] = $value['priority'];
		}

		array_multisort($sort_order, SORT_ASC, $this->data);
	}

	/**
	 * Trigger
	 *
	 * @param string       $event
	 * @param array<mixed> $args
	 *
	 * @return mixed
	 */
	public function trigger(string $event, array $args = []) {
		foreach ($this->data as $value) {
			if (preg_match('/^' . str_replace(['\*', '\?'], ['.*', '.'], preg_quote($value['trigger'], '/')) . '/', $event)) {
				$value['action']->execute($this->registry, $args);
			}
		}

		return '';
	}

	/**
	 * Unregister
	 *
	 * @param string $trigger
	 * @param string $route
	 *
	 * @return void
	 */
	public function unregister(string $trigger, string $route): void {
		foreach ($this->data as $key => $value) {
			if ($trigger == $value['trigger'] && $value['action']->getId() == $route) {
				unset($this->data[$key]);
			}
		}
	}

	/**
	 * Clear
	 *
	 * @param string $trigger
	 *
	 * @return void
	 */
	public function clear(string $trigger): void {
		foreach ($this->data as $key => $value) {
			if ($trigger == $value['trigger']) {
				unset($this->data[$key]);
			}
		}
	}
}
