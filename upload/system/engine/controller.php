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
abstract class Controller {
	protected $registry;	
	protected $id;
	protected $layout;
	protected $template;
	protected $children = array();
	protected $data = array();
	protected $output;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
	
	protected function forward($route, $args = array()) {
		return new Action($route, $args);
	}

	protected function redirect($url, $status = 302) {
		header('Status: ' . $status);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
		exit();
	}

	protected function getChild($child, $args = array()) {
		$action = new Action($child, $args);

		if (file_exists($action->getFile())) {
			require_once($action->getFile());

			$class = $action->getClass();

			$controller = new $class($this->registry);

			$controller->{$action->getMethod()}($action->getArgs());

			return $controller->output;
		} else {
			trigger_error('Error: Could not load controller ' . $child . '!');
			exit();
		}
	}

	protected function render() {
		foreach ($this->children as $child) {
			$this->data[basename($child)] = $this->getChild($child);
		}

		if (file_exists(DIR_TEMPLATE . $this->template)) {
			extract($this->data);

			ob_start();

			require(DIR_TEMPLATE . $this->template);

			$this->output = ob_get_contents();

			ob_end_clean();

			return $this->output;
		} else {
			trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
			exit();
		}
	}
}
?>