<?php
class ControllerExtensionModuleEbayListing extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/ebay_listing');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_ebay_listing', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->cache->delete('ebay');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/ebay_listing', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/ebay_listing', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['ebay_listing_username'])) {
			$data['ebay_listing_username'] = $this->request->post['ebay_listing_username'];
		} else {
			$data['ebay_listing_username'] = $this->config->get('ebay_listing_username');
		}

		if (isset($this->request->post['ebay_listing_keywords'])) {
			$data['ebay_listing_keywords'] = $this->request->post['ebay_listing_keywords'];
		} else {
			$data['ebay_listing_keywords'] = $this->config->get('ebay_listing_keywords');
		}

		if (isset($this->request->post['ebay_listing_description'])) {
			$data['ebay_listing_description'] = $this->request->post['ebay_listing_description'];
		} else {
			$data['ebay_listing_description'] = $this->config->get('ebay_listing_description');
		}

		if (isset($this->request->post['ebay_listing_limit'])) {
			$data['ebay_listing_limit'] = $this->request->post['ebay_listing_limit'];
		} elseif ($this->config->has('ebay_listing_limit')) {
			$data['ebay_listing_limit'] = $this->config->get('ebay_listing_limit');
		} else {
			$data['ebay_listing_limit'] = 5;
		}

		if (isset($this->request->post['ebay_listing_width'])) {
			$data['ebay_listing_width'] = $this->request->post['width'];
		} elseif ($this->config->has('ebay_listing_width')) {
			$data['ebay_listing_width'] = $this->config->get('ebay_listing_width');
		} else {
			$data['ebay_listing_width'] = 200;
		}

		if (isset($this->request->post['ebay_listing_height'])) {
			$data['ebay_listing_height'] = $this->request->post['ebay_listing_height'];
		} elseif ($this->config->has('ebay_listing_height')) {
			$data['ebay_listing_height'] = $this->config->get('ebay_listing_height');
		} else {
			$data['ebay_listing_height'] = 200;
		}

		if (isset($this->request->post['ebay_listing_sort'])) {
			$data['ebay_listing_sort'] = $this->request->post['ebay_listing_sort'];
		} elseif ($this->config->has('ebay_listing_sort')) {
			$data['ebay_listing_sort'] = $this->config->get('ebay_listing_sort');
		} else {
			$data['ebay_listing_sort'] = 'StartTimeNewest';
		}

		if (isset($this->request->post['ebay_listing_site'])) {
			$data['ebay_listing_site'] = $this->request->post['ebay_listing_site'];
		} else {
			$data['ebay_listing_site'] = $this->config->get('ebay_listing_site');
		}

		$data['sites'] = array();

		$data['sites'][] = array(
			'text'  => 'USA',
			'value' => 0
		);

		$data['sites'][] = array(
			'text'  => 'UK',
			'value' => 3
		);
		$data['sites'][] = array(
			'text'  => 'Australia',
			'value' => 15
		);

		$data['sites'][] = array(
			'text'  => 'Canada (English)',
			'value' => 2
		);

		$data['sites'][] = array(
			'text'  => 'France',
			'value' => 71
		);
		$data['sites'][] = array(
			'text'  => 'Germany',
			'value' => 77
		);
		$data['sites'][] = array(
			'text'  => 'Italy',
			'value' => 101
		);
		$data['sites'][] = array(
			'text'  => 'Spain',
			'value' => 186
		);
		$data['sites'][] = array(
			'text'  => 'Ireland',
			'value' => 205
		);

		$data['sites'][] = array(
			'text'  => 'Austria',
			'value' => 16
		);

		$data['sites'][] = array(
			'text'  => 'Netherlands',
			'value' => 146
		);

		$data['sites'][] = array(
			'text'  => 'Belgium (French)',
			'value' => 23
		);

		$data['sites'][] = array(
			'text'  => 'Belgium (Dutch)',
			'value' => 123
		);

		if (isset($this->request->post['ebay_listing_status'])) {
			$data['ebay_listing_status'] = $this->request->post['ebay_listing_status'];
		} else {
			$data['ebay_listing_status'] = $this->config->get('ebay_listing_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/ebay_listing', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ebay_listing')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['ebay_listing_width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['ebay_listing_height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
}