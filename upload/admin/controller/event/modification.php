<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Modification
 *
 * @package Opencart\Admin\Controller\Event
 */
class Modification extends \Opencart\System\Engine\Controller {
	/**
	 * Controller
	 *
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 */
	public function controller(string &$route, array &$args) {
		if (substr($route, 0, 16) !== 'extension/ocmod/' && is_file(DIR_EXTENSION . 'ocmod/admin/controller/' . $route . '.php')) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	/**
	 * Model
	 *
	 *
	 *
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 */
	public function model(string &$route, array &$args): void {
		// For all models we need to separate the method which will always the last /
		$pos = strrpos($route, '/');

		$class = substr($route, 0, $pos);

		if (substr($route, 0, 16) !== 'extension/ocmod/' && is_file(DIR_EXTENSION . 'ocmod/admin/model/' . $class . '.php')) {
			$route = 'extension/ocmod/' . $class . '/' . substr($route, $pos + 1);
		}
	}

	/**
	 * View
	 *
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 */
	public function view(string &$route, array &$args): void {
		if (substr($route, 0, 16) !== 'extension/ocmod/' && is_file(DIR_EXTENSION . 'ocmod/admin/view/template/' . $route . '.twig')) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	/**
	 * Library
	 *
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 */
	public function library(string &$route, array &$args): void {
		if (substr($route, 0, 16) !== 'extension/ocmod/' && is_file(DIR_EXTENSION . 'ocmod/system/library/' . $route . '.php')) {
			$route = 'extension/ocmod/' . $route;
		}
	}
}
