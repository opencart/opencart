<?php
class ControllerOverrideTemplate extends Controller {
	public function index(&$view, &$data) {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $view)) {
			$view = $this->config->get('config_template') . '/template/' . $view;
		} else {
			$view = 'default/template/' . $view;
		}
	}
}
