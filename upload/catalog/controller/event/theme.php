<?php
class ControllerEventTheme extends Controller {
	public function index(&$view, &$data) {
		if (!$this->config->get($this->config->get('config_theme') . '_status')) {
			exit('Error: A theme has not been assigned to this store!');
		}
		
		// This is only here for compatibility with older extensions
		if (substr($view, -4) == '.tpl') {
			$view = substr($view, 0, -4);
		}
		
		if ($this->config->get('config_theme') == 'theme_default') {
			$theme = $this->config->get('theme_default_directory');
		} else {
			$theme = $this->config->get('config_theme');
		}
		
		//$this->load->model('design/theme');
		
		//$theme_info = $this->model_design_theme->getTheme($view, $theme);
		
		//if ($theme_info) {
		//	echo $theme_info['code'];
		//} else {
			if (is_file(DIR_TEMPLATE . $theme . '/template/' . $view . '.tpl')) {
				$view = $theme . '/template/' . $view;
			} else {
				$view = 'default/template/' . $view;
			}			
		//}
	}
}
