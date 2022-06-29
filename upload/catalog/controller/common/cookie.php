<?php
namespace Opencart\Catalog\Controller\Common;
class Cookie extends \Opencart\System\Engine\Controller {
	public function index(): string {
		if ($this->config->get('config_cookie_id') && !isset($this->request->cookie['policy'])) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_cookie_id'));

			if ($information_info) {
				$this->load->language('common/cookie');

				$data['text_cookie'] = sprintf($this->language->get('text_cookie'), $this->url->link('information/information|info', 'language=' . $this->config->get('config_language') . '&information_id=' . $information_info['information_id']));

				$data['agree'] = $this->url->link('common/cookie|confirm', 'language=' . $this->config->get('config_language') . '&agree=1');
				$data['disagree'] = $this->url->link('common/cookie|confirm', 'language=' . $this->config->get('config_language') . '&agree=0');

				return $this->load->view('common/cookie', $data);
			}
		}

		return '';
	}

	public function confirm(): void {
		$json = [];

		if ($this->config->get('config_cookie_id') && !isset($this->request->cookie['policy'])) {
			$this->load->language('common/cookie');

			if (isset($this->request->get['agree'])) {
				$agree = (int)$this->request->get['agree'];
			} else {
				$agree = 0;
			}

			$option = [
				'expires'  => time() + 60 * 60 * 24 * 365,
				'path'     => !empty($this->request->server['PHP_SELF']) ? rtrim(dirname($this->request->server['PHP_SELF']), '/') . '/' : '/',
				'SameSite' => $this->config->get('config_session_samesite')
			];

			setcookie('policy', $agree, $option);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
