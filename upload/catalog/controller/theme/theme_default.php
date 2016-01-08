<?php
class ControllerThemeThemeDefault extends Controller {
	public function index(&$view, &$data) {
		if ($this->config->get('config_theme') == 'theme_default') {
			if (is_file(DIR_TEMPLATE . $this->config->get('theme_default_directory') . '/template/' . $view . '.tpl')) {
				$view = $this->config->get('theme_default_directory') . '/template/' . $view;
			} else {
				$view = 'default/template/' . $view;
			}
		}
	}
}
