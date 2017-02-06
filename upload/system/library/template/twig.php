<?php
namespace Template;
final class Twig {
	private $twig;
	private $data = array();
	
	public function __construct() {
		// include and register Twig auto-loader
		include_once DIR_SYSTEM . 'library/template/Twig/Autoloader.php';
		
		\Twig_Autoloader::register();	
		
		// specify where to look for templates
		$loader = new \Twig_Loader_Filesystem(DIR_TEMPLATE);	
		
		// initialize Twig environment
		$this->twig = new \Twig_Environment($loader, array('autoescape' => false));			
	}	
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function render($template) {
		try {
			$template = $this->twig->loadTemplate($template . '.twig');
		} catch(\Exception $exception) {
			echo $template;
			echo $exception->getMessage();
			die();
		}


		try {
			// load template

			return $template->render($this->data);
		} catch (\Exception $e) {
			echo $template;
			echo $e->getMessage();

			die();
			trigger_error('Error: Could not load template ' . $template . '!');
			exit();
		}

//		} catch(Twig_Error_Loader $e) {
//				//throw new \Exception('Unable to load template ' . $template);
//				echo 'Unable to load template ' . $template;
//		} catch(Twig_Error_Syntax $e) {
//				//throw new \Exception($e->getMessage());
//				echo $e->getMessage();
//		} catch(Twig_Error_Runtime $e) {
//				//throw new \Exception($e->getMessage());
//				echo $e->getMessage();
//		}
	}	
}
