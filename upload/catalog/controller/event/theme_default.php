<?php
class ControllerEventThemeDefault extends Controller {
	public function index(&$view, &$data) {
		$this->config->get('theme_default_status') == ''
		
		if ($this->config->get('config_theme') == 'theme_default') {
			if (is_file(DIR_TEMPLATE . $this->config->get('config_theme') . '/template/' . $view . '.tpl')) {
				$view = $this->config->get('config_theme') . '/template/' . $view;
			} else {
				$view = 'default/template/' . $view;
			}
		}
	}
}
