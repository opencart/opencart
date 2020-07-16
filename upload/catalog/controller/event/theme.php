<?php
class ControllerEventTheme extends Controller {
	public function index(&$route, &$args, &$code) {

		if (!$this->config->get('theme_' . $this->config->get('config_theme') . '_status')) {
			exit('Error: A theme has not been assigned to this store!');
		}

		// If the default theme is selected we need to know which directory its pointing to
		if ($this->config->get('config_theme') == 'default') {
			$directory = $this->config->get('theme_default_directory');
		} else {
			$directory = $this->config->get('config_theme');
		}

		if ($directory != 'default' && is_file(DIR_TEMPLATE . $directory . '/template/' . $route . '.twig')) {
			$this->config->set('template_directory', $directory . '/template/');
		} elseif (is_file(DIR_TEMPLATE . 'default/template/' . $route . '.twig')) {
			$this->config->set('template_directory', 'default/template/');
		}

		// If there is a theme override we should get it
		$this->load->model('design/theme');

		$theme_info = $this->model_design_theme->getTheme($route, $directory);

		if ($theme_info) {
			$code = html_entity_decode($theme_info['code'], ENT_QUOTES, 'UTF-8');
		}
	}
}