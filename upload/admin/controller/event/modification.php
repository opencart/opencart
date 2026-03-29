<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Modification
 *
 * Adds event handling of modification ocmod controllers, models, views and libraries easier
 *
 * @package Opencart\Admin\Controller\Event
 */
class Modification extends \Opencart\System\Engine\Controller {
	/**
	 * Controller
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @return void
	 */
	public function controller(string &$route, array &$args): void {
		if (substr($route, 0, 16) == 'extension/ocmod/') {
			return;
		}

		if (substr($route, 0, 10) !== 'extension/') {
			$class = 'Opencart\Admin\Controller\Extension\Ocmod\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));
		} else {
			$class = 'Opencart\Admin\Controller\Extension\Ocmod\Extension\\' . str_replace(['_', '/'], ['', '\\'], ucwords(substr($route, 10), '_/'));
		}

		if (class_exists($class)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	/**
	 * Model
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @return void
	 */
	public function model(string &$route, array &$args): void {
		if (substr($route, 0, 16) == 'extension/ocmod/') {
			return;
		}

		if (substr($route, 0, 10) !== 'extension/') {
			$class = 'Opencart\Admin\Model\Extension\Ocmod\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));
		} else {
			$class = 'Opencart\Admin\Model\Extension\Ocmod\Extension\\' . str_replace(['_', '/'], ['', '\\'], ucwords(substr($route, 10), '_/'));
		}

		if (class_exists($class)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	/**
	 * View
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @return void
	 */
	public function view(string &$route, array &$args): void {
		if (substr($route, 0, 16) == 'extension/ocmod/') {
			return;
		}

		if (substr($route, 0, 10) !== 'extension/') {
			$file = DIR_EXTENSION . 'ocmod/admin/view/template/' . $route . '.twig';
		} else {
			$file = DIR_EXTENSION . 'ocmod/extension/' . substr($route, 10, strpos($route, '/', 10) - 10) . '/admin/view/template/' . substr($route, strpos($route, '/', 10) + 1) . '.twig';
		}

		if (is_file($file)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	/**
	 * Library
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @return void
	 */
	public function library(string &$route, array &$args): void {
		if (substr($route, 0, 16) == 'extension/ocmod/') {
			return;
		}

		if (substr($route, 0, 10) !== 'extension/') {
			$class = 'Opencart\System\Library\Extension\Ocmod\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));
		} else {
			$class = 'Opencart\System\Library\Extension\Ocmod\Extension\\' . str_replace(['_', '/'], ['', '\\'], ucwords(substr($route, 10), '_/'));
		}

		if (class_exists($class)) {
			$route = 'extension/ocmod/' . $route;
		}
	}
}
