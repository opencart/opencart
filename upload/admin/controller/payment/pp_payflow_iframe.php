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

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');
		$this->data['text_iframe'] = $this->language->get('text_iframe');
		$this->data['text_redirect'] = $this->language->get('text_redirect');

		$this->data['entry_vendor'] = $this->language->get('entry_vendor');
		$this->data['entry_user'] = $this->language->get('entry_user');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_partner'] = $this->language->get('entry_partner');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_transaction_method'] = $this->language->get('entry_transaction_method');
		$this->data['entry_debug'] = $this->language->get('entry_debug'); 

		$this->data['entry_cancel_url'] = $this->language->get('entry_cancel_url');
		$this->data['entry_error_url'] = $this->language->get('entry_error_url');
		$this->data['entry_return_url'] = $this->language->get('entry_return_url');
		$this->data['entry_post_url'] = $this->language->get('entry_post_url');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_settings'] = $this->language->get('tab_settings');
		$this->data['tab_order_status'] = $this->language->get('tab_order_status');
		$this->data['tab_checkout_customisation'] = $this->language->get('tab_checkout_customisation');

		$this->data['entry_checkout_method'] = $this->language->get('entry_checkout_method');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');

		$this->data['help_vendor'] = $this->language->get('help_vendor');
		$this->data['help_user'] = $this->language->get('help_user');
		$this->data['help_password'] = $this->language->get('help_password');
		$this->data['help_partner'] = $this->language->get('help_partner');
		$this->data['help_checkout_method'] = $this->language->get('help_checkout_method');
		$this->data['help_debug'] = $this->language->get('help_debug');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['vendor'])) {
			$this->data['error_vendor'] = $this->error['vendor'];
		} else {
			$this->data['error_vendor'] = '';
		}


		if (isset($this->error['user'])) {
			$this->data['error_user'] = $this->error['user'];
		} else {
			$this->data['error_user'] = '';
		}


		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}


		if (isset($this->error['partner'])) {
			$this->data['error_partner'] = $this->error['partner'];
		} else {
			$this->data['error_partner'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_payflow_iframe', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/pp_payflow_iframe', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pp_payflow_iframe_vendor'])) {
			$this->data['pp_payflow_iframe_vendor'] = $this->request->post['pp_payflow_iframe_vendor'];
		} else {
			$this->data['pp_payflow_iframe_vendor'] = $this->config->get('pp_payflow_iframe_vendor');
		}

		if (isset($this->request->post['pp_payflow_iframe_user'])) {
			$this->data['pp_payflow_iframe_user'] = $this->request->post['pp_payflow_iframe_user'];
		} else {
			$this->data['pp_payflow_iframe_user'] = $this->config->get('pp_payflow_iframe_user');
		}

		if (isset($this->request->post['pp_payflow_iframe_password'])) {
			$this->data['pp_payflow_iframe_password'] = $this->request->post['pp_payflow_iframe_password'];
		} else {
			$this->data['pp_payflow_iframe_password'] = $this->config->get('pp_payflow_iframe_password');
		}

		if (isset($this->request->post['pp_payflow_iframe_partner'])) {
			$this->data['pp_payflow_iframe_partner'] = $this->request->post['pp_payflow_iframe_partner'];
		} else {
			$this->data['pp_payflow_iframe_partner'] = $this->config->get('pp_payflow_iframe_partner');
		}

		if (isset($this->request->post['pp_payflow_iframe_transaction_method'])) {
			$this->data['pp_payflow_iframe_transaction_method'] = $this->request->post['pp_payflow_iframe_transaction_method'];
		} else {
			$this->data['pp_payflow_iframe_transaction_method'] = $this->config->get('pp_payflow_iframe_transaction_method');
		}

		if (isset($this->request->post['pp_payflow_iframe_test'])) {
			$this->data['pp_payflow_iframe_test'] = $this->request->post['pp_payflow_iframe_test'];
		} else {
			$this->data['pp_payflow_iframe_test'] = $this->config->get('pp_payflow_iframe_test');
		}

		if (isset($this->request->post['pp_payflow_iframe_total'])) {
			$this->data['pp_payflow_iframe_total'] = $this->request->post['pp_payflow_iframe_total'];
		} else {
			$this->data['pp_payflow_iframe_total'] = $this->config->get('pp_payflow_iframe_total'); 
		}

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_payflow_iframe_order_status_id'])) {
			$this->data['pp_payflow_iframe_order_status_id'] = $this->request->post['pp_payflow_iframe_order_status_id'];
		} else {
			$this->data['pp_payflow_iframe_order_status_id'] = $this->config->get('pp_payflow_iframe_order_status_id');
		}

		if (isset($this->request->post['pp_payflow_iframe_geo_zone_id'])) {
			$this->data['pp_payflow_iframe_geo_zone_id'] = $this->request->post['pp_payflow_iframe_geo_zone_id'];
		} else {
			$this->data['pp_payflow_iframe_geo_zone_id'] = $this->config->get('pp_payflow_iframe_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_payflow_iframe_status'])) {
			$this->data['pp_payflow_iframe_status'] = $this->request->post['pp_payflow_iframe_status'];
		} else {
			$this->data['pp_payflow_iframe_status'] = $this->config->get('pp_payflow_iframe_status');
		}

		if (isset($this->request->post['pp_payflow_iframe_sort_order'])) {
			$this->data['pp_payflow_iframe_sort_order'] = $this->request->post['pp_payflow_iframe_sort_order'];
		} else {
			$this->data['pp_payflow_iframe_sort_order'] = $this->config->get('pp_payflow_iframe_sort_order');
		}

		if (isset($this->request->post['pp_payflow_iframe_checkout_method'])) {
			$this->data['pp_payflow_iframe_checkout_method'] = $this->request->post['pp_payflow_iframe_checkout_method'];
		} else {
			$this->data['pp_payflow_iframe_checkout_method'] = $this->config->get('pp_payflow_iframe_checkout_method');
		}

		if (isset($this->request->post['pp_payflow_iframe_debug'])) {
			$this->data['pp_payflow_iframe_debug'] = $this->request->post['pp_payflow_iframe_debug'];
		} else {
			$this->data['pp_payflow_iframe_debug'] = $this->config->get('pp_payflow_iframe_debug');
		}

		$this->data['cancel_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_payflow_iframe/pp_cancel';
		$this->data['error_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_payflow_iframe/pp_error';
		$this->data['return_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_payflow_iframe/pp_return';
		$this->data['post_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_payflow_iframe/pp_post';

		$this->template = 'payment/pp_payflow_iframe.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
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

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_payment'),
				'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			);

			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('payment/pp_payflow_iframe', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			);

			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_refund'),
				'href' => $this->url->link('payment/pp_payflow_iframe/refund', 'transaction_reference=' . $this->request->get['transaction_reference'] . '&token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			);

			$this->data['transaction_reference'] = $transaction['transaction_reference'];
			$this->data['transaction_amount'] = number_format($transaction['amount'], 2);
			$this->data['cancel'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $transaction['order_id'], 'SSL');

			$this->data['token'] = $this->session->data['token'];

			$this->data['heading_refund'] = $this->language->get('heading_refund');

			$this->data['entry_transaction_reference'] = $this->language->get('entry_transaction_reference');
			$this->data['entry_transaction_amount'] = $this->language->get('entry_transaction_amount');
			$this->data['entry_refund_amount'] = $this->language->get('entry_refund_amount');

			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_refund'] = $this->language->get('button_refund');

			$this->template = 'payment/pp_payflow_iframe_refund.tpl';

			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		} else {
			return $this->forward('error/not_found');
		}
	}

	public function do_refund() {
		$this->load->model('payment/pp_payflow_iframe');
		$this->load->language('payment/pp_payflow_iframe');
		$json = array();

		if (isset($this->request->post['transaction_reference']) && isset($this->request->post['amount'])) {

			$transaction = $this->model_payment_pp_payflow_iframe->getTransaction($this->request->post['transaction_reference']);

			if ($transaction) {
				$call_data = array(
					'TRXTYPE' => 'C',
					'TENDER' => 'C',
					'ORIGID' => $transaction['transaction_reference'],
					'AMT' => $this->request->post['amount'],
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
					'TRXTYPE' => 'D',
					'TENDER' => 'C',
					'ORIGID' => $paypal_order['transaction_reference'],
					'AMT' => $this->request->post['amount'],
					'CAPTURECOMPLETE' => $complete,
				);

				$result = $this->model_payment_pp_payflow_iframe->call($call_data);

				if ($result['RESULT'] == 0) {

					$data = array(
						'order_id' => $order_id,
						'type' => 'D',
						'transaction_reference' => $result['PNREF'],
						'amount' => $this->request->post['amount'],
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

		$this->response->setOutput(json_encode($json));
	}

	public function orderAction() {
		$this->load->model('payment/pp_payflow_iframe');
		$this->load->language('payment/pp_payflow_iframe');

		$order_id = $this->request->get['order_id'];

		$paypal_order = $this->model_payment_pp_payflow_iframe->getOrder($order_id);

		if ($paypal_order) {
			$this->data['entry_capture_status'] = $this->language->get('entry_capture_status');
			$this->data['entry_captured_amount'] = $this->language->get('entry_captured_amount');
			$this->data['entry_capture'] = $this->language->get('entry_capture');
			$this->data['entry_void'] = $this->language->get('entry_void');
			$this->data['entry_transactions'] = $this->language->get('entry_transactions');
			$this->data['entry_complete_capture'] = $this->language->get('entry_complete_capture');

			$this->data['text_payment_info'] = $this->language->get('text_payment_info');
			$this->data['text_complete'] = $this->language->get('text_complete');
			$this->data['text_incomplete'] = $this->language->get('text_incomplete');
			$this->data['text_confirm_void'] = $this->language->get('text_confirm_void');

			$this->data['column_transaction_id'] = $this->language->get('column_transaction_id');
			$this->data['column_transaction_type'] = $this->language->get('column_transaction_type');
			$this->data['column_amount'] = $this->language->get('column_amount');
			$this->data['column_time'] = $this->language->get('column_time');
			$this->data['column_actions'] = $this->language->get('column_actions');

			$this->data['button_capture'] = $this->language->get('button_capture');
			$this->data['button_void'] = $this->language->get('button_void');

			$this->data['complete'] = $paypal_order['complete'];
			$this->data['order_id'] = $this->request->get['order_id'];
			$this->data['token'] = $this->request->get['token'];

			$this->data['transactions'] = array();

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

				$this->data['transactions'][] = array(
					'transaction_reference' => $transaction['transaction_reference'],
					'transaction_type' => $transaction_type,
					'time' => $transaction['time'],
					'amount' => $transaction['amount'],
					'actions' => $actions,
				);
			}

			$this->template = 'payment/pp_payflow_iframe_order.tpl';
			$this->response->setOutput($this->render());
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

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>