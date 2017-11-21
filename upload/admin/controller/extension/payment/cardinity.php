<?php
class ControllerExtensionPaymentCardinity extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/cardinity');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_cardinity', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		if (isset($this->error['secret'])) {
			$data['error_secret'] = $this->error['secret'];
		} else {
			$data['error_secret'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/cardinity', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/payment/cardinity', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		if (isset($this->request->post['payment_cardinity_key'])) {
			$data['payment_cardinity_key'] = $this->request->post['payment_cardinity_key'];
		} else {
			$data['payment_cardinity_key'] = $this->config->get('payment_cardinity_key');
		}

		if (isset($this->request->post['payment_cardinity_secret'])) {
			$data['payment_cardinity_secret'] = $this->request->post['payment_cardinity_secret'];
		} else {
			$data['payment_cardinity_secret'] = $this->config->get('payment_cardinity_secret');
		}

		if (isset($this->request->post['payment_cardinity_debug'])) {
			$data['payment_cardinity_debug'] = $this->request->post['payment_cardinity_debug'];
		} else {
			$data['payment_cardinity_debug'] = $this->config->get('payment_cardinity_debug');
		}

		if (isset($this->request->post['payment_cardinity_total'])) {
			$data['payment_cardinity_total'] = $this->request->post['payment_cardinity_total'];
		} else {
			$data['payment_cardinity_total'] = $this->config->get('payment_cardinity_total');
		}

		if (isset($this->request->post['payment_cardinity_order_status_id'])) {
			$data['payment_cardinity_order_status_id'] = $this->request->post['payment_cardinity_order_status_id'];
		} else {
			$data['payment_cardinity_order_status_id'] = $this->config->get('payment_cardinity_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_cardinity_geo_zone_id'])) {
			$data['payment_cardinity_geo_zone_id'] = $this->request->post['payment_cardinity_geo_zone_id'];
		} else {
			$data['payment_cardinity_geo_zone_id'] = $this->config->get('payment_cardinity_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_cardinity_status'])) {
			$data['payment_cardinity_status'] = $this->request->post['payment_cardinity_status'];
		} else {
			$data['payment_cardinity_status'] = $this->config->get('payment_cardinity_status');
		}

		if (isset($this->request->post['payment_cardinity_sort_order'])) {
			$data['payment_cardinity_sort_order'] = $this->request->post['payment_cardinity_sort_order'];
		} else {
			$data['payment_cardinity_sort_order'] = $this->config->get('payment_cardinity_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/cardinity', $data));
	}

	public function order() {
		$this->load->language('extension/payment/cardinity');

		$data['user_token'] = $this->session->data['user_token'];
		$data['order_id'] = (int)$this->request->get['order_id'];

		return $this->load->view('extension/payment/cardinity_order', $data);
	}

	public function getPayment() {
		$this->load->language('extension/payment/cardinity');

		$this->load->model('extension/payment/cardinity');

		$data['column_refund'] = $this->language->get('column_refund');
		$data['column_date'] = $this->language->get('column_date');
		$data['column_refund_history'] = $this->language->get('column_refund_history');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_description'] = $this->language->get('column_description');

		$data['button_refund'] = $this->language->get('button_refund');

		$data['user_token'] = $this->session->data['user_token'];

		$client = $this->model_extension_payment_cardinity->createClient(array(
			'key'    => $this->config->get('payment_cardinity_key'),
			'secret' => $this->config->get('payment_cardinity_secret')
		));

		$order = $this->model_extension_payment_cardinity->getOrder($this->request->get['order_id']);

		$data['payment'] = false;

		$data['refunds'] = array();

		if ($order && $order['payment_id']) {
			$data['payment'] = true;

			$payment = $this->model_extension_payment_cardinity->getPayment($client, $order['payment_id']);

			$data['refund_action'] = false;

			$successful_statuses = array(
				'approved'
			);

			if (in_array($payment->getStatus(), $successful_statuses)) {
				$data['refund_action'] = true;
			}

			$max_refund_amount = $payment->getAmount();

			$refunds = $this->model_extension_payment_cardinity->getRefunds($client, $order['payment_id']);

			if ($refunds) {
				foreach ($refunds as $refund) {
					$successful_refund_statuses = array(
						'approved'
					);

					if (in_array($refund->getStatus(), $successful_refund_statuses)) {
						$max_refund_amount -= $refund->getAmount();
					}

					$data['refunds'][] = array(
						'date_added'  => date($this->language->get('datetime_format'), strtotime($refund->getCreated())),
						'amount'	  => $this->currency->format($refund->getAmount(), $refund->getCurrency(), '1.00000000', true),
						'status'	  => $refund->getStatus(),
						'description' => $refund->getDescription()
					);
				}
			}

			if (!$max_refund_amount) {
				$data['refund_action'] = false;
			}

			$data['payment_id'] = $payment->getId();
			$data['symbol_left'] = $this->currency->getSymbolLeft($payment->getCurrency());
			$data['symbol_right'] = $this->currency->getSymbolRight($payment->getCurrency());

			$data['max_refund_amount'] = $this->currency->format($max_refund_amount, $payment->getCurrency(), '1.00000000', false);
		}

		$this->response->setOutput($this->load->view('extension/payment/cardinity_order_ajax', $data));
	}

	public function refund() {
		$this->load->language('extension/payment/cardinity');

		$this->load->model('extension/payment/cardinity');

		$json = array();

		$success = $error = '';

		$client = $this->model_extension_payment_cardinity->createClient(array(
			'key'    => $this->config->get('payment_cardinity_key'),
			'secret' => $this->config->get('payment_cardinity_secret')
		));

		$refund = $this->model_extension_payment_cardinity->refundPayment($client, $this->request->post['payment_id'], (float)number_format($this->request->post['amount'], 2), $this->request->post['description']);

		if ($refund) {
			$success = $this->language->get('text_success_action');
		} else {
			$error = $this->language->get('text_error_generic');
		}

		$json['success'] = $success;
		$json['error'] = $error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		$this->load->model('extension/payment/cardinity');

		$check_credentials = true;

		if (version_compare(phpversion(), '5.4.0', '<')) {
			$this->error['warning'] = $this->language->get('error_php_version');
		}

		if (!$this->user->hasPermission('modify', 'extension/payment/cardinity')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$check_credentials = false;
		}

		if (!$this->request->post['payment_cardinity_key']) {
			$this->error['key'] = $this->language->get('error_key');

			$check_credentials = false;
		}

		if (!$this->request->post['payment_cardinity_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');

			$check_credentials = false;
		}

		if (!class_exists('Cardinity\Client')) {
			$this->error['warning'] = $this->language->get('error_composer');

			$check_credentials = false;
		}

		if ($check_credentials) {
			$client = $this->model_extension_payment_cardinity->createClient(array(
				'key'    => $this->request->post['payment_cardinity_key'],
				'secret' => $this->request->post['payment_cardinity_secret']
			));

			$verify_credentials = $this->model_extension_payment_cardinity->verifyCredentials($client);

			if (!$verify_credentials) {
				$this->error['warning'] = $this->language->get('error_connection');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/payment/cardinity');

		$this->model_extension_payment_cardinity->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/cardinity');

		$this->model_extension_payment_cardinity->uninstall();
	}
}