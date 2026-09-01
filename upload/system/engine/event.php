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
	 * @var array<string, array<string, mixed>>
	 */
	protected $processed = [];
	/**
	 * @var boolean
	 */
	protected $refresh = false;

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
			'priority' => $priority,
			'wildcard' => (strpos($trigger, '*') !== false) || (strpos($trigger, '?') !== false)
		];
		$this->refresh = true;
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
		if ($this->refresh) {
			array_multisort(
				array_column($this->data, 'priority'), SORT_ASC,
				$this->data
			);
			$this->processed = [];
			$this->refresh = false;
		}
		
		if (!isset($this->processed[$event])) {
			$this->processed[$event] = [];
			foreach ($this->data as $value) {
				if (!$value['wildcard'] && ($value['trigger'] == $event)) {
					// not a wildcard and exactly matches
					$this->processed[$event][] = $value;
				} elseif ($value['wildcard']) {
					if (preg_match('/^' . str_replace(['\*', '\?'], ['.*', '.'], preg_quote($value['trigger'], '/')) . '/', $event)) {
						$this->processed[$event][] = $value;
					}
				}
			}
		}
		
		foreach ($this->processed[$event] as $value) {
			$value['action']->execute($this->registry, $args);
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
				$this->refresh = true;
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
				$this->refresh = true;
			}
		}
	}
}
