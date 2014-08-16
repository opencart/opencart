<?php
class ControllerPaymentAmazonCheckout extends Controller {
	private $errors = array();

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
		
		$data['text_cron_job_token'] = $this->language->get('text_cron_job_token');
		$data['text_cron_job_url'] = $this->language->get('text_cron_job_url');
		$data['text_amazon_join'] = $this->language->get('text_amazon_join');
		
		
		$data['text_germany'] = $this->language->get('text_germany');
		$data['text_uk'] = $this->language->get('text_uk');
		
		
		
		
		$data['text_live'] = $this->language->get('text_live');
		$data['text_sandbox'] = $this->language->get('text_sandbox');
		$data['text_sort_order'] = $this->language->get('text_sort_order');
		$data['text_minimum_total'] = $this->language->get('text_minimum_total');
		$data['text_all_geo_zones'] = $this->language->get('text_all_geo_zones');
		$data['text__enabled'] = $this->language->get('text_status_enabled');
		$data['text_disabled'] = $this->language->get('text_status_disabled');
		$data['text_ready_order_status'] = $this->language->get('text_ready_order_status');
		$data['text_canceled_status'] = $this->language->get('text_canceled_status');
		$data['text_shipped_status'] = $this->language->get('text_shipped_status');
		$data['text_last_cron_job_run'] = $this->language->get('text_last_cron_job_run');
		$data['text_ip'] = $this->language->get('text_ip');
		
		
		$data['text_ip_allowed'] = $this->language->get('text_ip_allowed');
		$data['text_upload_success'] = $this->language->get('text_upload_success');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_button_settings'] = $this->language->get('text_button_settings');
		$data['text_colour'] = $this->language->get('text_colour');
		$data['text_orange'] = $this->language->get('text_orange');
		$data['text_tan'] = $this->language->get('text_tan');
		$data['text_white'] = $this->language->get('text_white');
		$data['text_light'] = $this->language->get('text_light');
		$data['text_dark'] = $this->language->get('text_dark');
		$data['text_size'] = $this->language->get('text_size');
		$data['text_medium'] = $this->language->get('text_medium');
		$data['text_large'] = $this->language->get('text_large');
		$data['text_x_large'] = $this->language->get('text_x_large');
		$data['text_background'] = $this->language->get('text_background');
		
		
		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['entry_access_key'] = $this->language->get('entry_access_key');
		$data['entry_access_secret'] = $this->language->get('entry_access_secret');
		$data['text_checkout_mode'] = $this->language->get('text_checkout_mode');
		$data['text_marketplace'] = $this->language->get('text_marketplace');
		
		
		
			$data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$data['text_status'] = $this->language->get('text_status');
	
		$data['text_pending_status'] = $this->language->get('text_pending_status');
		
		
		$data['help_ip'] = $this->language->get('help_ip');
		$data['help_ip_allowed'] = $this->language->get('help_ip_allowed');
		$data['help_cron_job_url'] = $this->language->get('help_cron_job_url');
		$data['help_cron_job_token'] = $this->language->get('help_cron_job_token');
		
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_ip_add'] = $this->language->get('button_ip_add');
		
		$data['errors'] = $this->errors;

		if (isset($this->request->post['amazon_checkout_status'])) {
			$data['amazon_checkout_status'] = $this->request->post['amazon_checkout_status'];
		} elseif ($this->config->get('amazon_checkout_status')) {
			$data['amazon_checkout_status'] = $this->config->get('amazon_checkout_status');
		} else {
			$data['amazon_checkout_status'] = '0';
		}

		if (isset($this->request->post['amazon_checkout_mode'])) {
			$data['amazon_checkout_mode'] = $this->request->post['amazon_checkout_mode'];
		} elseif ($this->config->get('amazon_checkout_mode')) {
			$data['amazon_checkout_mode'] = $this->config->get('amazon_checkout_mode');
		} else {
			$data['amazon_checkout_mode'] = 'sandbox';
		}

		if (isset($this->request->post['amazon_checkout_sort_order'])) {
			$data['amazon_checkout_sort_order'] = $this->request->post['amazon_checkout_sort_order'];
		} elseif ($this->config->get('amazon_checkout_sort_order')) {
			$data['amazon_checkout_sort_order'] = $this->config->get('amazon_checkout_sort_order');
		} else {
			$data['amazon_checkout_sort_order'] = '0';
		}

