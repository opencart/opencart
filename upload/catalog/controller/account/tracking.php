<?php
class ControllerAffiliateTracking extends Controller {
	public function index() {
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/tracking', '', true);

			$this->response->redirect($this->url->link('affiliate/login', '', true));
		}

		$this->load->language('affiliate/tracking');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('affiliate/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('affiliate/tracking', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_description'] = sprintf($this->language->get('text_description'), $this->config->get('config_name'));

		$data['entry_code'] = $this->language->get('entry_code');
		$data['entry_generator'] = $this->language->get('entry_generator');
		$data['entry_link'] = $this->language->get('entry_link');

		$data['help_generator'] = $this->language->get('help_generator');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['code'] = $this->affiliate->getCode();

		$data['continue'] = $this->url->link('affiliate/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('affiliate/tracking', $data));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/product');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'link' => str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $result['product_id'] . '&tracking=' . $this->affiliate->getCode()))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}