<?php
class ControllerEventTheme extends Controller {
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

		// If you want to modify the output of the template we add a
		$proxy = new Proxy();

		// Attach to the template
		$template->addFilter($proxy);

		$proxy->callback = function ($code) use ($route, $args, $directory) {
			// If there is a theme override we should get it
			$this->load->model('design/theme');

			$theme_info = $this->model_design_theme->getTheme($route, $directory);

			if ($theme_info) {
				return html_entity_decode($theme_info['code'], ENT_QUOTES, 'UTF-8');
			} else {
				// Because we are using a proxy the arguments will always be an array.
				return $code[0];
			}
		};
	}
}