<?php
class ControllerCommonCookie extends Controller {
	public function index() {
		if ($this->config->get('config_cookie_status') && !isset($this->request->cookie['cookie'])) {
			$this->load->language('common/cookie');

			$data['text_cookie'] = sprintf($this->language->get('text_cookie'), ';');

			$data['link'] = $this->url->link('common/cookie', 'language=' . $this->config->get('config_language'));

			return $this->load->view('common/cookie', $data);
		}
	}

	public function agree() {
		if (isset($this->cookie[''])) {

		}
	}

	public function terms() {
		if (isset($this->cookie[''])) {
			setcookie();
		}
	}
}