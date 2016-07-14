<?php
class ControllerEventTheme extends Controller {
	public function index(&$view, &$data) {
		if (is_file(DIR_TEMPLATE . $view . '.tpl')) {
			$view .= '.tpl'; 
			
			$this->config->set('template_type', 'php');
		} elseif (is_file(DIR_TEMPLATE . $view . '.twig')) {
			$view .= '.twig'; 
			
			$this->config->set('template_type', 'twig');
		}		
	}
}
