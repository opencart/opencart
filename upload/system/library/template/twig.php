<?php
namespace Template;
final class Twig {
	private $twig;
	private $filters = array();
	private $data = array();

	public function addFilter($value) {
		$this->filters[] = $value;
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($template, $cache = false) {
		// include and register Twig auto-loader
		\Twig_Autoloader::register();

		// Initialize Twig environment
		$config = array('autoescape' => false);

		if ($cache) {
			$config['cache'] = DIR_CACHE;
		}

		// Specify where to look for templates
		$this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(DIR_TEMPLATE), $config);

		try {
			$file = DIR_TEMPLATE . $template . '.twig';

			$code = file_get_contents($file);

			foreach ($this->filters as $filter) {
				$code = $filter->callback($code);
			}

			return $this->twig->createTemplate($code)->render($this->data);
		} catch (Exception $e) {
			trigger_error('Error: Could not load template ' . $template . '!');
			exit();
		}
	}
}