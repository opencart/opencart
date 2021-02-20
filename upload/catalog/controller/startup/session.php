<?php
namespace Opencart\Application\Controller\Startup;
class Session extends \Opencart\System\Engine\Controller {
	public function index() {
		$session = new \Opencart\System\Library\Session($this->config->get('session_engine'), $this->registry);
		$this->registry->set('session', $session);

		if (isset($this->request->get['route']) && substr((string)$this->request->get['route'], 0, 4) == 'api/' && isset($this->request->get['api_token'])) {
			$this->load->model('setting/api');

			$this->model_setting_api->cleanSessions();

			// Make sure the IP is allowed
			$api_info = $this->model_setting_api->getApiByToken($this->request->get['api_token']);

			if ($api_info) {
				$this->session->start($this->request->get['api_token']);

				$this->model_setting_api->updateSession($api_info['api_session_id']);
			}
		} else {
			/*
			We are adding the session cookie outside of the session class as I believe
			PHP messed up in a big way handling sessions. Why in the hell is it so hard to
			have more than one concurrent session using cookies!

			Is it not better to have multiple cookies when accessing parts of the system
			that requires different cookie sessions for security reasons.

			Also cookies can be accessed via the URL parameters. So why force only one cookie
			for all sessions!
			*/

			if (isset($this->request->cookie[$this->config->get('session_name')])) {
				$session_id = $this->request->cookie[$this->config->get('session_name')];
			} else {
				$session_id = '';
			}

			$session->start($session_id);

			$option = [
				'expires'  => 0,
				'path'     => !empty($_SERVER['PHP_SELF']) ? dirname($_SERVER['PHP_SELF']) . '/' : '',
				'secure'   => $this->request->server['HTTPS'],
				'httponly' => false,
				'SameSite' => 'Strict'
			];

			$this->response->addHeader('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

			setcookie($this->config->get('session_name'), $session->getId(), $option);
		}
	}
}