<?php
class ControllerEventTheme extends Controller {
	public function index(&$view, &$data) {
		if (!$this->config->get($this->config->get('config_theme') . '_status')) {
			exit('Error: A theme has not been assigned to this store!');
		}
		
		if (is_file(DIR_TEMPLATE . $this->config->get('config_theme') . '/template/' . $view . '.tpl')) {
			$view = $this->config->get('config_theme') . '/template/' . $view;
		} else {
			$view = 'theme_default/template/' . $view;
		}
	}
}
