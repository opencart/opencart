<?php
class ControllerPaymentAmazonCheckout extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/amazon_checkout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		$this->load->model('payment/amazon_checkout');

		$this->load->library('cba');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['amazon_checkout_access_key'] = $this->request->post['amazon_checkout_access_key'];
			$this->request->post['amazon_checkout_access_secret'] = $this->request->post['amazon_checkout_access_secret'];
			$this->request->post['amazon_checkout_merchant_id'] = $this->request->post['amazon_checkout_merchant_id'];

			if (!isset($this->request->post['amazon_checkout_allowed_ips'])) {
				$this->request->post['amazon_checkout_allowed_ips'] = array();
			}

			$this->model_setting_setting->editSetting('amazon_checkout', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$cba = new CBA($this->request->post['amazon_checkout_merchant_id'], $this->request->post['amazon_checkout_access_key'], $this->request->post['amazon_checkout_access_secret'], $this->request->post['amazon_checkout_marketplace']);

			$cba->setMode($this->request->post['amazon_checkout_mode']);

			$cba->scheduleReports();

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_amazon_join'] = $this->language->get('text_amazon_join');
		$data['text_sandbox'] = $this->language->get('text_sandbox');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_germany'] = $this->language->get('text_germany');
		$data['text_uk'] = $this->language->get('text_uk');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_button_settings'] = $this->language->get('text_button_settings');
		$data['text_orange'] = $this->language->get('text_orange');
		$data['text_tan'] = $this->language->get('text_tan');
		$data['text_white'] = $this->language->get('text_white');
		$data['text_light'] = $this->language->get('text_light');
		$data['text_dark'] = $this->language->get('text_dark');
		$data['text_medium'] = $this->language->get('text_medium');
		$data['text_large'] = $this->language->get('text_large');
		$data['text_x_large'] = $this->language->get('text_x_large');

		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['entry_access_key'] = $this->language->get('entry_access_key');
		$data['entry_access_secret'] = $this->language->get('entry_access_secret');
		$data['entry_checkout_mode'] = $this->language->get('entry_checkout_mode');
		$data['entry_marketplace'] = $this->language->get('entry_marketplace');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_ready_status'] = $this->language->get('entry_ready_status');
		$data['entry_canceled_status'] = $this->language->get('entry_canceled_status');
		$data['entry_shipped_status'] = $this->language->get('entry_shipped_status');
		$data['entry_cron_job_token'] = $this->language->get('entry_cron_job_token');
		$data['entry_cron_job_url'] = $this->language->get('entry_cron_job_url');
		$data['entry_cron_job_last_run'] = $this->language->get('entry_cron_job_last_run');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_ip_allowed'] = $this->language->get('entry_ip_allowed');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_colour'] = $this->language->get('entry_colour');
		$data['entry_background'] = $this->language->get('entry_background');
		$data['entry_size'] = $this->language->get('entry_size');

		$data['help_ip'] = $this->language->get('help_ip');
		$data['help_ip_allowed'] = $this->language->get('help_ip_allowed');
		$data['help_cron_job_url'] = $this->language->get('help_cron_job_url');
		$data['help_cron_job_token'] = $this->language->get('help_cron_job_token');

		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant_id'])) {
			$data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$data['error_merchant_id'] = '';
		}

		if (isset($this->error['access_key'])) {
			$data['error_access_key'] = $this->error['access_key'];
		} else {
			$data['error_access_key'] = '';
		}

		if (isset($this->error['access_secret'])) {
			$data['error_access_secret'] = $this->error['access_secret'];
		} else {
			$data['error_access_secret'] = '';
		}

		if (isset($this->error['currency'])) {
			$data['error_currency'] = $this->error['currency'];
		} else {
			$data['error_currency'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/amazon_checkout', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/amazon_checkout', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token']);

		if (isset($this->request->post['amazon_checkout_merchant_id'])) {
			$data['amazon_checkout_merchant_id'] = $this->request->post['amazon_checkout_merchant_id'];
		} elseif ($this->config->get('amazon_checkout_merchant_id')) {
			$data['amazon_checkout_merchant_id'] = $this->config->get('amazon_checkout_merchant_id');
		} else {
			$data['amazon_checkout_merchant_id'] = '';
		}

		if (isset($this->request->post['amazon_checkout_access_key'])) {
			$data['amazon_checkout_access_key'] = $this->request->post['amazon_checkout_access_key'];
		} else {
			$data['amazon_checkout_access_key'] = $this->config->get('amazon_checkout_access_key');
		}

		if (isset($this->request->post['amazon_checkout_access_secret'])) {
			$data['amazon_checkout_access_secret'] = $this->request->post['amazon_checkout_access_secret'];
		} else {
			$data['amazon_checkout_access_secret'] = $this->config->get('amazon_checkout_access_secret');
		}

		if (isset($this->request->post['amazon_checkout_mode'])) {
			$data['amazon_checkout_mode'] = $this->request->post['amazon_checkout_mode'];
		} else {
			$data['amazon_checkout_mode'] = $this->config->get('amazon_checkout_mode');
		}

		if (isset($this->request->post['amazon_checkout_marketplace'])) {
			$data['amazon_checkout_marketplace'] = $this->request->post['amazon_checkout_marketplace'];
		} else {
			$data['amazon_checkout_marketplace'] = $this->config->get('amazon_checkout_marketplace');
		}

		if (isset($this->request->post['amazon_checkout_order_status_id'])) {
			$data['amazon_checkout_order_status_id'] = $this->request->post['amazon_checkout_order_status_id'];
		} else {
			$data['amazon_checkout_order_status_id'] = $this->config->get('amazon_checkout_order_status_id');
		}

		if (isset($this->request->post['amazon_checkout_ready_status_id'])) {
			$data['amazon_checkout_ready_status_id'] = $this->request->post['amazon_checkout_ready_status_id'];
		} else {
			$data['amazon_checkout_ready_status_id'] = $this->config->get('amazon_checkout_ready_status_id');
		}

		if (isset($this->request->post['amazon_checkout_canceled_status_id'])) {
			$data['amazon_checkout_canceled_status_id'] = $this->request->post['amazon_checkout_canceled_status_id'];
		} else {
			$data['amazon_checkout_canceled_status_id'] = $this->config->get('amazon_checkout_canceled_status_id');
		}

		if (isset($this->request->post['amazon_checkout_shipped_status_id'])) {
			$data['amazon_checkout_shipped_status_id'] = $this->request->post['amazon_checkout_shipped_status_id'];
		} else {
			$data['amazon_checkout_shipped_status_id'] = $this->config->get('amazon_checkout_shipped_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['amazon_checkout_cron_job_token'])) {
			$data['amazon_checkout_cron_job_token'] = $this->request->post['amazon_checkout_cron_job_token'];
		} elseif ($this->config->get('amazon_checkout_cron_job_token')) {
			$data['amazon_checkout_cron_job_token'] = $this->config->get('amazon_checkout_cron_job_token');
		} else {
			$data['amazon_checkout_cron_job_token'] = sha1(uniqid(mt_rand(), 1));
		}

		$data['cron_job_url'] = HTTPS_CATALOG . 'index.php?route=payment/amazon_checkout/cron&token=' . $data['amazon_checkout_cron_job_token'];

		$data['store'] = HTTPS_CATALOG;

		$data['cron_job_last_run'] = $this->config->get('amazon_checkout_cron_job_last_run');

		if (isset($this->request->post['amazon_checkout_allowed_ips'])) {
			$data['amazon_checkout_ip_allowed'] = $this->request->post['amazon_checkout_ip_allowed'];
		} elseif ($this->config->get('amazon_checkout_allowed_ips')) {
			$data['amazon_checkout_ip_allowed'] = $this->config->get('amazon_checkout_ip_allowed');
		} else {
			$data['amazon_checkout_ip_allowed'] = array();
		}

		if (isset($this->request->post['amazon_checkout_geo_zone'])) {
			$data['amazon_checkout_geo_zone'] = $this->request->post['amazon_checkout_geo_zone'];
		} else {
			$data['amazon_checkout_geo_zone'] = $this->config->get('amazon_checkout_geo_zone');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['amazon_checkout_total'])) {
			$data['amazon_checkout_total'] = $this->request->post['amazon_checkout_total'];
		} elseif ($this->config->get('amazon_checkout_total')) {
			$data['amazon_checkout_total'] = $this->config->get('amazon_checkout_total');
		} else {
			$data['amazon_checkout_total'] = '0.00';
		}

		if (isset($this->request->post['amazon_checkout_status'])) {
			$data['amazon_checkout_status'] = $this->request->post['amazon_checkout_status'];
		} else {
			$data['amazon_checkout_status'] = $this->config->get('amazon_checkout_status');
		}

		if (isset($this->request->post['amazon_checkout_sort_order'])) {
			$data['amazon_checkout_sort_order'] = $this->request->post['amazon_checkout_sort_order'];
		} else {
			$data['amazon_checkout_sort_order'] = $this->config->get('amazon_checkout_sort_order');
		}

		if (isset($this->request->post['amazon_checkout_button_colour'])) {
			$data['amazon_checkout_button_colour'] = $this->request->post['amazon_checkout_button_colour'];
		} elseif ($this->config->get('amazon_checkout_button_colour')) {
			$data['amazon_checkout_button_colour'] = $this->config->get('amazon_checkout_button_colour');
		} else {
			$data['amazon_checkout_button_colour'] = 'orange';
		}

		if (isset($this->request->post['amazon_checkout_button_size'])) {
			$data['amazon_checkout_button_size'] = $this->request->post['amazon_checkout_button_size'];
		} elseif ($this->config->get('amazon_checkout_button_size')) {
			$data['amazon_checkout_button_size'] = $this->config->get('amazon_checkout_button_size');
		} else {
			$data['amazon_checkout_button_size'] = 'large';
		}

		if (isset($this->request->post['amazon_checkout_button_background'])) {
			$data['amazon_checkout_button_background'] = $this->request->post['amazon_checkout_button_background'];
		} elseif ($this->config->get('amazon_checkout_button_background')) {
			$data['amazon_checkout_button_background'] = $this->config->get('amazon_checkout_button_background');
		} else {
			$data['amazon_checkout_button_background'] = '';
		}

		$data['button_colours'] = array(
			'orange' => $this->language->get('text_orange'),
			'tan'    => $this->language->get('text_tan')
		);

		$data['button_backgrounds'] = array(
			'white' => $this->language->get('text_white'),
			'light' => $this->language->get('text_light'),
			'dark'  => $this->language->get('text_dark'),
		);

		$data['button_sizes'] = array(
			'medium'  => $this->language->get('text_medium'),
			'large'   => $this->language->get('text_large'),
			'x-large' => $this->language->get('text_x_large'),
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/amazon_checkout.tpl', $data));
	}

	public function install() {
		$this->load->model('payment/amazon_checkout');

		$this->load->model('setting/setting');

		$this->model_payment_amazon_checkout->install();

		$this->model_setting_setting->editSetting('amazon_checkout', $this->settings);
	}

	public function uninstall() {
		$this->load->model('payment/amazon_checkout');

		$this->model_payment_amazon_checkout->uninstall();
	}

	public function uploadOrderAdjustment() {
		$this->load->language('payment/amazon_checkout');

		$json = array();

		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload');
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$flat = str_replace(",", "\t", file_get_contents($this->request->files['file']['tmp_name']));

				$this->load->library('cba');

				$cba = new CBA($this->config->get('amazon_checkout_merchant_id'), $this->config->get('amazon_checkout_access_key'), $this->config->get('amazon_checkout_access_secret'), $this->config->get('amazon_checkout_marketplace'));

				$response = $cba->orderAdjustment($flat);

				$response_xml = simplexml_load_string($response);
				$submission_id = (string)$response_xml->SubmitFeedResult->FeedSubmissionInfo->FeedSubmissionId;

				if (!empty($submission_id)) {
					$json['success'] = $this->language->get('text_upload_success');
					$json['submission_id'] = $submission_id;
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addSubmission() {
		$this->load->model('payment/amazon_checkout');

		$this->model_payment_amazon_checkout->addReportSubmission($this->request->get['order_id'], $this->request->get['submission_id']);
	}

	public function orderAction() {
		$this->load->model('sale/order');
		$this->load->model('payment/amazon_checkout');
		$this->load->language('sale/order');
		$this->load->language('payment/amazon_checkout');

		$amazon_order_info = $this->model_payment_amazon_checkout->getOrder($this->request->get['order_id']);

		if ($amazon_order_info) {
			$order_products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

			$data['products'] = array();

			foreach ($order_products as $product) {
				$product_options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				$order_item_code = '';

				if (isset($amazon_order_info['products'][$product['order_product_id']])) {
					$order_item_code = $amazon_order_info['products'][$product['order_product_id']]['amazon_order_item_code'];
				}

				$data['products'][] = array(
					'amazon_order_item_code' => $order_item_code,
					'order_product_id'       => $product['order_product_id'],
					'product_id'             => $product['product_id'],
					'name'                   => $product['name'],
					'model'                  => $product['model'],
					'option'                 => $product_options,
					'quantity'               => $product['quantity'],
					'price'                  => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
					'total'                  => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
					'href'                   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			$data['report_submissions'] = $this->model_payment_amazon_checkout->getReportSubmissions($this->request->get['order_id']);

			$data['text_amazon_details'] = $this->language->get('text_amazon_details');
			$data['text_amazon_order_id'] = $this->language->get('text_amazon_order_id');
			$data['text_upload'] = $this->language->get('text_upload');
			$data['text_upload_template'] = $this->language->get('text_upload_template');
			$data['text_download'] = sprintf($this->language->get('text_download'), 'https://sellercentral-europe.amazon.com/gp/transactions/uploadAdjustments.html');

			$data['column_submission_id'] = $this->language->get('column_submission_id');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_text'] = $this->language->get('column_text');
			$data['column_amazon_order_item_code'] = $this->language->get('column_amazon_order_item_code');
			$data['column_product'] = $this->language->get('column_product');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');

			$data['help_adjustment'] = $this->language->get('help_adjustment');

			$data['tab_order_adjustment'] = $this->language->get('tab_order_adjustment');

			$data['amazon_order_id'] = $amazon_order_info['amazon_order_id'];

			$data['token'] = $this->session->data['token'];
			$data['order_id'] = $this->request->get['order_id'];

			return $this->load->view('payment/amazon_checkout_order.tpl', $data);
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/amazon_checkout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['amazon_checkout_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['amazon_checkout_access_key']) {
			$this->error['access_key'] = $this->language->get('error_access_key');
		}

		if (!$this->request->post['amazon_checkout_access_secret']) {
			$this->error['access_secret'] = $this->language->get('error_access_secret');
		}

		switch ($this->request->post['amazon_checkout_marketplace']) {
			case 'uk':
				$currency_code = 'GBP';
				break;

			case 'de':
				$currency_code = 'EUR';
				break;
		}

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($currency_code);

		if (empty($currency_info) || !$currency_info['status']) {
			$this->error['currency'] = sprintf($this->language->get('error_currency'), $currency_code);
		}

		return !$this->error;
	}
}