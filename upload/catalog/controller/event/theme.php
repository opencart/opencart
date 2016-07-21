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
		
		// If the default theme is selected we need to know which directory its pointing to			
		if ($this->config->get('config_theme') == 'theme_default') {
			$theme = $this->config->get('theme_default_directory');
		} else {
			$theme = $this->config->get('config_theme');
		}		
		 
		// If there is a theme override we should get it				
		$this->load->model('design/theme');
		
		$theme_info = $this->model_design_theme->getTheme($view, $theme);
		
		if ($theme_info) {
			// include and register Twig auto-loader
			include_once DIR_SYSTEM . 'library/template/Twig/Autoloader.php';
			
			Twig_Autoloader::register();	

			// specify where to look for templates
			$loader = new \Twig_Loader_Filesystem(DIR_TEMPLATE);	
			
			// initialize Twig environment
			$twig = new \Twig_Environment($loader, array('autoescape' => false));	

			$template = $twig->createTemplate(html_entity_decode($theme_info['code'], ENT_QUOTES, 'UTF-8'));
			
			$output = $template->render($data);
		} else {
			if (is_file(DIR_TEMPLATE . $theme . '/template/' . $view . '.twig')) { 
				$view = $theme . '/template/' . $view . '.twig';
				
				$this->config->set('template_type', 'twig');
			} elseif (is_file(DIR_TEMPLATE . 'default/template/' . $view . '.twig')) {
				$view = 'default/template/' . $view . '.twig';
				
				$this->config->set('template_type', 'twig');
			} elseif (is_file(DIR_TEMPLATE . $theme . '/template/' . $view . '.tpl')) {
				$view = $theme . '/template/' . $view . '.tpl';
				
				$this->config->set('template_type', 'php');
			} elseif (is_file(DIR_TEMPLATE . 'default/template/' . $view . '.tpl')) {
				$view = 'default/template/' . $view . '.tpl';
				
				$this->config->set('template_type', 'php');
			}		
		}
	}
}