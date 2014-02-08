<?php

class Twig {

	protected $environment;

	public function __construct() {

		$loader = new Twig_Loader_Filesystem(DIR_TEMPLATE);
		$this->environment = new Twig_Environment($loader, array(
			'autoescape' => false,
			'cache' => DIR_CACHE . 'templates_c',
			'strict_variables' => false,
		));
	}

	public function render($template, $data) {
		return $this->environment->render($template, $data);
	}
}
