<?php
class Language {
	private $default = 'english';
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

	public function all() {
		return $this->data;
	}
	
	public function load($filename) {
		$_ = array();

		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

		if (file_exists($file)) {
			require($file);
		}

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';

		if (file_exists($file)) {
			require($file);
		}

		$this->data = array_merge($this->data, $_);

		return $this->data;
	}
}
