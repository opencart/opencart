<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class SSR
 *
 * Used for triggering static file rendering
 *
 * @package Opencart\Admin\Controller\Event
 */
class Ssr extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Triggers SSR generation
	 *
	 * Called using model/ * /after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$action = new Action('ssr/' . $route);
		$action->execute($this->registry, $args);
	}
}