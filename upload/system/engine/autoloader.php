<?php
Class Autoloader {
	private $data = array();

	public function addLoader($directory, $prefix) {
		$this->data[$prefix] = $directory;
	}

	public function load($class) {
		foreach ($this->data as $key => $value) {
			if (substr($class, 0, strlen($key)) == $key) {
				include_once($value);

				return true;
			}
		}

		return false;
	}
}