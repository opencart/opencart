<?php
class ControllerEventTheme extends Controller {
	public function index(&$view, &$data) {
		if (is_file(DIR_TEMPLATE . $view . '.twig')) {
			$this->config->set('template_engine', 'twig');
		} elseif (is_file(DIR_TEMPLATE . $view . '.tpl')) {
			$this->config->set('template_engine', 'php');
		}		
	}
}
