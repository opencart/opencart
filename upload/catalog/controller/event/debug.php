<?php
namespace Opencart\Catalog\Controller\Event;
/**
 * Class Debug
 *
 * @package Opencart\Catalog\Controller\Event
 */
class Debug extends \Opencart\System\Engine\Controller {
	/**
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args): void {
		//echo $route;
	}

	/**
	 * Before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @return void
	 */
	public function before(string &$route, array &$args): void {
		// add the route you want to test
		/*
		if ($route == 'common/home') {
			$this->session->data['debug'][$route] = microtime(true);
		}
		*/
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
	public function after(string &$route, array &$args, &$output): void {
		// add the route you want to test
		/*
		if ($route == 'common/home') {
			if (isset($this->session->data['debug'][$route])) {
				$log_data = [
					'route' => $route,
					'time'  => microtime(true) - $this->session->data['debug'][$route]
				];

				$this->log->write($log_data);
			}
		}
		*/
	}
}
