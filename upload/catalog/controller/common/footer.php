<?php
namespace Opencart\Catalog\Controller\Common;
class Footer extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		$data['informations'] = [];

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = [
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $result['information_id'])
				];
			}
		}

		$data['contact'] = $this->url->link('information/contact', 'language=' . $this->config->get('config_language'));
		$data['return'] = $this->url->link('account/returns|add', 'language=' . $this->config->get('config_language'));

		if ($this->config->get('config_gdpr_id')) {
			$data['gdpr'] = $this->url->link('information/gdpr', 'language=' . $this->config->get('config_language'));
		} else {
			$data['gdpr'] = '';
		}

		$data['sitemap'] = $this->url->link('information/sitemap', 'language=' . $this->config->get('config_language'));
		$data['manufacturer'] = $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language'));
		$data['voucher'] = $this->url->link('checkout/voucher', 'language=' . $this->config->get('config_language'));

		if ($this->config->get('config_affiliate_status')) {
			$data['affiliate'] = $this->url->link('account/affiliate', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : ''));
		} else {
			$data['affiliate'] = '';
		}

		$data['special'] = $this->url->link('product/special', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : ''));
		$data['account'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : ''));
		$data['order'] = $this->url->link('account/order', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : ''));
		$data['wishlist'] = $this->url->link('account/wishlist', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : ''));
		$data['newsletter'] = $this->url->link('account/newsletter', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : ''));

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['HTTP_X_REAL_IP'])) {
				$ip = $this->request->server['HTTP_X_REAL_IP'];
			} elseif (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['bootstrap'] = 'catalog/view/javascript/bootstrap/js/bootstrap.bundle.min.js';

		$data['scripts'] = $this->document->getScripts('footer');

		$data['cookie'] = $this->load->controller('common/cookie');

		return $this->load->view('common/footer', $data);
	}
}
