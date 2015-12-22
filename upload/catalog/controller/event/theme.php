<?php
class ControllerEventTheme extends Controller {
	public function index(&$view, &$data) {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_theme') . '/template/' . $view . '.tpl')) {
			$view = $this->config->get('config_theme') . '/template/' . $view;
		} else {
			$view = 'theme_default/template/' . $view;
		}
	}
}
