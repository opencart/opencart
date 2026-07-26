<?php
namespace Opencart\System\Library\Template;
/**
 * Class Twig
 *
 * @package Opencart\System\Library\Template
 */
class Twig {
	/**
	 * @var string
	 */
	protected string $root;
	/**
	 * @var \Twig\Loader\FilesystemLoader
	 */
	protected \Twig\Loader\FilesystemLoader $loader;
	/**
	 * @var string
	 */
	protected string $directory;
	/**
	 * @var array<string, string>
	 */
	protected array $path = [];

	/**
	 * Constructor
	 */
	public function __construct() {
		// Unfortunately, we have to set the web root directory as the base since Twig confuses which template cache to use.
		$this->root = substr(DIR_OPENCART, 0, -1);

		// We have to add the C directory as the base directory because twig can only accept the first namespace/,
		// rather than a multiple namespace system, which took me less than a minute to write. If symphony is like
		// this, then I have no idea why people use the framework.
		$this->loader = new \Twig\Loader\FilesystemLoader('./', $this->root);
	}

	/**
	 * Add Path
	 *
	 * @param string $namespace
	 * @param string $directory
	 *
	 * @return void
	 */
	public function addPath(string $namespace, string $directory = ''): void {
		if (!$directory) {
			$this->directory = $namespace;
		} else {
			$this->path[$namespace] = $directory;
		}
	}

	/**
	 * Render
	 *
	 * @param string               $filename
	 * @param array<string, mixed> $data
	 * @param string               $code
	 *
	 * @return string
	 */
	public function render(string $filename, array $data = [], string $code = ''): string {
		/*
		 * FYI: To all the Twig lovers out there!
		 * The Twig syntax is good, but the implementation and the available methods is a joke!
		 *
		 * All the Symfony developer has done is create a garbage framework by putting 3rd party scripts into DI containers.
		 * If symphony is like this, then I have no idea why people use the framework.
		 *
		 * The fact that this system cache is just compiling php into more php code instead of html, is a disgrace!
		 */

		$namespace = '';

		$parts = explode('/', $filename);

		$file = '';

		foreach ($parts as $part) {
			if (!$namespace) {
				$namespace .= $part;
			} else {
				$namespace .= '/' . $part;
			}

			if (isset($this->path[$namespace])) {
				$base = $this->path[$namespace] . substr($filename, strlen($namespace) + 1);
			}
		}

		if (!isset($base)) {
			$base = $this->directory . $filename;
		}

		// If $base is an absolute path, make it relative to $this->root
		if (strpos($base, '/') === 0) {
			$base = substr($base, strlen($this->root) + 1);
		}

		// Check for .html first, then .twig
		if (is_file($this->root . '/' . $base . '.html')) {
			$file = $base . '.html';
		} elseif (is_file($this->root . '/' . $base . '.twig')) {
			$file = $base . '.twig';
		} else {
			$file = $base . '.html';
		}

		// We have to remove the root web directory.
		if (strpos($file, $this->root) === 0) {
			$file = substr($file, strlen($this->root) + 1);
		}

		if ($code) {
			// render from modified template code
			$loader = new \Twig\Loader\ArrayLoader([$file => $code]);
		} else {
			$loader = $this->loader;
		}

		try {
			// Initialize Twig environment
			$config = [
				'charset'     => 'utf-8',
				'autoescape'  => false,
				'debug'       => true,
				'auto_reload' => true,
				'cache'       => DIR_CACHE . 'template/'
			];

			$twig = new \Twig\Environment($loader, $config);

			if ($config['debug']) {
				$twig->addExtension(new \Twig\Extension\DebugExtension());
			}

			$twig->addExtension(new \Opencart\System\Library\Template\TwigExtension());

			return $twig->render($file, $data);
		} catch (\Twig\Error\SyntaxError | \Twig\Error\RuntimeError | \Twig\Error\LoaderError $e) {
			throw new \Exception('Error: Could not load template ' . $filename . ': ' . $e->getMessage());
		}
	}
}
