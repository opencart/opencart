<?php
namespace Template;
final class Twig {
	protected $code;
	private $data = array();

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function load($filename) {
		$file = DIR_TEMPLATE . $filename . '.twig';

		if (is_file($file)) {
			$this->code = file_get_contents($file);
		} else {
			throw new \Exception('Error: Could not load template ' . $file . '!');
			exit();
		}
	}

	public function render($filename, $code = '') {
		// Initialize Twig environment
		$config = array(
			'autoescape'  => false,
			'debug'       => false,
			'auto_reload' => true,
			'cache'       => DIR_CACHE . 'template/'
		);


		if (!$code) {
			$this->load($filename);
		} else {
			$this->code = $code;
		}




		if (!$code) {
			//load

		}

		/*
		 * FYI all the Twig lovers out there!
		 * The Twig syntax is good, but the implementation and the available methods is a joke!
		 *
		 * All the Symfony developer has done is create a garbage frame work putting 3rd party scripts into DI containers.
		 * The Twig syntax he ripped off from Jinja and Django templates then did a garbage implementation!
		 *
		 * The fact that this system cache is just compiling php into more php code instead of html is a disgrace!
		 */

		if ($code) {
			// render from modified template code
			$loader = new \Twig_Loader_Array(array($filename . '.twig' => $this->code));
		} else {
			// render from template file
			$loader = new \Twig_Loader_Filesystem(DIR_TEMPLATE);
		}

		// 2. Initiate Twig Environment
		//$twig = new \Twig_Environment($loader, $config);


		try {
			$twig = new \Twig_Environment($loader, $config);

			$twig->parse($twig->tokenize(new \Twig\Source($template)));

			return $twig->render($filename . '.twig', $this->data);
		} catch (Twig_Error_Syntax $e) {
			trigger_error('Error: Could not load template ' . $filename . '!');
			exit();
		}
	}

	public function compile($filename, $code) {

	}
}