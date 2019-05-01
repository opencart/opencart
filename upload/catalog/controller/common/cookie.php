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
			setcookie('policy', time(), strtotime('+10 years'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
