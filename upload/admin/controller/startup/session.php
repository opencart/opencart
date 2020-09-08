<?php
namespace Opencart\Application\Controller\Startup;
class Session extends \Opencart\System\Engine\Controller {
	public function index() {
		// Session
		if (isset($this->request->cookie[$this->config->get('session_name')])) {
			$session_id = $this->request->cookie[$this->config->get('session_name')];
		} else {
			$session_id = '';
		}

		$this->session->start($session_id);

		// Require higher security for session cookies
		$option = [
			'max-age'  => time() + $this->config->get('session_expire'),
			'path'     => !empty($_SERVER['PHP_SELF']) ? dirname($_SERVER['PHP_SELF']) . '/' : '',
			'domain'   => $this->request->server['HTTP_HOST'],
			'secure'   => $this->request->server['HTTPS'],
			'httponly' => false,
			'SameSite' => 'strict'
		);

		oc_setcookie($this->config->get('session_name'), $this->session->getId(), $option);
	}
}