		if (isset($this->request->post['amazon_checkout_geo_zone'])) {
			$data['amazon_checkout_geo_zone'] = $this->request->post['amazon_checkout_geo_zone'];
		} elseif ($this->config->get('amazon_checkout_geo_zone')) {
			$data['amazon_checkout_geo_zone'] = $this->config->get('amazon_checkout_geo_zone');
		} else {
			$data['amazon_checkout_geo_zone'] = '0';
		}

		if (isset($this->request->post['amazon_checkout_minimum_total'])) {
			$data['amazon_checkout_minimum_total'] = $this->request->post['amazon_checkout_minimum_total'];
		} elseif ($this->config->get('amazon_checkout_minimum_total')) {
			$data['amazon_checkout_minimum_total'] = $this->config->get('amazon_checkout_minimum_total');
		} else {
			$data['amazon_checkout_minimum_total'] = '0.00';
		}

		if (isset($this->request->post['amazon_checkout_access_key'])) {
			$data['amazon_checkout_access_key'] = $this->request->post['amazon_checkout_access_key'];
		} else {
			$data['amazon_checkout_access_key'] = $this->config->get('amazon_checkout_access_key');
		}

		if (isset($this->request->post['amazon_checkout_access_secret'])) {
			$data['amazon_checkout_access_secret'] = $this->request->post['amazon_checkout_access_secret'];
		} elseif ($this->config->get('amazon_checkout_access_secret')) {
			$data['amazon_checkout_access_secret'] = $this->config->get('amazon_checkout_access_secret');
		} else {
			$data['amazon_checkout_access_secret'] = '';
		}

		if (isset($this->request->post['amazon_checkout_merchant_id'])) {
			$data['amazon_checkout_merchant_id'] = $this->request->post['amazon_checkout_merchant_id'];
		} elseif ($this->config->get('amazon_checkout_merchant_id')) {
			$data['amazon_checkout_merchant_id'] = $this->config->get('amazon_checkout_merchant_id');
		} else {
			$data['amazon_checkout_merchant_id'] = '';
		}

		if (isset($this->request->post['amazon_checkout_marketplace'])) {
			$data['amazon_checkout_marketplace'] = $this->request->post['amazon_checkout_marketplace'];
		} elseif ($this->config->get('amazon_checkout_marketplace')) {
			$data['amazon_checkout_marketplace'] = $this->config->get('amazon_checkout_marketplace');
		} else {
			$data['amazon_checkout_marketplace'] = 'uk';
		}

		if (isset($this->request->post['amazon_checkout_order_default_status'])) {
			$data['amazon_checkout_order_default_status'] = $this->request->post['amazon_checkout_order_default_status'];
		} elseif ($this->config->get('amazon_checkout_order_default_status')) {
			$data['amazon_checkout_order_default_status'] = $this->config->get('amazon_checkout_order_default_status');
		} else {
			$data['amazon_checkout_order_default_status'] = '0';
		}

		if (isset($this->request->post['amazon_checkout_order_ready_status'])) {
			$data['amazon_checkout_order_ready_status'] = $this->request->post['amazon_checkout_order_ready_status'];
		} elseif ($this->config->get('amazon_checkout_order_ready_status')) {
			$data['amazon_checkout_order_ready_status'] = $this->config->get('amazon_checkout_order_ready_status');
		} else {
			$data['amazon_checkout_order_ready_status'] = '0';
		}

		if (isset($this->request->post['amazon_checkout_order_canceled_status'])) {
			$data['amazon_checkout_order_canceled_status'] = $this->request->post['amazon_checkout_order_canceled_status'];
		} elseif ($this->config->get('amazon_checkout_order_canceled_status')) {
			$data['amazon_checkout_order_canceled_status'] = $this->config->get('amazon_checkout_order_canceled_status');
		} else {
			$data['amazon_checkout_order_canceled_status'] = 'amazon_checkout_order_canceled_status';
		}

		if (isset($this->request->post['amazon_checkout_order_shipped_status'])) {
			$data['amazon_checkout_order_shipped_status'] = $this->request->post['amazon_checkout_order_shipped_status'];
		} elseif ($this->config->get('amazon_checkout_order_shipped_status')) {
			$data['amazon_checkout_order_shipped_status'] = $this->config->get('amazon_checkout_order_shipped_status');
		} else {
			$data['amazon_checkout_order_shipped_status'] = '0';
		}

