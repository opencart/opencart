<?php
namespace Opencart\Application\Controller\Extension\Opencart\Other;
class Cookiecuttr extends \Opencart\System\Engine\Controller {
	public function index() {
		if ($this->config->get('other_cookie_id') && !isset($this->request->cookie['policy'])) {
			$this->load->language('extension\opencart\other/cookiecuttr');

			$data['text_cookie'] = sprintf($this->language->get('text_cookie'), $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_cookie_id')));

			return $this->load->view('extension/opencart/other/cookiecuttr', $data);
		}
	}

	public function event(&$route, &$args, &$output) {
		echo 'hi';

	}
}