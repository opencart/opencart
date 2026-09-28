<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Event class
*
* Event System Userguide
* 
* https://github.com/opencart/opencart/wiki/Events-(script-notifications)-2.2.x.x
*/
class Event {
	protected $registry;
	protected $data = array();
	protected $processed = array();
	protected $refresh = false;
	
	/**
	 * Constructor
	 *
	 * @param	object	$route
 	*/
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	/**
	 * 
	 *
	 * @param	string	$trigger
	 * @param	object	$action
	 * @param	int		$priority
 	*/	
	public function register($trigger, Action $action, $priority = 0) {
		$this->data[] = array(
			'trigger'  => $trigger,
			'action'   => $action,
			'priority' => $priority,
			'wildcard' => (strpos($trigger, '*') !== false) || (strpos($trigger, '?') !== false)
		);
		$this->refresh = true;
	}
	
	/**
	 * 
	 *
	 * @param	string	$event
	 * @param	array	$args
 	*/		
	public function trigger($event, array $args = array()) {
		if ($this->refresh) {
			array_multisort(
				array_column($this->data, 'priority'), SORT_ASC,
				$this->data
			);
			$this->processed = array();
			$this->refresh = false;
		}
		
		if (!isset($this->processed[$event])) {
			$this->processed[$event] = array();
			foreach ($this->data as $value) {
				if (!$value['wildcard'] && ($value['trigger'] == $event)) {
					// not a wildcard and exactly matches
					$this->processed[$event][] = $value;
				} elseif ($value['wildcard']) {
					if (preg_match('/^' . str_replace(array('\*', '\?'), array('.*', '.'), preg_quote($value['trigger'], '/')) . '/', $event)) {
						$this->processed[$event][] = $value;
					}
				}
			}
		}
		
		foreach ($this->processed[$event] as $value) {
			$result = $value['action']->execute($this->registry, $args);
			if (!is_null($result) && !($result instanceof Exception)) {
				return $result;
			}
		}
	}
	
	/**
	 * 
	 *
	 * @param	string	$trigger
	 * @param	string	$route
 	*/	
	public function unregister($trigger, $route) {
		foreach ($this->data as $key => $value) {
			if ($trigger == $value['trigger'] && $value['action']->getId() == $route) {
				unset($this->data[$key]);
				$this->refresh = true;
			}
		}
	}
	
	/**
	 * 
	 *
	 * @param	string	$trigger
 	*/		
	public function clear($trigger) {
		foreach ($this->data as $key => $value) {
			if ($trigger == $value['trigger']) {
				unset($this->data[$key]);
				$this->refresh = true;
			}
		}
	}	
}