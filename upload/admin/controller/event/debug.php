<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Debug
 *
 * @package Opencart\Admin\Controller\Event
 */
class Debug extends \Opencart\System\Engine\Controller {
	/**
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 */
	public function before(string &$route, array &$args): void {
		if ($route == 'common/home') { // add the route you want to test
			//$this->session->data['debug'][$route] = microtime();
		}
	}

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function after(string $route, array &$args, mixed &$output): void {
		if ($route == 'common/home') { // add the route you want to test
			if (isset($this->session->data['debug'][$route])) {
				$log_data = [
					'route' => $route,
					'time'  => microtime() - $this->session->data['debug'][$route]
				];
				
				$this->log->write($route);
			}
		}
	}	
}
