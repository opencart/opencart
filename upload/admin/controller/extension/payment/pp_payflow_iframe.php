<?php
class ControllerExtensionPaymentPPPayflowIframe extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/pp_payflow_iframe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_pp_payflow_iframe', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['vendor'])) {
			$data['error_vendor'] = $this->error['vendor'];
		} else {
			$data['error_vendor'] = '';
		}

		if (isset($this->error['user'])) {
			$data['error_user'] = $this->error['user'];
		} else {
			$data['error_user'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['partner'])) {
			$data['error_partner'] = $this->error['partner'];
		} else {
			$data['error_partner'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pp_payflow_iframe', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['action'] = $this->url->link('extension/payment/pp_payflow_iframe', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_pp_payflow_iframe_vendor'])) {
			$data['payment_pp_payflow_iframe_vendor'] = $this->request->post['payment_pp_payflow_iframe_vendor'];
		} else {
			$data['payment_pp_payflow_iframe_vendor'] = $this->config->get('payment_pp_payflow_iframe_vendor');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_user'])) {
			$data['payment_pp_payflow_iframe_user'] = $this->request->post['payment_pp_payflow_iframe_user'];
		} else {
			$data['payment_pp_payflow_iframe_user'] = $this->config->get('payment_pp_payflow_iframe_user');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_password'])) {
			$data['payment_pp_payflow_iframe_password'] = $this->request->post['payment_pp_payflow_iframe_password'];
		} else {
			$data['payment_pp_payflow_iframe_password'] = $this->config->get('payment_pp_payflow_iframe_password');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_partner'])) {
			$data['payment_pp_payflow_iframe_partner'] = $this->request->post['payment_pp_payflow_iframe_partner'];
		} else {
			$data['payment_pp_payflow_iframe_partner'] = $this->config->get('payment_pp_payflow_iframe_partner');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_transaction_method'])) {
			$data['payment_pp_payflow_iframe_transaction_method'] = $this->request->post['payment_pp_payflow_iframe_transaction_method'];
		} else {
			$data['payment_pp_payflow_iframe_transaction_method'] = $this->config->get('payment_pp_payflow_iframe_transaction_method');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_test'])) {
			$data['payment_pp_payflow_iframe_test'] = $this->request->post['payment_pp_payflow_iframe_test'];
		} else {
			$data['payment_pp_payflow_iframe_test'] = $this->config->get('payment_pp_payflow_iframe_test');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_total'])) {
			$data['payment_pp_payflow_iframe_total'] = $this->request->post['payment_pp_payflow_iframe_total'];
		} else {
			$data['payment_pp_payflow_iframe_total'] = $this->config->get('payment_pp_payflow_iframe_total');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_pp_payflow_iframe_order_status_id'])) {
			$data['payment_pp_payflow_iframe_order_status_id'] = $this->request->post['payment_pp_payflow_iframe_order_status_id'];
		} else {
			$data['payment_pp_payflow_iframe_order_status_id'] = $this->config->get('payment_pp_payflow_iframe_order_status_id');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_geo_zone_id'])) {
			$data['payment_pp_payflow_iframe_geo_zone_id'] = $this->request->post['payment_pp_payflow_iframe_geo_zone_id'];
		} else {
			$data['payment_pp_payflow_iframe_geo_zone_id'] = $this->config->get('payment_pp_payflow_iframe_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_pp_payflow_iframe_status'])) {
			$data['payment_pp_payflow_iframe_status'] = $this->request->post['payment_pp_payflow_iframe_status'];
		} else {
			$data['payment_pp_payflow_iframe_status'] = $this->config->get('payment_pp_payflow_iframe_status');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_sort_order'])) {
			$data['payment_pp_payflow_iframe_sort_order'] = $this->request->post['payment_pp_payflow_iframe_sort_order'];
		} else {
			$data['payment_pp_payflow_iframe_sort_order'] = $this->config->get('payment_pp_payflow_iframe_sort_order');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_checkout_method'])) {
			$data['payment_pp_payflow_iframe_checkout_method'] = $this->request->post['payment_pp_payflow_iframe_checkout_method'];
		} else {
			$data['payment_pp_payflow_iframe_checkout_method'] = $this->config->get('payment_pp_payflow_iframe_checkout_method');
		}

		if (isset($this->request->post['payment_pp_payflow_iframe_debug'])) {
			$data['payment_pp_payflow_iframe_debug'] = $this->request->post['payment_pp_payflow_iframe_debug'];
		} else {
			$data['payment_pp_payflow_iframe_debug'] = $this->config->get('payment_pp_payflow_iframe_debug');
		}

		$data['post_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/pp_payflow_iframe/paymentipn';
		$data['cancel_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/pp_payflow_iframe/paymentcancel';
		$data['error_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/pp_payflow_iframe/paymenterror';
		$data['return_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/pp_payflow_iframe/paymentreturn';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_payflow_iframe', $data));
	}

	public function install() {
		$this->load->model('extension/payment/pp_payflow_iframe');

		$this->model_extension_payment_pp_payflow_iframe->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/pp_payflow_iframe');

		$this->model_extension_payment_pp_payflow_iframe->uninstall();
	}

	public function refund() {
		$this->load->model('extension/payment/pp_payflow_iframe');
		$this->load->model('sale/order');
		$this->load->language('extension/payment/pp_payflow_iframe');

		$transaction = $this->model_extension_payment_pp_payflow_iframe->getTransaction($this->request->get['transaction_reference']);

		if ($transaction) {
			$this->document->setTitle($this->language->get('heading_refund'));

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
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/payment/pp_payflow_iframe', 'user_token=' . $this->session->data['user_token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_refund'),
				'href' => $this->url->link('extension/payment/pp_payflow_iframe/refund', 'transaction_reference=' . $this->request->get['transaction_reference'] . '&user_token=' . $this->session->data['user_token'], true)
			);

			$data['transaction_reference'] = $transaction['transaction_reference'];
			$data['transaction_amount'] = number_format($transaction['amount'], 2);
			$data['cancel'] = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $transaction['order_id'], true);

			$data['user_token'] = $this->session->data['user_token'];

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('extension/payment/pp_payflow_iframe_refund', $data));
		} else {
			return $this->forward('error/not_found');
		}
	}

	public function doRefund() {
		$this->load->model('extension/payment/pp_payflow_iframe');
		$this->load->language('extension/payment/pp_payflow_iframe');
		$json = array();

		if (isset($this->request->post['transaction_reference']) && isset($this->request->post['amount'])) {

			$transaction = $this->model_extension_payment_pp_payflow_iframe->getTransaction($this->request->post['transaction_reference']);

			if ($transaction) {
				$call_data = array(
					'TRXTYPE' => 'C',
					'TENDER'  => 'C',
					'ORIGID'  => $transaction['transaction_reference'],
					'AMT'     => $this->request->post['amount'],
				);

				$result = $this->model_extension_payment_pp_payflow_iframe->call($call_data);

				if ($result['RESULT'] == 0) {
					$json['success'] = $this->language->get('text_refund_issued');

					$data = array(
						'order_id' => $transaction['order_id'],
						'type' => 'C',
						'transaction_reference' => $result['PNREF'],
						'amount' => $this->request->post['amount'],
					);

					$this->model_extension_payment_pp_payflow_iframe->addTransaction($data);
				} else {
					$json['error'] = $result['RESPMSG'];
				}
			} else {
				$json['error'] = $this->language->get('error_missing_order');
			}
		} else {
			$json['error'] = $this->language->get('error_missing_data');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function capture() {
		$this->load->model('extension/payment/pp_payflow_iframe');
		$this->load->model('sale/order');
		$this->load->language('extension/payment/pp_payflow_iframe');

		if (isset($this->request->post['order_id']) && isset($this->request->post['amount']) && isset($this->request->post['complete'])) {
			$order_id = $this->request->post['order_id'];
			$paypal_order = $this->model_extension_payment_pp_payflow_iframe->getOrder($order_id);
			$paypal_transactions = $this->model_extension_payment_pp_payflow_iframe->getTransactions($order_id);
			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($paypal_order && $order_info) {
				if ($this->request->post['complete'] == 1) {
					$complete = 'Y';
				} else {
					$complete = 'N';
				}

				$call_data = array(
					'TRXTYPE'         => 'D',
					'TENDER'          => 'C',
					'ORIGID'          => $paypal_order['transaction_reference'],
					'AMT'             => $this->request->post['amount'],
					'CAPTURECOMPLETE' => $complete
				);

				$result = $this->model_extension_payment_pp_payflow_iframe->call($call_data);

				if ($result['RESULT'] == 0) {

					$data = array(
						'order_id'              => $order_id,
						'type'                  => 'D',
						'transaction_reference' => $result['PNREF'],
						'amount'                => $this->request->post['amount']
					);

					$this->model_extension_payment_pp_payflow_iframe->addTransaction($data);
					$this->model_extension_payment_pp_payflow_iframe->updateOrderStatus($order_id, $this->request->post['complete']);

					$actions = array();

					$actions[] = array(
						'title' => $this->language->get('text_capture'),
						'href' => $this->url->link('extension/payment/pp_payflow_iframe/refund', 'transaction_reference=' . $result['PNREF'] . '&user_token=' . $this->session->data['user_token'], true),
					);

					$json['success'] = array(
						'transaction_type' => $this->language->get('text_capture'),
						'transaction_reference' => $result['PNREF'],
						'time' => date('Y-m-d H:i:s'),
						'amount' => number_format($this->request->post['amount'], 2),
						'actions' => $actions,
					);
				} else {
					$json['error'] = $result['RESPMSG'];
				}
			} else {
				$json['error'] = $this->language->get('error_missing_order');
			}
		} else {
			$json['error'] = $this->language->get('error_missing_data');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function void() {
		$this->load->model('extension/payment/pp_payflow_iframe');
		$this->load->language('extension/payment/pp_payflow_iframe');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$order_id = $this->request->post['order_id'];
			$paypal_order = $this->model_extension_payment_pp_payflow_iframe->getOrder($order_id);

			if ($paypal_order) {
				$call_data = array(
					'TRXTYPE' => 'V',
					'TENDER' => 'C',
					'ORIGID' => $paypal_order['transaction_reference'],
				);

				$result = $this->model_extension_payment_pp_payflow_iframe->call($call_data);

				if ($result['RESULT'] == 0) {
					$json['success'] = $this->language->get('text_void_success');
					$this->model_extension_payment_pp_payflow_iframe->updateOrderStatus($order_id, 1);

					$data = array(
						'order_id' => $order_id,
						'type' => 'V',
						'transaction_reference' => $result['PNREF'],
						'amount' => '',
					);

					$this->model_extension_payment_pp_payflow_iframe->addTransaction($data);
					$this->model_extension_payment_pp_payflow_iframe->updateOrderStatus($order_id, 1);

					$json['success'] = array(
						'transaction_type' => $this->language->get('text_void'),
						'transaction_reference' => $result['PNREF'],
						'time' => date('Y-m-d H:i:s'),
						'amount' => '0.00',
					);
				} else {
					$json['error'] = $result['RESPMSG'];
				}
			} else {
				$json['error'] = $this->language->get('error_missing_order');
			}
		} else {
			$json['error'] = $this->language->get('error_missing_data');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function order() {
		$this->load->model('extension/payment/pp_payflow_iframe');
		$this->load->language('extension/payment/pp_payflow_iframe');

		$order_id = $this->request->get['order_id'];

		$paypal_order = $this->model_extension_payment_pp_payflow_iframe->getOrder($order_id);

		if ($paypal_order) {
			$data['complete'] = $paypal_order['complete'];
			
			$data['order_id'] = (int)$this->request->get['order_id'];
			
			$data['user_token'] = $this->session->data['user_token'];

			$data['transactions'] = array();

			$transactions = $this->model_extension_payment_pp_payflow_iframe->getTransactions($order_id);

			foreach ($transactions as $transaction) {
				$actions = array();

				switch ($transaction['transaction_type']) {
					case 'V':
						$transaction_type = $this->language->get('text_void');
						break;
					case 'S':
						$transaction_type = $this->language->get('text_sale');

						$actions[] = array(
							'title' => $this->language->get('text_refund'),
							'href' => $this->url->link('extension/payment/pp_payflow_iframe/refund', 'transaction_reference=' . $transaction['transaction_reference'] . '&user_token=' . $this->session->data['user_token'], true),
						);
						break;
					case 'D':
						$transaction_type = $this->language->get('text_capture');

						$actions[] = array(
							'title' => $this->language->get('text_refund'),
							'href' => $this->url->link('extension/payment/pp_payflow_iframe/refund', 'transaction_reference=' . $transaction['transaction_reference'] . '&user_token=' . $this->session->data['user_token'], true),
						);
						break;
					case 'A':
						$transaction_type = $this->language->get('text_authorise');
						break;

					case 'C':
						$transaction_type = $this->language->get('text_refund');#
						break;

					default:
						$transaction_type = '';
						break;
				}

				$data['transactions'][] = array(
					'transaction_reference' => $transaction['transaction_reference'],
					'transaction_type'      => $transaction_type,
					'time'                  => $transaction['time'],
					'amount'                => $transaction['amount'],
					'actions'               => $actions
				);
			}

			return $this->load->view('extension/payment/pp_payflow_iframe_order', $data);
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/pp_payflow_iframe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_pp_payflow_iframe_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}

		if (!$this->request->post['payment_pp_payflow_iframe_user']) {
			$this->error['user'] = $this->language->get('error_user');
		}

		if (!$this->request->post['payment_pp_payflow_iframe_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['payment_pp_payflow_iframe_partner']) {
			$this->error['partner'] = $this->language->get('error_partner');
		}

		return !$this->error;
	}
}
