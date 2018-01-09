<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact', 'language=' . $this->config->get('config_language'));
		$data['return'] = $this->url->link('account/return/add', 'language=' . $this->config->get('config_language'));
		$data['sitemap'] = $this->url->link('information/sitemap', 'language=' . $this->config->get('config_language'));
		$data['tracking'] = $this->url->link('information/tracking', 'language=' . $this->config->get('config_language'));
		$data['manufacturer'] = $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language'));
		$data['voucher'] = $this->url->link('account/voucher', 'language=' . $this->config->get('config_language'));
		$data['affiliate'] = $this->url->link('affiliate/login', 'language=' . $this->config->get('config_language'));
		$data['special'] = $this->url->link('product/special', 'language=' . $this->config->get('config_language'));
		$data['account'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));
		$data['order'] = $this->url->link('account/order', 'language=' . $this->config->get('config_language'));
		$data['wishlist'] = $this->url->link('account/wishlist', 'language=' . $this->config->get('config_language'));
		$data['newsletter'] = $this->url->link('account/newsletter', 'language=' . $this->config->get('config_language'));

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		$data['address'] = $this->config->get('config_address');
		$data['open'] = $this->config->get('config_open');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['fax'] = $this->config->get('config_fax');
		$data['email'] = $this->config->get('config_email');

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['HTTP_X_REAL_IP'])) {
				$ip = $this->request->server['HTTP_X_REAL_IP'];
			} else if (isset($this->request->server['REMOTE_ADDR'])) {
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

		$data['scripts'] = $this->document->getScripts('footer');

		return $this->load->view('common/footer', $data);
	}
}
