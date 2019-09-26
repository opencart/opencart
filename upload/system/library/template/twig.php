<?php
namespace Template;
final class Twig {
	protected $data = array();

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($filename, $code = '') {
		if (!$code) {
			$file = DIR_TEMPLATE . $filename . '.twig';

			if (is_file($file)) {
				$code = file_get_contents($file);
			} else {
				throw new \Exception('Error: Could not load template ' . $file . '!');
				exit();
			}
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

		// render from modified template code
		if ($code) {
			// Initialize Twig environment
			$config = array(
				'autoescape'  => false,
				'debug'       => false,
				'auto_reload' => true,
				'cache'       => DIR_CACHE . 'template/'
			);

			$loader = new \Twig_Loader_Array(array($filename . '.twig' => $code));

			try {
				$twig = new \Twig_Environment($loader, $config);

				return $twig->render($filename . '.twig', $this->data);
			} catch (Twig_Error_Syntax $e) {
				trigger_error('Error: Could not load template ' . $filename . '!');
				exit();
			}
		}
	}
}