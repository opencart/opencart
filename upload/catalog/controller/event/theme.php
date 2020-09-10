<?php
namespace Opencart\Application\Controller\Event;
class Theme extends \Opencart\System\Engine\Controller {
	public function index(&$route, &$args, &$code) {
		if (!$this->config->get('theme_' . $this->config->get('config_theme') . '_status')) {
			exit('Error: A theme has not been assigned to this store!');
		}

		// If there is a theme override we should get it
		$this->load->model('design/theme');

		$theme_info = $this->model_design_theme->getTheme($route, $this->config->get('config_theme'));

		if ($theme_info) {
			$code = html_entity_decode($theme_info['code'], ENT_QUOTES, 'UTF-8');
		}
	}
}