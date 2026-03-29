<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Debug
 *
 * @package Opencart\Admin\Controller\Event
 */
class Debug extends \Opencart\System\Engine\Controller {
	/**
	 * Before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @return void
	 */
	public function before(string &$route, array &$args): void {
		if ($route == 'common/home') { // Add the route you want to test
			//$this->session->data['debug'][$route] = microtime();
		}
	}

	/**
	 * After
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function after(string $route, array &$args, &$output): void {
		if ($route == 'common/home') {
			// add the route you want to test
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
