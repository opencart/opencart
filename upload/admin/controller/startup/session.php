<?php
namespace Opencart\Application\Controller\Startup;
class Session extends \Opencart\System\Engine\Controller {
	public function index() {
		$session = new \Opencart\System\Library\Session($this->config->get('session_engine'), $this->registry);
		$this->registry->set('session', $session);

		if (isset($this->request->cookie[$this->config->get('session_name')])) {
			$session_id = $this->request->cookie[$this->config->get('session_name')];
		} else {
			$session_id = '';
		}

		$session->start($session_id);

		// Setting the cookie path to the store front so admin users can login to cutomers accounts.
		$path = dirname($_SERVER['PHP_SELF']);

		$path = substr($path, 0, strrpos($path, '/')) . '/';

		// Require higher security for session cookies
		$option = [
			'expires'  => time() + $this->config->get('session_expire'),
			'path'     => !empty($_SERVER['PHP_SELF']) ? $path : '',
			'secure'   => $this->request->server['HTTPS'],
			'httponly' => false,
			'SameSite' => 'Strict'
		];

		setcookie($this->config->get('session_name'), $session->getId(), $option);
	}
}