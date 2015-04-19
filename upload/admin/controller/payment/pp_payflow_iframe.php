<?php
class ControllerPaymentPPPayflowIframe extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/pp_payflow_iframe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_payflow_iframe', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_iframe'] = $this->language->get('text_iframe');
		$data['text_redirect'] = $this->language->get('text_redirect');

		$data['entry_vendor'] = $this->language->get('entry_vendor');
		$data['entry_user'] = $this->language->get('entry_user');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_partner'] = $this->language->get('entry_partner');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_cancel_url'] = $this->language->get('entry_cancel_url');
		$data['entry_error_url'] = $this->language->get('entry_error_url');
		$data['entry_return_url'] = $this->language->get('entry_return_url');
		$data['entry_post_url'] = $this->language->get('entry_post_url');
		$data['entry_checkout_method'] = $this->language->get('entry_checkout_method');
		$data['entry_order_status'] = $this->language->get('entry_order_status');

		$data['help_vendor'] = $this->language->get('help_vendor');
		$data['help_user'] = $this->language->get('help_user');
		$data['help_password'] = $this->language->get('help_password');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_total'] = $this->language->get('help_total');
		$data['help_test'] = $this->language->get('help_test');
		$data['help_partner'] = $this->language->get('help_partner');
		$data['help_checkout_method'] = $this->language->get('help_checkout_method');

		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_order_status'] = $this->language->get('tab_order_status');
		$data['tab_checkout_customisation'] = $this->language->get('tab_checkout_customisation');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_pp_express'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/pp_payflow', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['action'] = $this->url->link('payment/pp_payflow_iframe', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pp_payflow_iframe_vendor'])) {
			$data['pp_payflow_iframe_vendor'] = $this->request->post['pp_payflow_iframe_vendor'];
		} else {
			$data['pp_payflow_iframe_vendor'] = $this->config->get('pp_payflow_iframe_vendor');
		}

		if (isset($this->request->post['pp_payflow_iframe_user'])) {
			$data['pp_payflow_iframe_user'] = $this->request->post['pp_payflow_iframe_user'];
		} else {
			$data['pp_payflow_iframe_user'] = $this->config->get('pp_payflow_iframe_user');
		}

		if (isset($this->request->post['pp_payflow_iframe_password'])) {
			$data['pp_payflow_iframe_password'] = $this->request->post['pp_payflow_iframe_password'];
		} else {
			$data['pp_payflow_iframe_password'] = $this->config->get('pp_payflow_iframe_password');
		}

		if (isset($this->request->post['pp_payflow_iframe_partner'])) {
			$data['pp_payflow_iframe_partner'] = $this->request->post['pp_payflow_iframe_partner'];
		} else {
			$data['pp_payflow_iframe_partner'] = $this->config->get('pp_payflow_iframe_partner');
		}

		if (isset($this->request->post['pp_payflow_iframe_transaction_method'])) {
			$data['pp_payflow_iframe_transaction_method'] = $this->request->post['pp_payflow_iframe_transaction_method'];
		} else {
			$data['pp_payflow_iframe_transaction_method'] = $this->config->get('pp_payflow_iframe_transaction_method');
		}

		if (isset($this->request->post['pp_payflow_iframe_test'])) {
			$data['pp_payflow_iframe_test'] = $this->request->post['pp_payflow_iframe_test'];
		} else {
			$data['pp_payflow_iframe_test'] = $this->config->get('pp_payflow_iframe_test');
		}

		if (isset($this->request->post['pp_payflow_iframe_total'])) {
			$data['pp_payflow_iframe_total'] = $this->request->post['pp_payflow_iframe_total'];
		} else {
			$data['pp_payflow_iframe_total'] = $this->config->get('pp_payflow_iframe_total');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_payflow_iframe_order_status_id'])) {
			$data['pp_payflow_iframe_order_status_id'] = $this->request->post['pp_payflow_iframe_order_status_id'];
		} else {
			$data['pp_payflow_iframe_order_status_id'] = $this->config->get('pp_payflow_iframe_order_status_id');
		}

		if (isset($this->request->post['pp_payflow_iframe_geo_zone_id'])) {
			$data['pp_payflow_iframe_geo_zone_id'] = $this->request->post['pp_payflow_iframe_geo_zone_id'];
		} else {
			$data['pp_payflow_iframe_geo_zone_id'] = $this->config->get('pp_payflow_iframe_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_payflow_iframe_status'])) {
			$data['pp_payflow_iframe_status'] = $this->request->post['pp_payflow_iframe_status'];
		} else {
			$data['pp_payflow_iframe_status'] = $this->config->get('pp_payflow_iframe_status');
		}

		if (isset($this->request->post['pp_payflow_iframe_sort_order'])) {
			$data['pp_payflow_iframe_sort_order'] = $this->request->post['pp_payflow_iframe_sort_order'];
		} else {
			$data['pp_payflow_iframe_sort_order'] = $this->config->get('pp_payflow_iframe_sort_order');
		}

		if (isset($this->request->post['pp_payflow_iframe_checkout_method'])) {
			$data['pp_payflow_iframe_checkout_method'] = $this->request->post['pp_payflow_iframe_checkout_method'];
		} else {
			$data['pp_payflow_iframe_checkout_method'] = $this->config->get('pp_payflow_iframe_checkout_method');
		}

		if (isset($this->request->post['pp_payflow_iframe_debug'])) {
			$data['pp_payflow_iframe_debug'] = $this->request->post['pp_payflow_iframe_debug'];
		} else {
			$data['pp_payflow_iframe_debug'] = $this->config->get('pp_payflow_iframe_debug');
		}

		$data['post_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_payflow_iframe/paymentipn';
		$data['cancel_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_payflow_iframe/paymentcancel';
		$data['error_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_payflow_iframe/paymenterror';
		$data['return_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_payflow_iframe/paymentreturn';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/pp_payflow_iframe.tpl', $data));
	}

	public function install() {
		$this->load->model('payment/pp_payflow_iframe');

		$this->model_payment_pp_payflow_iframe->install();
	}

	public function uninstall() {
		$this->load->model('payment/pp_payflow_iframe');

		$this->model_payment_pp_payflow_iframe->uninstall();
	}

	public function refund() {
		$this->load->model('payment/pp_payflow_iframe');
		$this->load->model('sale/order');
		$this->load->language('payment/pp_payflow_iframe');

		$transaction = $this->model_payment_pp_payflow_iframe->getTransaction($this->request->get['transaction_reference']);

		if ($transaction) {
			$this->document->setTitle($this->language->get('heading_refund'));

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
				'href' => $this->url->link('payment/pp_payflow_iframe', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_refund'),
				'href' => $this->url->link('payment/pp_payflow_iframe/refund', 'transaction_reference=' . $this->request->get['transaction_reference'] . '&token=' . $this->session->data['token'], 'SSL')
			);

			$data['transaction_reference'] = $transaction['transaction_reference'];
			$data['transaction_amount'] = number_format($transaction['amount'], 2);
			$data['cancel'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $transaction['order_id'], 'SSL');

			$data['token'] = $this->session->data['token'];

			$data['heading_refund'] = $this->language->get('heading_refund');

			$data['entry_transaction_reference'] = $this->language->get('entry_transaction_reference');
			$data['entry_transaction_amount'] = $this->language->get('entry_transaction_amount');
			$data['entry_refund_amount'] = $this->language->get('entry_refund_amount');

			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_refund'] = $this->language->get('button_refund');

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('payment/pp_payflow_iframe_refund.tpl', $data));
		} else {
			return $this->forward('error/not_found');
		}
	}

	public function doRefund() {
		$this->load->model('payment/pp_payflow_iframe');
		$this->load->language('payment/pp_payflow_iframe');
		$json = array();

		if (isset($this->request->post['transaction_reference']) && isset($this->request->post['amount'])) {

			$transaction = $this->model_payment_pp_payflow_iframe->getTransaction($this->request->post['transaction_reference']);

			if ($transaction) {
				$call_data = array(
					'TRXTYPE' => 'C',
					'TENDER'  => 'C',
					'ORIGID'  => $transaction['transaction_reference'],
					'AMT'     => $this->request->post['amount'],
				);

				$result = $this->model_payment_pp_payflow_iframe->call($call_data);

				if ($result['RESULT'] == 0) {
					$json['success'] = $this->language->get('text_refund_issued');

					$data = array(
						'order_id' => $transaction['order_id'],
						'type' => 'C',
						'transaction_reference' => $result['PNREF'],
						'amount' => $this->request->post['amount'],
					);

					$this->model_payment_pp_payflow_iframe->addTransaction($data);
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
		$this->load->model('payment/pp_payflow_iframe');
		$this->load->model('sale/order');
		$this->load->language('payment/pp_payflow_iframe');

		if (isset($this->request->post['order_id']) && isset($this->request->post['amount']) && isset($this->request->post['complete'])) {
			$order_id = $this->request->post['order_id'];
			$paypal_order = $this->model_payment_pp_payflow_iframe->getOrder($order_id);
			$paypal_transactions = $this->model_payment_pp_payflow_iframe->getTransactions($order_id);
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

				$result = $this->model_payment_pp_payflow_iframe->call($call_data);

				if ($result['RESULT'] == 0) {

					$data = array(
						'order_id'              => $order_id,
						'type'                  => 'D',
						'transaction_reference' => $result['PNREF'],
						'amount'                => $this->request->post['amount']
					);

					$this->model_payment_pp_payflow_iframe->addTransaction($data);
					$this->model_payment_pp_payflow_iframe->updateOrderStatus($order_id, $this->request->post['complete']);

					$actions = array();

					$actions[] = array(
						'title' => $this->language->get('text_capture'),
						'href' => $this->url->link('payment/pp_payflow_iframe/refund', 'transaction_reference=' . $result['PNREF'] . '&token=' . $this->session->data['token']),
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
		$this->load->model('payment/pp_payflow_iframe');
		$this->load->language('payment/pp_payflow_iframe');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$order_id = $this->request->post['order_id'];
			$paypal_order = $this->model_payment_pp_payflow_iframe->getOrder($order_id);

			if ($paypal_order) {
				$call_data = array(
					'TRXTYPE' => 'V',
					'TENDER' => 'C',
					'ORIGID' => $paypal_order['transaction_reference'],
				);

				$result = $this->model_payment_pp_payflow_iframe->call($call_data);

				if ($result['RESULT'] == 0) {
					$json['success'] = $this->language->get('text_void_success');
					$this->model_payment_pp_payflow_iframe->updateOrderStatus($order_id, 1);

					$data = array(
						'order_id' => $order_id,
						'type' => 'V',
						'transaction_reference' => $result['PNREF'],
						'amount' => '',
					);

					$this->model_payment_pp_payflow_iframe->addTransaction($data);
					$this->model_payment_pp_payflow_iframe->updateOrderStatus($order_id, 1);

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

	public function action() {
		$this->load->model('payment/pp_payflow_iframe');
		$this->load->language('payment/pp_payflow_iframe');

		$order_id = $this->request->get['order_id'];

		$paypal_order = $this->model_payment_pp_payflow_iframe->getOrder($order_id);

		if ($paypal_order) {
			$data['entry_capture_status'] = $this->language->get('entry_capture_status');
			$data['entry_captured_amount'] = $this->language->get('entry_captured_amount');
			$data['entry_capture'] = $this->language->get('entry_capture');
			$data['entry_void'] = $this->language->get('entry_void');
			$data['entry_transactions'] = $this->language->get('entry_transactions');
			$data['entry_complete_capture'] = $this->language->get('entry_complete_capture');

			$data['text_payment_info'] = $this->language->get('text_payment_info');
			$data['text_complete'] = $this->language->get('text_complete');
			$data['text_incomplete'] = $this->language->get('text_incomplete');
			$data['text_confirm_void'] = $this->language->get('text_confirm_void');

			$data['column_transaction_id'] = $this->language->get('column_transaction_id');
			$data['column_transaction_type'] = $this->language->get('column_transaction_type');
			$data['column_amount'] = $this->language->get('column_amount');
			$data['column_time'] = $this->language->get('column_time');
			$data['column_actions'] = $this->language->get('column_actions');

			$data['button_capture'] = $this->language->get('button_capture');
			$data['button_void'] = $this->language->get('button_void');

			$data['complete'] = $paypal_order['complete'];
			$data['order_id'] = $this->request->get['order_id'];
			$data['token'] = $this->request->get['token'];

			$data['transactions'] = array();

			$transactions = $this->model_payment_pp_payflow_iframe->getTransactions($order_id);

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
							'href' => $this->url->link('payment/pp_payflow_iframe/refund', 'transaction_reference=' . $transaction['transaction_reference'] . '&token=' . $this->session->data['token']),
						);
						break;
					case 'D':
						$transaction_type = $this->language->get('text_capture');

						$actions[] = array(
							'title' => $this->language->get('text_refund'),
							'href' => $this->url->link('payment/pp_payflow_iframe/refund', 'transaction_reference=' . $transaction['transaction_reference'] . '&token=' . $this->session->data['token']),
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

			return $this->load->view('payment/pp_payflow_iframe_order.tpl', $data);
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_payflow_iframe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pp_payflow_iframe_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}

		if (!$this->request->post['pp_payflow_iframe_user']) {
			$this->error['user'] = $this->language->get('error_user');
		}

		if (!$this->request->post['pp_payflow_iframe_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['pp_payflow_iframe_partner']) {
			$this->error['partner'] = $this->language->get('error_partner');
		}

		return !$this->error;
	}
}