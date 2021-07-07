<?php
namespace Opencart\Catalog\Controller\Account;
class Tracking extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if (!$this->config->get('config_affiliate_status')) {
			$this->response->redirect($this->url->link('account/account', 'language=' . $this->config->get('config_language')));
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/tracking', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->load->model('account/affiliate');

		$affiliate_info = $this->model_account_affiliate->getAffiliate($this->customer->getId());

		if (!$affiliate_info) {
			$this->response->redirect($this->url->link('account/account', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('account/tracking');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/tracking', 'language=' . $this->config->get('config_language'))
		];

		$data['text_description'] = sprintf($this->language->get('text_description'), $this->config->get('config_name'));

		$data['code'] = $affiliate_info['tracking'];

		$data['continue'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/tracking', $data));
	}

	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			if (isset($this->request->get['tracking'])) {
				$tracking = $this->request->get['tracking'];
			} else {
				$tracking = '';
			}

			$this->load->model('catalog/product');

			$filter_data = [
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			];

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'link' => str_replace('&amp;', '&', $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $result['product_id'] . '&tracking=' . $tracking))
				];
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}