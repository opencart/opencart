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

		$this->load->model('design/theme');
		$theme_info = $this->model_design_theme->getTheme($route, $directory);
		if ($theme_info) {
			$template = html_entity_decode($theme_info['code'], ENT_QUOTES, 'UTF-8');
			$this->config->set('template_directory', $directory . '/template/');
			$this->config->set('template_engine','twig');
		} else if (is_file(DIR_TEMPLATE . $directory . '/template/' . $route . '.twig')) {
			$this->config->set('template_directory', $directory . '/template/');
			$this->config->set('template_engine','twig');
		} else if (is_file(DIR_TEMPLATE . $directory . '/template/' . $route . '.tpl')) {
			$this->config->set('template_directory', $directory . '/template/');
			$this->config->set('template_engine','template');
		} elseif (is_file(DIR_TEMPLATE . 'default/template/' . $route . '.twig')) {
			$this->config->set('template_directory', 'default/template/');
			$this->config->set('template_engine','twig');
		} elseif (is_file(DIR_TEMPLATE . 'default/template/' . $route . '.tpl')) {
			$this->config->set('template_directory', 'default/template/');
			$this->config->set('template_engine','template');
		} else {
			exit("Error: Unable to find template for '$route'!");
		}
	}

}
