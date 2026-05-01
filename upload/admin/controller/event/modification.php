<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Modification
 *
и * Adds event handling of modification ocmod controllers, models, views and libraries easier
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
	 * Language
	 *
	 * Mirrors view(): redirects the language route to its OCMOD copy when a
	 * patched file exists for the active language code. The Loader fires
	 * `language/<route>/before` with three by-reference arguments
	 * ($route, $prefix, $code), so this handler matches that exact signature.
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

		// Loader::language() forwards an empty $code most of the time and lets
		// Language::load() fall back to its constructor default, which is
		// $config['language_code'].
		$active_code = $code !== '' ? $code : (string)$this->config->get('language_code');

		if ($active_code === '') {
			return;
		}

		if (!str_starts_with($route, 'extension/')) {
			$file = DIR_EXTENSION . 'ocmod/admin/language/' . $active_code . '/' . $route . '.php';
		} else {
			$separator = strpos($route, '/', 10);

			if ($separator === false) {
				return;
			}

			$file = DIR_EXTENSION . 'ocmod/extension/' . substr($route, 10, $separator - 10) . '/admin/language/' . $active_code . '/' . substr($route, $separator + 1) . '.php';
		}

		if (is_file($file)) {
			$route = 'extension/ocmod/' . $route;
		}
	}

	/**
	 * Library
	 *
	 * Reserved for future use. The Loader does not currently fire a
	 * `library/<route>/before` event and no config registers one, so this
	 * handler is dormant. Kept in place so OCMOD can hook library overrides
	 * once the event is wired up upstream.
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
	 * Build the OCMOD-namespaced class name for a given route.
	 *
	 * @param string $route
	 * @param string $classPrefix
	 *
	 * @return string
	 */
	protected function prepareClassName(string $route, string $classPrefix): string {
		$pos = strrpos($route, '.');
		$route = $pos ? substr($route, 0, $pos) : $route;

		// Extension routes live under \Extension\Ocmod\Extension\<code>\..., so
		// strip the leading 'extension/' before building the class part to avoid
		// producing a double 'Extension\Extension\' segment.
		if (str_starts_with($route, 'extension/')) {
			$classPrefix .= 'Extension\\';
			$route = substr($route, strlen('extension/'));
		}

		$classPart = str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

		return $classPrefix . $classPart;
	}
}
