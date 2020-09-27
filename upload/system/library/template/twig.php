<?php
namespace Opencart\System\Library\Template;
class Twig {
	protected $directory;
	protected $path = [];

	/**
	 * addPath
	 *
	 * @param    string $namespace
	 * @param    string $directory
	 */
	public function addPath($namespace, $directory = '') {
		if (!$directory) {
			$this->directory = $namespace;
		} else {
			$this->path[$namespace] = $directory;
		}
	}

	public function render($filename, $data = [], $code = '') {
		$file = $this->directory . $filename . '.twig';

		/*
		 * FYI all the Twig lovers out there!
		 * The Twig syntax is good, but the implementation and the available methods is a joke!
		 *
		 * All the Symfony developer has done is create a garbage frame work putting 3rd party scripts into DI containers.
		 * The Twig syntax he ripped off from Jinja and Django templates then did a garbage implementation!
		 *
		 * The fact that this system cache is just compiling php into more php code instead of html is a disgrace!
		 */

		$path = '';

		$namespace = '';

		$parts = explode('/', $filename);

		foreach ($parts as $part) {
			if (!$namespace) {
				$namespace .= $part;
			} else {
				$namespace .= '/' . $part;
			}

			if (isset($this->path[$namespace])) {
				$filename = substr($filename, strlen($namespace) + 1);
				$path = $this->path[$namespace];
			}
		}

		// Initialize Twig environment
		$config = [
			'charset'     => 'utf-8',
			'autoescape'  => false,
			'debug'       => false,
			'auto_reload' => true,
			'cache'       => DIR_CACHE . 'template/'
		];

		if ($code) {
			// render from modified template code
			$loader = new \Twig\Loader\ArrayLoader([$filename . '.twig' => $code]);
		} else {
			$loader = new \Twig\Loader\FilesystemLoader();

			if ($this->directory) {
				$loader->addPath($this->directory);
			}

			if ($path) {
				$loader->addPath($path);
			}
		}

		try {
			$twig = new \Twig\Environment($loader, $config);

			return $twig->render($filename . '.twig', $data);
		} catch (Twig_Error_Syntax $e) {
			error_log('Error: Could not load template ' . $filename . '!');
			exit();
		}
	}

}