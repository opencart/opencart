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
final class Front {
	protected $registry;
	protected $pre_action = array();
	protected $error;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function addPreAction($pre_action) {
		$this->pre_action[] = $pre_action;
	}

	public function dispatch($action, $error) {
		$this->error = $error;

		foreach ($this->pre_action as $pre_action) {
			$result = $this->execute($pre_action);

			if ($result) {
				$action = $result;

				break;
			}
		}

		while ($action) {
			$action = $this->execute($action);
		}
	}

	private function execute($action) {
		if (file_exists($action->getFile())) {
			require_once($action->getFile());

			$class = $action->getClass();

			$controller = new $class($this->registry);

			if (is_callable(array($controller, $action->getMethod()))) {
				$action = call_user_func_array(array($controller, $action->getMethod()), $action->getArgs());
			} else {
				$action = $this->error;

				$this->error = '';
			}
		} else {
			$action = $this->error;

			$this->error = '';
		}

		return $action;
	}
}
?>