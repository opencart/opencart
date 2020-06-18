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
				'max-age'  => strtotime('+10 years'),
				'path'     => '/',
				'SameSite' => 'lax'
			);

			// Using time as the policy value allows you to see when te policy was agreed.
			oc_setcookie('policy', time(), $option);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
