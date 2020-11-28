<?php
namespace Opencart\Application\Controller\Common;
class Cookie extends \Opencart\System\Engine\Controller {
	public function index() {
		if ($this->config->get('config_cookie_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_cookie_id'));

			if ($information_info) {
				$this->load->language('common/cookie');

				$data['text_cookie'] = sprintf($this->language->get('text_cookie'), $this->url->link('information/information|info', 'language=' . $this->config->get('config_language') . '&information_id=' . $information_info['information_id']));

				$data['agree'] = $this->url->link('account/cookie|confirm', 'language=' . $this->config->get('config_language') . '&agree=1');
				$data['disagree'] = $this->url->link('account/cookie|confirm', 'language=' . $this->config->get('config_language') . '&agree=0');

				return $this->load->view('common/cookie', $data);
			}
		}
	}

	public function confirm() {
		if ($this->request->get['agree']) {
			$agree = $this->request->get['agree'];
		} else {
			$agree = 0;
		}

		$this->load->model('account/cookie');

		$this->model_account_cookie->addCookie($this->request->server['REMOTE_ADDR'], $this->config->get('config_language_code'), $agree);
	}
}