		if (isset($this->request->post['amazon_checkout_allowed_ips'])) {
			$data['amazon_checkout_ip_allowed'] = $this->request->post['amazon_checkout_ip_allowed'];
		} elseif ($this->config->get('amazon_checkout_allowed_ips')) {
			$data['amazon_checkout_ip_allowed'] = $this->config->get('amazon_checkout_ip_allowed');
		} else {
			$data['amazon_checkout_ip_allowed'] = array();
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

		if (isset($this->request->post['amazon_checkout_cron_job_token'])) {
			$data['amazon_checkout_cron_job_token'] = $this->request->post['amazon_checkout_cron_job_token'];
		} elseif ($this->config->get('amazon_checkout_cron_job_token')) {
			$data['amazon_checkout_cron_job_token'] = $this->config->get('amazon_checkout_cron_job_token');
		} else {
			$data['amazon_checkout_cron_job_token'] = sha1(uniqid(mt_rand(), 1));
		}

		$data['cron_job_url'] = HTTPS_CATALOG . 'index.php?route=payment/amazon_checkout/cron&token=' . $data['amazon_checkout_cron_job_token'];

		$data['last_cron_job_run'] = $this->config->get('amazon_checkout_last_cron_job_run');
		
		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['action'] = $this->url->link('payment/amazon_checkout', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token']);

		$data['button_colours'] = array(
			'orange' => $this->language->get('text_orange'),
			'tan' => $this->language->get('text_tan')
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
		$data['menu'] = $this->load->controller('common/menu');
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

				$cba = new CBA($this->config->get('amazon_checkout_merchant_id'), $this->config->get('amazon_checkout_access_key'),
						$this->config->get('amazon_checkout_access_secret'), $this->config->get('amazon_checkout_marketplace'));

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
					'order_product_id' => $product['order_product_id'],
					'product_id' => $product['product_id'],
					'name' => $product['name'],
					'model' => $product['model'],
					'option' => $product_options,
					'quantity' => $product['quantity'],
					'price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
					'total' => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
					'href' => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			$data['report_submissions'] = $this->model_payment_amazon_checkout->getReportSubmissions($this->request->get['order_id']);

			$data['text_amazon_details'] = $this->language->get('text_amazon_details');
			$data['text_amazon_order_id'] = $this->language->get('text_amazon_order_id');
			$data['text_upload'] = $this->language->get('text_upload');
			$data['text_upload_template'] = $this->language->get('text_upload_template');
			$data['tab_order_adjustment'] = $this->language->get('tab_order_adjustment');

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
			$data['text_download'] = sprintf($this->language->get('text_download'), 'https://sellercentral-europe.amazon.com/gp/transactions/uploadAdjustments.html');

			$data['amazon_order_id'] = $amazon_order_info['amazon_order_id'];

			$data['token'] = $this->session->data['token'];
			$data['order_id'] = $this->request->get['order_id'];

			return $this->load->view('payment/amazon_checkout_order.tpl', $data);
		}
	}

	protected function validate() {
		$this->load->model('localisation/currency');

		if (!$this->user->hasPermission('modify', 'payment/amazon_checkout')) {
			$this->errors[] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['amazon_checkout_merchant_id'])) {
			$this->errors[] = $this->language->get('error_empty_merchant_id');
		}

		if (empty($this->request->post['amazon_checkout_access_key'])) {
			$this->errors[] = $this->language->get('error_empty_access_key');
		}

		if (empty($this->request->post['amazon_checkout_access_secret'])) {
			$this->errors[] = $this->language->get('error_empty_access_secret');
		}

		switch ($this->request->post['amazon_checkout_marketplace']) {
			case 'uk':
				$currency_code = 'GBP';
				break;

			case 'de':
				$currency_code = 'EUR';
				break;
		}

		$currency = $this->model_localisation_currency->getCurrency($this->currency->getId($currency_code));

		if (empty($currency) || $currency['status'] != '1') {
			$this->errors[] = sprintf($this->language->get('error_curreny'), $currency_code);
		}

		return empty($this->errors);
	}
}