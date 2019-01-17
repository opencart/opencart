<?php
namespace Template;
final class Twig {
	private $data = array();

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($template, $cache = true, $template_code=null) {
		// Initialize Twig environment
		$config = array(
			'autoescape'  => false,
			'debug'       => false,
			'auto_reload' => true,
			'cache'       => ($cache) ? DIR_CACHE.'twig/' : false
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
		if ($template_code) {
			// render from modified template code
			$loader = new \Twig_Loader_Array( array($template.'.twig' => $template_code) );
		} else {
			// render from template file
			$loader = new \Twig_Loader_Filesystem( DIR_TEMPLATE );
		}
		try {
			$twig = new \Twig_Environment( $loader, $config );
			return $twig->render( $template.'.twig', $this->data );
		} catch (Twig_Error_Syntax $e) {
			trigger_error('Error: Could not load template ' . $template . '!');
			exit();
		}
	}
}
