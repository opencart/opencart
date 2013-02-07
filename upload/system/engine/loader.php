<?php
/**
 * @property Loader $load Loads whatever you want
 * @property Config $config Access to congiguration
 * @property Url $url Helpes to generate links
 * @property Log $log Log helper
 * @property Request $request Access to request data (POST, GET, COOKIE, FILES, SERVER)
 * @property Response $response Some functionality to work with response
 * @property Cache $cache Access to cache
 * @property Session $session Access to Session
 * @property Language $language Language helper
 * @property Document $document Helper to work with html DOM document
 * @property Customer $customer Access to customers data
 * @property Affiliate $affiliate 
 * @property Currency $currency 
 * @property Tax $tax Tax helper
 * @property Weight $weight Weight helper
 * @property Length $length Length helper
 * @property Cart $cart Access to cart data
 * @property Encryption $encryption Encription helper
 */
final class Loader {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}

	public function library($library) {
		$file = DIR_SYSTEM . 'library/' . $library . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $library . '!');
			exit();
		}
	}

	public function helper($helper) {
		$file = DIR_SYSTEM . 'helper/' . $helper . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load helper ' . $helper . '!');
			exit();
		}
	}

	public function model($model) {
		$file  = DIR_APPLICATION . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) { 
			include_once($file);

			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load model ' . $model . '!');
			exit();
		}
	}

	public function database($driver, $hostname, $username, $password, $database) {
		$file  = DIR_SYSTEM . 'database/' . $driver . '.php';
		$class = 'Database' . preg_replace('/[^a-zA-Z0-9]/', '', $driver);

		if (file_exists($file)) {
			include_once($file);

			$this->registry->set(str_replace('/', '_', $driver), new $class($hostname, $username, $password, $database));
		} else {
			trigger_error('Error: Could not load database ' . $driver . '!');
			exit();
		}
	}

	public function config($config) {
		$this->config->load($config);
	}

	public function language($language) {
		return $this->language->load($language);
	}
} 
?>
