<?php
class ControllerEventTheme extends Controller {
	public $lambda;

	public function index(&$route, &$args, &$template) {
		if (!$this->config->get('theme_' . $this->config->get('config_theme') . '_status')) {
			exit('Error: A theme has not been assigned to this store!');
		}

		// If the default theme is selected we need to know which directory its pointing to
		if ($this->config->get('config_theme') == 'default') {
			$directory = $this->config->get('theme_default_directory');
		} else {
			$directory = $this->config->get('config_theme');
		}

		if (is_file(DIR_TEMPLATE . $directory . '/template/' . $route . '.twig')) {
			$this->config->set('template_directory', $directory . '/template/');
		} elseif (is_file(DIR_TEMPLATE . 'default/template/' . $route . '.twig')) {
			$this->config->set('template_directory', 'default/template/');
		}

		// Attach to the template
		$template->addFilter('theme-override-' . $this->config->get('config_store_id'), $this);

		// If you want to modify the output of the template we add a
		$this->lambda = function (&$code) use (&$route, &$args, &$directory) {
			// If there is a theme override we should get it
			$this->load->model('design/theme');

			$theme_info = $this->model_design_theme->getTheme($route, $directory);

			if ($theme_info) {
				$code = html_entity_decode($theme_info['code'], ENT_QUOTES, 'UTF-8');
			}
		};
	}

	// Ridiculous we have to use these work around's because magic methods can not pass by reference!
	public function callback(&$code) {
		// Genius
		$lambda = $this->lambda;

		$lambda($code);
	}
}