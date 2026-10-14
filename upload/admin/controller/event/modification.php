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
		if (str_starts_with($route, 'extension/ocmod/')) {
			return;
		}

		$class = $this->prepareClassName($route, 'Opencart\Admin\Controller\Extension\Ocmod\\');

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
		if (str_starts_with($route, 'extension/ocmod/')) {
			return;
		}

		$class = $this->prepareClassName($route, 'Opencart\Admin\Model\Extension\Ocmod\\');

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
		if (str_starts_with($route, 'extension/ocmod/')) {
			return;
		}

		if (!str_starts_with($route, 'extension/')) {
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
		if (str_starts_with($route, 'extension/ocmod/')) {
			return;
		}

		$class = $this->prepareClassName($route, 'Opencart\System\Library\Extension\Ocmod\\');

		if (class_exists($class)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	/**
	 * Language
	 *
	 * @param string $route
	 * @param string $prefix
	 * @param string $code
	 *
	 * @return void
	 */
	public function language(string &$route, string &$prefix, string &$code): void {
		if (str_starts_with($route, 'extension/ocmod/')) {
			return;
		}

		$language_code = $code ?: $this->config->get('config_language_admin');

		if (!str_starts_with($route, 'extension/')) {
			$file = DIR_EXTENSION . 'ocmod/admin/language/' . $language_code . '/' . $route . '.php';
		} else {
			$file = DIR_EXTENSION . 'ocmod/extension/' . substr($route, 10, strpos($route, '/', 10) - 10) . '/admin/language/' . $language_code . '/' . substr($route, strpos($route, '/', 10) + 1) . '.php';
		}

		if (is_file($file)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	protected function prepareClassName(string $route, string $classPrefix): string {
		$pos = strrpos($route, '.');
		$route = $pos ? substr($route, 0, $pos) : $route;

		$classPart = str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

		if (str_starts_with($route, 'extension/')) {
			$classPrefix .= 'Extension\\';
		}

		return $classPrefix . $classPart;
	}
}
