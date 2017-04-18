<?php
class Language {
	private $default = 'en-gb';
	private $directory;
	private $data = array();

	public function __construct($directory = '') {
		$this->directory = $directory;
	}

	public function get($lang, $args = array(), $data = array()) {
		$args = (array)$args;

		if (is_array($lang)) {
			foreach ($lang as $prefix => $names) {
				foreach ($names as $name) {
					$data[$prefix . '_' . $name] = $this->get($prefix . '_' . $name, (isset($args[$prefix][$name]) ? $args[$prefix][$name] : array()));
				}
			}
		} else {
			$data = (isset($this->data[$lang]) ? $this->data[$lang] : $lang);

			if (!empty($args)) {
				$data = vsprintf($data, $args);
			}
		}

		return $data;
	}
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
			
	public function load($filename, &$data = array()) {
		$_ = array();

		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

		if (is_file($file)) {
			require($file);
		}

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';
		
		if (is_file($file)) {
			require($file);
		} 

		$this->data = array_merge($this->data, $_);

		$data = array_merge($data, $this->data);

		return $this->data;
	}
}
