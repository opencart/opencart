<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionFeedYandexMarket extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/feed/yandex_market');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			if (isset($this->request->post['feed_yandex_market_categories'])) {
				$this->request->post['feed_yandex_market_categories'] = implode(',', $this->request->post['feed_yandex_market_categories']);
			}

			if (isset($this->request->post['feed_yandex_market_manufacturers'])) {
				$this->request->post['feed_yandex_market_manufacturers'] = implode(',', $this->request->post['feed_yandex_market_manufacturers']);
			}

			$this->model_setting_setting->editSetting('feed_yandex_market', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['error_image_width'])) {
			$data['error_image_width'] = $this->error['error_image_width'];
		} else {
			$data['error_image_width'] = '';
		}

		if (isset($this->error['error_image_height'])) {
			$data['error_image_height'] = $this->error['error_image_height'];
		} else {
			$data['error_image_height'] = '';
		}

		if (isset($this->error['error_image_width_min'])) {
			$data['error_image_width_min'] = $this->error['error_image_width_min'];
		} else {
			$data['error_image_width_min'] = '';
		}

		if (isset($this->error['error_image_height_min'])) {
			$data['error_image_height_min'] = $this->error['error_image_height_min'];
		} else {
			$data['error_image_height_min'] = '';
		}

		if (isset($this->error['error_image_width_max'])) {
			$data['error_image_width_max'] = $this->error['error_image_width_max'];
		} else {
			$data['error_image_width_max'] = '';
		}

		if (isset($this->error['error_image_height_max'])) {
			$data['error_image_height_max'] = $this->error['error_image_height_max'];
		} else {
			$data['error_image_height_max'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_feed'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/feed/yandex_market', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/feed/yandex_market', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true);

		if (isset($this->request->post['feed_yandex_market_status'])) {
			$data['feed_yandex_market_status'] = $this->request->post['feed_yandex_market_status'];
		} else {
			$data['feed_yandex_market_status'] = $this->config->get('feed_yandex_market_status');
		}

		if (isset($this->request->post['feed_yandex_market_secret_key'])) {
			$data['feed_yandex_market_secret_key'] = $this->request->post['feed_yandex_market_secret_key'];
		} else {
			$data['feed_yandex_market_secret_key'] = $this->config->get('feed_yandex_market_secret_key');
		}

		if (isset($this->request->post['feed_yandex_market_shopname'])) {
			$data['feed_yandex_market_shopname'] = $this->request->post['feed_yandex_market_shopname'];
		} else {
			$data['feed_yandex_market_shopname'] = $this->config->get('feed_yandex_market_shopname');
		}

		if (isset($this->request->post['feed_yandex_market_company'])) {
			$data['feed_yandex_market_company'] = $this->request->post['feed_yandex_market_company'];
		} else {
			$data['feed_yandex_market_company'] = $this->config->get('feed_yandex_market_company');
		}

		if (isset($this->request->post['feed_yandex_market_id'])) {
			$data['feed_yandex_market_id'] = $this->request->post['feed_yandex_market_id'];
		} elseif ($this->config->has('feed_yandex_market_id')) {
			$data['feed_yandex_market_id'] = $this->config->get('feed_yandex_market_id');
		} else {
			$data['feed_yandex_market_id'] = 'product_id';
		}

		if (isset($this->request->post['feed_yandex_market_type'])) {
			$data['feed_yandex_market_type'] = $this->request->post['feed_yandex_market_type'];
		} else {
			$data['feed_yandex_market_type'] = $this->config->get('feed_yandex_market_type');
		}

		$data['code_man'] = array('not_unload', 'name', 'meta_h1', 'meta_title', 'meta_keyword', 'meta_description', 'model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location');

		if (isset($this->request->post['feed_yandex_market_name'])) {
			$data['feed_yandex_market_name'] = $this->request->post['feed_yandex_market_name'];
		} elseif ($this->config->has('feed_yandex_market_name')) {
			$data['feed_yandex_market_name'] = $this->config->get('feed_yandex_market_name');
		} else {
			$data['feed_yandex_market_name'] = 'name';
		}

		if (isset($this->request->post['feed_yandex_market_model'])) {
			$data['feed_yandex_market_model'] = $this->request->post['feed_yandex_market_model'];
		} elseif ($this->config->has('feed_yandex_market_model')) {
			$data['feed_yandex_market_model'] = $this->config->get('feed_yandex_market_model');
		} else {
			$data['feed_yandex_market_model'] = 'model';
		}

		if (isset($this->request->post['feed_yandex_market_vendorcode'])) {
			$data['feed_yandex_market_company'] = $this->request->post['feed_yandex_market_company'];
		} elseif ($this->config->has('feed_yandex_market_vendorcode')) {
			$data['feed_yandex_market_vendorcode'] = $this->config->get('feed_yandex_market_vendorcode');
		} else {
			$data['feed_yandex_market_vendorcode'] = 'sku';
		}

		if (isset($this->request->post['feed_yandex_market_image'])) {
			$data['feed_yandex_market_image'] = $this->request->post['feed_yandex_market_image'];
		} elseif ($this->config->has('feed_yandex_market_image')) {
			$data['feed_yandex_market_image'] = $this->config->get('feed_yandex_market_image');
		} else {
			$data['feed_yandex_market_image'] = '1';
		}

		if (isset($this->request->post['feed_yandex_market_image_width'])) {
			$data['feed_yandex_market_image_width'] = $this->request->post['feed_yandex_market_image_width'];
		} elseif ($this->config->has('feed_yandex_market_image_width')) {
			$data['feed_yandex_market_image_width'] = $this->config->get('feed_yandex_market_image_width');
		} else {
			$data['feed_yandex_market_image_width'] = '600';
		}

		if (isset($this->request->post['feed_yandex_market_image_height'])) {
			$data['feed_yandex_market_image_height'] = $this->request->post['feed_yandex_market_image_height'];
		} elseif ($this->config->has('feed_yandex_market_image_height')) {
			$data['feed_yandex_market_image_height'] = $this->config->get('feed_yandex_market_image_height');
		} else {
			$data['feed_yandex_market_image_height'] = '600';
		}

		if (isset($this->request->post['feed_yandex_market_image_quantity'])) {
			$data['feed_yandex_market_image_quantity'] = $this->request->post['feed_yandex_market_image_quantity'];
		} elseif ($this->config->has('feed_yandex_market_image_quantity')) {
			$data['feed_yandex_market_image_quantity'] = $this->config->get('feed_yandex_market_image_quantity');
		} else {
			$data['feed_yandex_market_image_quantity'] = '10';
		}

		if (isset($this->request->post['feed_yandex_market_main_category'])) {
			$data['feed_yandex_market_main_category'] = $this->request->post['feed_yandex_market_main_category'];
		} elseif ($this->config->has('feed_yandex_market_main_category')) {
			$data['feed_yandex_market_main_category'] = $this->config->get('feed_yandex_market_main_category');
		} else {
			$data['feed_yandex_market_main_category'] = '1';
		}

		$this->load->model('catalog/category');

		$data['categories'] = $this->model_catalog_category->getCategories(0);

		if (isset($this->request->post['feed_yandex_market_categories'])) {
			$data['feed_yandex_market_categories'] = $this->request->post['feed_yandex_market_categories'];
		} elseif ($this->config->has('feed_yandex_market_categories')) {
			$data['feed_yandex_market_categories'] = explode(',', $this->config->get('feed_yandex_market_categories'));
		} else {
			$data['feed_yandex_market_categories'] = array();
		}

		$this->load->model('catalog/manufacturer');

		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers(0);

		if (isset($this->request->post['feed_yandex_market_manufacturers'])) {
			$data['feed_yandex_market_manufacturers'] = $this->request->post['feed_yandex_market_manufacturers'];
		} elseif ($this->config->has('feed_yandex_market_manufacturers')) {
			$data['feed_yandex_market_manufacturers'] = explode(',', $this->config->get('feed_yandex_market_manufacturers'));
		} else {
			$data['feed_yandex_market_manufacturers'] = array();
		}

		$this->load->model('localisation/currency');

		$currencies = $this->model_localisation_currency->getCurrencies();

		$allowed_currencies = array_flip(array('RUR', 'RUB', 'BYR', 'KZT', 'UAH'));

		$data['currencies'] = array_intersect_key($currencies, $allowed_currencies);

		if (isset($this->request->post['feed_yandex_market_currency'])) {
			$data['feed_yandex_market_currency'] = $this->request->post['feed_yandex_market_currency'];
		} else {
			$data['feed_yandex_market_currency'] = $this->config->get('feed_yandex_market_currency');
		}

		$this->load->model('localisation/stock_status');

		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if (isset($this->request->post['feed_yandex_market_in_stock'])) {
			$data['feed_yandex_market_in_stock'] = $this->request->post['feed_yandex_market_in_stock'];
		} elseif ($this->config->get('feed_yandex_market_in_stock')) {
			$data['feed_yandex_market_in_stock'] = $this->config->get('feed_yandex_market_in_stock');
		} else {
			$data['feed_yandex_market_in_stock'] = 7;
		}

		if (isset($this->request->post['feed_yandex_market_out_of_stock'])) {
			$data['feed_yandex_market_out_of_stock'] = $this->request->post['feed_yandex_market_out_of_stock'];
		} elseif ($this->config->get('feed_yandex_market_in_stock')) {
			$data['feed_yandex_market_out_of_stock'] = $this->config->get('feed_yandex_market_out_of_stock');
		} else {
			$data['feed_yandex_market_out_of_stock'] = 5;
		}

		if (isset($this->request->post['feed_yandex_market_quantity_status'])) {
			$data['feed_yandex_market_quantity_status'] = $this->request->post['feed_yandex_market_quantity_status'];
		} else {
			$data['feed_yandex_market_quantity_status'] = $this->config->get('feed_yandex_market_quantity_status');
		}

		$data['data_feed'] = HTTP_CATALOG . 'index.php?route=extension/feed/yandex_market' . ($this->config->get('feed_yandex_market_secret_key') ? '&secret_key=' . $this->config->get('feed_yandex_market_secret_key') : false);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/yandex_market', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/feed/yandex_market')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['feed_yandex_market_image_width']) {
			$this->error['error_image_width'] = $this->language->get('error_image_width');
		}

		if (!$this->request->post['feed_yandex_market_image_height']) {
			$this->error['error_image_height'] = $this->language->get('error_image_height');
		}

		if ($this->request->post['feed_yandex_market_image_width'] < 250) {
			$this->error['error_image_width_min'] = $this->language->get('error_image_width_min');
		}

		if ($this->request->post['feed_yandex_market_image_height'] < 250) {
			$this->error['error_image_height_min'] = $this->language->get('error_image_height_min');
		}

		if ($this->request->post['feed_yandex_market_image_width'] > 3500) {
			$this->error['error_image_width_max'] = $this->language->get('error_image_width_max');
		}

		if ($this->request->post['feed_yandex_market_image_height'] > 3500) {
			$this->error['error_image_height_max'] = $this->language->get('error_image_height_max');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}