<?php
class ControllerEventTheme extends Controller {
	public function index(&$view, &$data) {
		// This is only here for compatibility with old templates
		if (substr($view, -3) == 'tpl') {
			$view = substr($view, 0, -3);
		}
		
		if (is_file(DIR_TEMPLATE . 'template/' . $view . '.twig')) {
			$view = 'template/' . $view . '.twig'; 
			
			$this->config->set('template_type', 'twig');
		} elseif (is_file(DIR_TEMPLATE . 'template/' . $view . '.tpl')) {
			$view = 'template/' . $view . '.tpl'; 
			
			$this->config->set('template_type', 'php');
		}		
	}
}
