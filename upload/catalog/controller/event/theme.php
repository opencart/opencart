<?php
class ControllerEventTheme extends Controller {
	public function index(&$view, &$data, &$output) {
		if (!$this->config->get($this->config->get('config_theme') . '_status')) {
			exit('Error: A theme has not been assigned to this store!');
		}
		
		// This is only here for compatibility with older extensions
		if (substr($view, -3) == 'tpl') {
			$view = substr($view, 0, -3);
		}
		
		if ($this->config->get('config_theme') == 'theme_default') {
			$theme = $this->config->get('theme_default_directory');
		} else {
			$theme = $this->config->get('config_theme');
		}
		
		if (is_file(DIR_TEMPLATE . $theme . '/template/' . $view . '.tpl')) {
			$view = $theme . '/template/' . $view;
		} else {
			$view = 'default/template/' . $view;
		}
		/*			
		// If there is a theme override we should get it				
		$this->load->model('design/theme');
		
		$theme_info = $this->model_design_theme->getTheme($view, $theme);
		
		if ($theme_info) {
			extract($data);

			ob_start();

			eval('?>' . html_entity_decode($theme_info['code']));

			$output = ob_get_clean();
		} else {
			if (is_file(DIR_TEMPLATE . $theme . '/template/' . $view . '.tpl')) {
				$view = $theme . '/template/' . $view;
			} else {
				$view = 'default/template/' . $view;
			}		
		}
		*/
	}
}
