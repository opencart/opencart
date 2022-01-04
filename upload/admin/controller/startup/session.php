<?php
namespace Opencart\Admin\Controller\Startup;
class Session extends \Opencart\System\Engine\Controller {
	public function index(): void {
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

		// Update the session lifetime
		if ($this->config->get('config_session_expire')) {
			$this->config->set('session_expire', $this->config->get('config_session_expire'));
		}

		// Require higher security for session cookies
		$option = [
			'expires'  => $this->config->get('config_session_expire') ? time() + (int)$this->config->get('config_session_expire') : 0,
			'path'     => !empty($_SERVER['PHP_SELF']) ? $path : '',
			'secure'   => $this->request->server['HTTPS'],
			'httponly' => false,
			'SameSite' => $this->config->get('session_samesite')
		];

		setcookie($this->config->get('session_name'), $session->getId(), $option);
	}
}