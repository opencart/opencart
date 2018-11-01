<?php
class ControllerCommonCookie extends Controller {
	public function index() {
		if ($this->config->get('config_cookie_status') && !isset($this->request->cookie['cookie'])) {
			$this->load->language('common/cookie');

			$data['text_cookie'] = sprintf($this->language->get('text_cookie'), $this->url->link('information/information/info', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_language')));

			return $this->load->view('common/cookie', $data);
		}
	}

	public function agree() {
		if (isset($this->cookie[''])) {
			setcookie();
		}
	}
}