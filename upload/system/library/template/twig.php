<?php
namespace Template;
final class Twig {
	private $filters = array();
	private $data = array();

	public function addFilter($key, $value) {
		$this->filters[$key] = $value;
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($template, $cache = true) {
		// Initialize Twig environment
		$config = array(
			'autoescape' => false,
			'debug'      => false
		);

		/*
		 * FYI all the Twig lovers out there!
		 * The Twig syntax is good, but the implementation and the available methods is a joke!
		 *
		 * All the Symfony developer has done is create a garbage frame work putting 3rd party scripts into DI containers.
		 * The Twig syntax he ripped off from Jinja and Django templates then did a garbage implementation!
		 *
		 * The fact that this system cache is just compiling php into more php code instead of html is a disgrace!
		 */

		// 1. Generate namespace for filters used to product the output
		$namespace = preg_replace('/[^0-9a-zA-Z_]/', '_', implode('_', array_keys($this->filters)));

		$loader = new \Twig_Loader_Filesystem(DIR_TEMPLATE);

		// 2. Initiate Twig Environment
		$twig = new \Twig_Environment($loader, $config);

		// 3. Create an anonymous cache class as twig will not all us to generate a key based on custom keys
		if ($cache) {
			$cache = new class(DIR_CACHE, $options = 0, $namespace) extends \Twig_Cache_Filesystem {
				private $directory;
				private $options;

				public function __construct($directory, $options = 0, $namespace) {
					$this->directory = rtrim($directory, '\/') . '/';
					$this->options = $options;
					$this->namespace = $namespace;
				}

				public function generateKey($name, $className) {
					$hash = hash('sha256', $name . $className . $this->namespace);

					return $this->directory . $hash[0] . $hash[1] . '/' . $hash . '.php';
				}
			};

			$twig->setCache($cache);
		}

		// 4. Get template class name
		$template_class_name = $twig->getTemplateClass($template . '.twig');

		// 5. Get cache file path
		$cache_file = $twig->getCache(false)->generateKey(DIR_TEMPLATE . $template . '.twig', $template_class_name);

		try {
			// 6. Check if cached file exists with modifications if not we create one
			if (!is_file($cache_file)) {
				// 7. Get the source code using the source
				$source = $twig->getLoader()->getSourceContext($template . '.twig');

				$code = $source->getCode();

				// 8. Run the code through the filters
				foreach ($this->filters as $key => $filter) {
					$filter->callback($code);
				}

				// 9. Compile the source
				$output = $twig->compileSource(new \Twig_Source($code, $source->getName(), $source->getPath()));

				// 10. Write the output to a cache file
				$twig->getCache(false)->write($cache_file, $output);
			}

			// 11. Load the cached file into array of loaded templates
			$twig->getCache(false)->load($cache_file);

			return $twig->render($template . '.twig', $this->data);
		} catch (Twig_Error_Syntax $e) {
			trigger_error('Error: Could not load template ' . $template . '!');
			exit();
		}
	}
}