<?php
class ControllerCommonCookie extends Controller {
	public function index() {
		if ($this->config->get('config_cookie_id') && !isset($this->request->cookie['policy'])) {
			$this->load->language('common/cookie');

			$data['text_cookie'] = sprintf($this->language->get('text_cookie'), $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_cookie_id')));

			return $this->load->view('common/cookie', $data);
		}
	}

	public function agree() {
		$this->load->language('common/cookie');

		$json = array();

		if (!isset($this->cookie['policy'])) {
			$option = array(
				'expires'  => strtotime('+10 years'),
				'path'     => ini_get('session.cookie_path'),
				'domain'   => ini_get('session.cookie_domain'),
				'secure'   => ini_get('session.cookie_secure'),
				'httponly' => ini_get('session.cookie_httponly'),
				'SameSite' => 'strict'
			);

			// Using time as the policy value allows you to see when te policy was agreed.
			setcookie('policy', date('Y-m-d H:i:s', time()), $option);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
