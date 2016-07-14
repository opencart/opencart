<?php
class Template {
	private $adaptor;

  	public function __construct($class) {

		if (class_exists($class)) {
			$this->adaptor = new $class();
		} else {
			throw new \Exception('Error: Could not load template class ' . $class . '!');
		}

	}

	public function set($key, $value) {
		$this->adaptor->set($key, $value);
	}

	public function render($template, $registry) {
		return $this->adaptor->render($template, $registry);
	}
}
