<?php
namespace Opencart\Catalog\Controller\Event;
/**
 * Class Theme
 *
 * @package Opencart\Catalog\Controller\Event
 */
class Theme extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param string            $code
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, string &$code): void {
		// If there is a theme override, we should get it
		$this->load->model('design/theme');

		$theme_info = $this->model_design_theme->getTheme($route, $this->config->get('config_theme'));

		if ($theme_info) {
			$code = html_entity_decode($theme_info['code'], ENT_QUOTES, 'UTF-8');
		}
	}
}
