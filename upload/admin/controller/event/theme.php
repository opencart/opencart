<?php
class ControllerEventTheme extends Controller {
	public function index(&$view, &$data) {
		// This is only here for compatibility with old templates
		if (substr($view, -3) == 'tpl') {
			$view = substr($view, 0, -3);
		}
		
		if (is_file(DIR_TEMPLATE . $view . '.twig')) {
			$this->config->set('template_engine', 'twig');
		} elseif (is_file(DIR_TEMPLATE . $view . '.tpl')) {
			$this->config->set('template_engine', 'php');
		}		
	}
}
