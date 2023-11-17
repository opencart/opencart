<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Modification
 *
 * @package Opencart\Admin\Controller\Event
 */
class Modification extends \Opencart\System\Engine\Controller {
	/**
	 *
	 * @param string $route
	 * @param array  $args
	 * @param array  $output
	 *
	 * @return void
	 */
	public function controller(string &$route, array &$args): void {
		if (substr($route, 0, 16) !== 'extension/ocmod/' && is_file(DIR_EXTENSION . 'ocmod/controller/' . $route)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	public function model(string &$route, array &$args): void {
		if (substr($route, 0, 16) !== 'extension/ocmod/' && is_file(DIR_EXTENSION . 'ocmod/model/' . $route)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	public function view(string &$route, array &$args): void {
		if (substr($route, 0, 16) !== 'extension/ocmod/' && is_file(DIR_EXTENSION . 'ocmod/view/template/' . $route)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	public function library(string &$route, array &$args): void {
		if (substr($route, 0, 16) !== 'extension/ocmod/' && is_file(DIR_EXTENSION . 'ocmod/controller/' . $route)) {
			$route = 'extension/ocmod/' . $route;
		}
	}
}
