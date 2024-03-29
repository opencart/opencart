<?php
namespace Opencart\Catalog\Controller\Event;
/**
 * Class Modification
 *
 * @package Opencart\Catalog\Controller\Event
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
			$class = 'Opencart\Catalog\Controller\Extension\Ocmod\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));
		} else {
			$class = 'Opencart\Catalog\Controller\Extension\Ocmod\Extension\\' . str_replace(['_', '/'], ['', '\\'], ucwords(substr($route, 10), '_/'));
		}

		if (class_exists($class)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	/**
	 * Model
	 *so
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
			$class = 'Opencart\Catalog\Model\Extension\Ocmod\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));
		} else {
			$class = 'Opencart\Catalog\Model\Extension\Ocmod\Extension\\' . str_replace(['_', '/'], ['', '\\'], ucwords(substr($route, 10), '_/'));
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
			$file = DIR_EXTENSION . 'ocmod/catalog/view/template/' . $route . '.twig';
		} else {
			substr($route, 10, );


			$part = explode('/', $route);

			$extension = $part[1];

			unset($part[0]);
			unset($part[1]);

			$file = DIR_EXTENSION . 'ocmod/extension/' . $extension . '/catalog/view/template/' . implode('/', $part) . '.twig';
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
