<?php
class ControllerExtensionPaymentPPExpress extends Controller {
	private $error = array();
	private $opencart_connect_url = 'https://www.opencart.com/index.php?route=external/paypal_auth/connect';
	private $opencart_retrieve_url = 'https://www.opencart.com/index.php?route=external/paypal_auth/retrieve';

	public function index() {
		$this->load->language('extension/payment/pp_express');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_pp_express', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['button_configure'] = $this->url->link('extension/module/pp_button/configure', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['signature'])) {
			$data['error_signature'] = $this->error['signature'];
		} else {
			$data['error_signature'] = '';
		}

		if (isset($this->error['sandbox_username'])) {
			$data['error_sandbox_username'] = $this->error['sandbox_username'];
		} else {
			$data['error_sandbox_username'] = '';
		}

		if (isset($this->error['sandbox_password'])) {
			$data['error_sandbox_password'] = $this->error['sandbox_password'];
		} else {
			$data['error_sandbox_password'] = '';
		}

		if (isset($this->error['sandbox_signature'])) {
			$data['error_sandbox_signature'] = $this->error['sandbox_signature'];
		} else {
			$data['error_sandbox_signature'] = '';
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
			'href' => $this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['action'] = $this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		$data['search'] = $this->url->link('extension/payment/pp_express/search', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->post['payment_pp_express_username'])) {
			$data['payment_pp_express_username'] = $this->request->post['payment_pp_express_username'];
		} else {
			$data['payment_pp_express_username'] = $this->config->get('payment_pp_express_username');
		}

		if (isset($this->request->post['payment_pp_express_password'])) {
			$data['payment_pp_express_password'] = $this->request->post['payment_pp_express_password'];
		} else {
			$data['payment_pp_express_password'] = $this->config->get('payment_pp_express_password');
		}

		if (isset($this->request->post['payment_pp_express_signature'])) {
			$data['payment_pp_express_signature'] = $this->request->post['payment_pp_express_signature'];
		} else {
			$data['payment_pp_express_signature'] = $this->config->get('payment_pp_express_signature');
		}

		if (isset($this->request->post['payment_pp_express_sandbox_username'])) {
			$data['payment_pp_express_sandbox_username'] = $this->request->post['payment_pp_express_sandbox_username'];
		} else {
			$data['payment_pp_express_sandbox_username'] = $this->config->get('payment_pp_express_sandbox_username');
		}

		if (isset($this->request->post['payment_pp_express_sandbox_password'])) {
			$data['payment_pp_express_sandbox_password'] = $this->request->post['payment_pp_express_sandbox_password'];
		} else {
			$data['payment_pp_express_sandbox_password'] = $this->config->get('payment_pp_express_sandbox_password');
		}

		if (isset($this->request->post['payment_pp_express_sandbox_signature'])) {
			$data['payment_pp_express_sandbox_signature'] = $this->request->post['payment_pp_express_sandbox_signature'];
		} else {
			$data['payment_pp_express_sandbox_signature'] = $this->config->get('payment_pp_express_sandbox_signature');
		}

		$data['ipn_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/pp_express/ipn';

		if (isset($this->request->post['payment_pp_express_test'])) {
			$data['payment_pp_express_test'] = $this->request->post['payment_pp_express_test'];
		} else {
			$data['payment_pp_express_test'] = $this->config->get('payment_pp_express_test');
		}

		if (isset($this->request->post['payment_pp_express_debug'])) {
			$data['payment_pp_express_debug'] = $this->request->post['payment_pp_express_debug'];
		} else {
			$data['payment_pp_express_debug'] = $this->config->get('payment_pp_express_debug');
		}

		if (isset($this->request->post['payment_pp_express_incontext_disable'])) {
			$data['payment_pp_express_incontext_disable'] = $this->request->post['payment_pp_express_incontext_disable'];
		} else {
			$data['payment_pp_express_incontext_disable'] = $this->config->get('payment_pp_express_incontext_disable');
		}

		if (isset($this->request->post['payment_pp_express_currency'])) {
			$data['payment_pp_express_currency'] = $this->request->post['payment_pp_express_currency'];
		} else {
			$data['payment_pp_express_currency'] = $this->config->get('payment_pp_express_currency');
		}

		$this->load->model('extension/payment/pp_express');

		$data['currencies'] = $this->model_extension_payment_pp_express->getCurrencies();

		if (isset($this->request->post['payment_pp_express_recurring_cancel'])) {
			$data['payment_pp_express_recurring_cancel'] = $this->request->post['payment_pp_express_recurring_cancel'];
		} else {
			$data['payment_pp_express_recurring_cancel'] = $this->config->get('payment_pp_express_recurring_cancel');
		}

		if (isset($this->request->post['payment_pp_express_transaction'])) {
			$data['payment_pp_express_transaction'] = $this->request->post['payment_pp_express_transaction'];
		} else {
			$data['payment_pp_express_transaction'] = $this->config->get('payment_pp_express_transaction');
		}

		if (isset($this->request->post['payment_pp_express_total'])) {
			$data['payment_pp_express_total'] = $this->request->post['payment_pp_express_total'];
		} else {
			$data['payment_pp_express_total'] = $this->config->get('payment_pp_express_total');
		}

		if (isset($this->request->post['payment_pp_express_geo_zone_id'])) {
			$data['payment_pp_express_geo_zone_id'] = $this->request->post['payment_pp_express_geo_zone_id'];
		} else {
			$data['payment_pp_express_geo_zone_id'] = $this->config->get('payment_pp_express_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_pp_express_status'])) {
			$data['payment_pp_express_status'] = $this->request->post['payment_pp_express_status'];
		} else {
			$data['payment_pp_express_status'] = $this->config->get('payment_pp_express_status');
		}

		if (isset($this->request->post['payment_pp_express_sort_order'])) {
			$data['payment_pp_express_sort_order'] = $this->request->post['payment_pp_express_sort_order'];
		} else {
			$data['payment_pp_express_sort_order'] = $this->config->get('payment_pp_express_sort_order');
		}

		if (isset($this->request->post['payment_pp_express_canceled_reversal_status_id'])) {
			$data['payment_pp_express_canceled_reversal_status_id'] = $this->request->post['payment_pp_express_canceled_reversal_status_id'];
		} else {
			$data['payment_pp_express_canceled_reversal_status_id'] = $this->config->get('payment_pp_express_canceled_reversal_status_id');
		}

		if (isset($this->request->post['payment_pp_express_completed_status_id'])) {
			$data['payment_pp_express_completed_status_id'] = $this->request->post['payment_pp_express_completed_status_id'];
		} else {
			$data['payment_pp_express_completed_status_id'] = $this->config->get('payment_pp_express_completed_status_id');
		}

		if (isset($this->request->post['payment_pp_express_denied_status_id'])) {
			$data['payment_pp_express_denied_status_id'] = $this->request->post['payment_pp_express_denied_status_id'];
		} else {
			$data['payment_pp_express_denied_status_id'] = $this->config->get('payment_pp_express_denied_status_id');
		}

		if (isset($this->request->post['payment_pp_express_expired_status_id'])) {
			$data['payment_pp_express_expired_status_id'] = $this->request->post['payment_pp_express_expired_status_id'];
		} else {
			$data['payment_pp_express_expired_status_id'] = $this->config->get('payment_pp_express_expired_status_id');
		}

		if (isset($this->request->post['payment_pp_express_failed_status_id'])) {
			$data['payment_pp_express_failed_status_id'] = $this->request->post['payment_pp_express_failed_status_id'];
		} else {
			$data['payment_pp_express_failed_status_id'] = $this->config->get('payment_pp_express_failed_status_id');
		}

		if (isset($this->request->post['payment_pp_express_pending_status_id'])) {
			$data['payment_pp_express_pending_status_id'] = $this->request->post['payment_pp_express_pending_status_id'];
		} else {
			$data['payment_pp_express_pending_status_id'] = $this->config->get('payment_pp_express_pending_status_id');
		}

		if (isset($this->request->post['payment_pp_express_processed_status_id'])) {
			$data['payment_pp_express_processed_status_id'] = $this->request->post['payment_pp_express_processed_status_id'];
		} else {
			$data['payment_pp_express_processed_status_id'] = $this->config->get('payment_pp_express_processed_status_id');
		}

		if (isset($this->request->post['payment_pp_express_refunded_status_id'])) {
			$data['payment_pp_express_refunded_status_id'] = $this->request->post['payment_pp_express_refunded_status_id'];
		} else {
			$data['payment_pp_express_refunded_status_id'] = $this->config->get('payment_pp_express_refunded_status_id');
		}

		if (isset($this->request->post['payment_pp_express_reversed_status_id'])) {
			$data['payment_pp_express_reversed_status_id'] = $this->request->post['payment_pp_express_reversed_status_id'];
		} else {
			$data['payment_pp_express_reversed_status_id'] = $this->config->get('payment_pp_express_reversed_status_id');
		}

		if (isset($this->request->post['payment_pp_express_voided_status_id'])) {
			$data['payment_pp_express_voided_status_id'] = $this->request->post['payment_pp_express_voided_status_id'];
		} else {
			$data['payment_pp_express_voided_status_id'] = $this->config->get('payment_pp_express_voided_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_pp_express_allow_note'])) {
			$data['payment_pp_express_allow_note'] = $this->request->post['payment_pp_express_allow_note'];
		} else {
			$data['payment_pp_express_allow_note'] = $this->config->get('payment_pp_express_allow_note');
		}

		if (isset($this->request->post['payment_pp_express_colour'])) {
			$data['payment_pp_express_colour'] = str_replace('#', '', $this->request->post['payment_pp_express_colour']);
		} else {
			$data['payment_pp_express_colour'] = $this->config->get('payment_pp_express_colour');
		}

		if (isset($this->request->post['payment_pp_express_logo'])) {
			$data['payment_pp_express_logo'] = $this->request->post['payment_pp_express_logo'];
		} else {
			$data['payment_pp_express_logo'] = $this->config->get('payment_pp_express_logo');
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['payment_pp_express_logo']) && is_file(DIR_IMAGE . $this->request->post['payment_pp_express_logo'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['payment_pp_express_logo'], 750, 90);
		} elseif (is_file(DIR_IMAGE . $this->config->get('payment_pp_express_logo'))) {
			$data['thumb'] = $this->model_tool_image->resize($this->config->get('payment_pp_express_logo'), 750, 90);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 750, 90);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 750, 90);

		if (isset($this->request->get['retrieve_code']) && isset($this->request->get['merchant_id'])) {
			$curl = curl_init($this->opencart_retrieve_url);

			$post_data = array(
				'merchant_id' => $this->request->get['merchant_id'],
				'retrieve_code' => $this->request->get['retrieve_code'],
			);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

			$curl_response = curl_exec($curl);
			$config_response = json_decode($curl_response, true);
			curl_close($curl);

			if (isset($config_response['api_user_name']) && isset($config_response['api_password']) && isset($config_response['signature'])) {
				$pp_express_settings = $this->model_setting_setting->getSetting('payment_pp_express');

				if ($config_response['environment'] == 'sandbox') {
					$pp_express_settings['payment_pp_express_sandbox_username'] = $config_response['api_user_name'];
					$pp_express_settings['payment_pp_express_sandbox_password'] = $config_response['api_password'];
					$pp_express_settings['payment_pp_express_sandbox_signature'] = $config_response['signature'];
					$pp_express_settings['payment_pp_express_test'] = 1;

					$data['payment_pp_express_sandbox_username'] = $config_response['api_user_name'];
					$data['payment_pp_express_sandbox_password'] = $config_response['api_password'];
					$data['payment_pp_express_sandbox_signature'] = $config_response['signature'];
					$data['payment_pp_express_test'] = 1;
				} else {
					$pp_express_settings['payment_pp_express_username'] = $config_response['api_user_name'];
					$pp_express_settings['payment_pp_express_password'] = $config_response['api_password'];
					$pp_express_settings['payment_pp_express_signature'] = $config_response['signature'];
					$pp_express_settings['payment_pp_express_test'] = 0;

					$data['payment_pp_express_username'] = $config_response['api_user_name'];
					$data['payment_pp_express_password'] = $config_response['api_password'];
					$data['payment_pp_express_signature'] = $config_response['signature'];
					$data['payment_pp_express_test'] = 0;
				}

				$data['retrieve_success'] = 1;
				$data['text_retrieve'] = $this->language->get('text_retrieve');

				$this->model_setting_setting->editSetting('payment_pp_express', $pp_express_settings);
			}
		}

		$this->load->model('localisation/country');

		$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

		$post_data = array(
			'return_url' => $this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true),
			'store_url' => HTTPS_CATALOG,
			'store_version' => VERSION,
			'store_country' => (isset($country['iso_code_3']) ? $country['iso_code_3'] : ''),
		);

		// Create sandbox link
		$curl = curl_init($this->opencart_connect_url);

		$post_data['environment'] = 'sandbox';

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));

		$curl_response = curl_exec($curl);
		$curl_response = json_decode($curl_response, true);

		curl_close($curl);

		$data['auth_connect_url_sandbox'] = '';
		if (isset($curl_response['url']) && !empty($curl_response['url'])) {
			$data['auth_connect_url_sandbox'] = $curl_response['url'];
		}

		// Create Live link
		$curl = curl_init($this->opencart_connect_url);

		$post_data['environment'] = 'live';

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));

		$curl_response = curl_exec($curl);
		$curl_response = json_decode($curl_response, true);

		curl_close($curl);

		$data['auth_connect_url_live'] = '';
		if (isset($curl_response['url']) && !empty($curl_response['url'])) {
			$data['auth_connect_url_live'] = $curl_response['url'];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_express', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/pp_express')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['payment_pp_express_test']) {
			if (!$this->request->post['payment_pp_express_sandbox_username']) {
				$this->error['sandbox_username'] = $this->language->get('error_sandbox_username');
			}

			if (!$this->request->post['payment_pp_express_sandbox_password']) {
				$this->error['sandbox_password'] = $this->language->get('error_sandbox_password');
			}

			if (!$this->request->post['payment_pp_express_sandbox_signature']) {
				$this->error['sandbox_signature'] = $this->language->get('error_sandbox_signature');
			}
		} else {
			if (!$this->request->post['payment_pp_express_username']) {
				$this->error['username'] = $this->language->get('error_username');
			}

			if (!$this->request->post['payment_pp_express_password']) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if (!$this->request->post['payment_pp_express_signature']) {
				$this->error['signature'] = $this->language->get('error_signature');
			}
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/payment/pp_express');

		$this->model_extension_payment_pp_express->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/pp_express');

		$this->model_extension_payment_pp_express->uninstall();
	}

	public function order() {
		if ($this->config->get('payment_pp_express_status')) {
			$this->load->language('extension/payment/pp_express_order');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('extension/payment/pp_express');

			$paypal_info = $this->model_extension_payment_pp_express->getPayPalOrder($order_id);

			if ($paypal_info) {
				$data['user_token'] = $this->session->data['user_token'];

				$data['order_id'] = $this->request->get['order_id'];

				$data['capture_status'] = $paypal_info['capture_status'];

				$data['total'] = $paypal_info['total'];

				$captured = number_format($this->model_extension_payment_pp_express->getCapturedTotal($paypal_info['paypal_order_id']), 2);

				$data['captured'] = $captured;

				$data['capture_remaining'] = number_format($paypal_info['total'] - $captured, 2);

				$refunded = number_format($this->model_extension_payment_pp_express->getRefundedTotal($paypal_info['paypal_order_id']), 2);

				$data['refunded'] = $refunded;

				return $this->load->view('extension/payment/pp_express_order', $data);
			}
		}
	}

	public function transaction() {
		$this->load->language('extension/payment/pp_express_order');

		$data['transactions'] = array();

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('extension/payment/pp_express');

		$paypal_info = $this->model_extension_payment_pp_express->getOrder($order_id);

		if ($paypal_info) {
			$results = $this->model_extension_payment_pp_express->getTransactions($paypal_info['paypal_order_id']);

			foreach ($results as $result) {
				$data['transactions'][] = array(
					'transaction_id' => $result['transaction_id'],
					'amount'         => $result['amount'],
					'payment_type'   => $result['payment_type'],
					'payment_status' => $result['payment_status'],
					'pending_reason' => $result['pending_reason'],
					'date_added'     => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
					'view'           => $this->url->link('extension/payment/pp_express/info', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $result['transaction_id'], true),
					'refund'         => $this->url->link('extension/payment/pp_express/refund', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $result['transaction_id'], true),
					'resend'         => $this->url->link('extension/payment/pp_express/resend', 'user_token=' . $this->session->data['user_token'] . '&paypal_order_transaction_id=' . $result['paypal_order_transaction_id'], true)
				);
			}
		}

		$this->response->setOutput($this->load->view('extension/payment/pp_express_transaction', $data));
	}

	public function capture() {
		$json = array();

		$this->load->language('extension/payment/pp_express_order');

		if (!isset($this->request->post['amount']) && $this->request->post['amount'] > 0) {
			$json['error'] = $this->language->get('error_capture');
		}

		if (!$json) {
			$this->load->model('extension/payment/pp_express');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$paypal_info = $this->model_extension_payment_pp_express->getOrder($order_id);

			if ($paypal_info) {
				// If this is the final amount to capture or not
				if ($this->request->post['complete'] == 1) {
					$complete = 'Complete';
				} else {
					$complete = 'NotComplete';
				}

				$request = array(
					'METHOD'          => 'DoCapture',
					'AUTHORIZATIONID' => $paypal_info['authorization_id'],
					'AMT'             => number_format($this->request->post['amount'], 2),
					'CURRENCYCODE'    => $paypal_info['currency_code'],
					'COMPLETETYPE'    => $complete,
					'MSGSUBID'        => uniqid(mt_rand(), true)
				);

				$response = $this->model_extension_payment_pp_express->call($request);

				if (isset($response['ACK']) && ($response['ACK'] != 'Failure') && ($response['ACK'] != 'FailureWithWarning')) {
					$transaction_data = array(
						'paypal_order_id'       => $paypal_info['paypal_order_id'],
						'transaction_id'        => $response['TRANSACTIONID'],
						'parent_id'             => $paypal_info['authorization_id'],
						'note'                  => '',
						'msgsubid'              => $response['MSGSUBID'],
						'receipt_id'            => '',
						'payment_type'          => $response['PAYMENTTYPE'],
						'payment_status'        => $response['PAYMENTSTATUS'],
						'pending_reason'        => (isset($response['PENDINGREASON']) ? $response['PENDINGREASON'] : ''),
						'transaction_entity'    => 'payment',
						'amount'                => $response['AMT'],
						'debug_data'            => json_encode($response)
					);

					$this->model_extension_payment_pp_express->addTransaction($transaction_data);

					$captured = number_format($this->model_extension_payment_pp_express->getCapturedTotal($paypal_info['paypal_order_id']), 2);
					$refunded = number_format($this->model_extension_payment_pp_express->getRefundedTotal($paypal_info['paypal_order_id']), 2);

					$json['captured'] = $captured;
					$json['refunded'] = $refunded;
					$json['remaining'] = number_format($paypal_info['total'] - $captured, 2);

					if ($this->request->post['complete'] == 1 || $json['remaining'] == 0.00) {
						$json['capture_status'] = $this->language->get('text_complete');

						$this->model_extension_payment_pp_express->editPayPalOrderStatus($order_id, 'Complete');
					}

					$json['success'] = $this->language->get('text_success');
				} else {
					$json['error'] = (isset($response_info['L_SHORTMESSAGE0']) ? $response_info['L_SHORTMESSAGE0'] : $this->language->get('error_transaction'));
				}
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function refund() {
		$this->load->language('extension/payment/pp_express_refund');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_pp_express'),
			'href' => $this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pp_express/refund', 'user_token=' . $this->session->data['user_token'], true),
		);

		//button actions
		$data['action'] = $this->url->link('extension/payment/pp_express/doRefund', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true);

		$data['transaction_id'] = $this->request->get['transaction_id'];

		$this->load->model('extension/payment/pp_express');
		$pp_transaction = $this->model_extension_payment_pp_express->getTransaction($this->request->get['transaction_id']);

		$data['amount_original'] = $pp_transaction['AMT'];
		$data['currency_code'] = $pp_transaction['CURRENCYCODE'];

		$refunded = number_format($this->model_extension_payment_pp_express->getRefundedTotalByParentId($this->request->get['transaction_id']), 2);

		if ($refunded != 0.00) {
			$data['refund_available'] = number_format($data['amount_original'] + $refunded, 2);
			$data['attention'] = $this->language->get('text_current_refunds') . ': ' . $data['refund_available'];
		} else {
			$data['refund_available'] = '';
			$data['attention'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_express_refund', $data));
	}

	public function doRefund() {
		/**
		 * used to issue a refund for a captured payment
		 *
		 * refund can be full or partial
		 */
		if (isset($this->request->post['transaction_id']) && isset($this->request->post['refund_full'])) {

			$this->load->model('extension/payment/pp_express');
			$this->load->language('extension/payment/pp_express_refund');

			if ($this->request->post['refund_full'] == 0 && $this->request->post['amount'] == 0) {
				$this->session->data['error'] = $this->language->get('error_partial_amt');
			} else {
				$order_id = $this->model_extension_payment_pp_express->getOrderId($this->request->post['transaction_id']);
				$paypal_order = $this->model_extension_payment_pp_express->getOrder($order_id);

				if ($paypal_order) {
					$call_data = array();
					$call_data['METHOD'] = 'RefundTransaction';
					$call_data['TRANSACTIONID'] = $this->request->post['transaction_id'];
					$call_data['NOTE'] = urlencode($this->request->post['refund_message']);
					$call_data['MSGSUBID'] = uniqid(mt_rand(), true);

					$current_transaction = $this->model_extension_payment_pp_express->getLocalTransaction($this->request->post['transaction_id']);

					if ($this->request->post['refund_full'] == 1) {
						$call_data['REFUNDTYPE'] = 'Full';
					} else {
						$call_data['REFUNDTYPE'] = 'Partial';
						$call_data['AMT'] = number_format($this->request->post['amount'], 2);
						$call_data['CURRENCYCODE'] = $this->request->post['currency_code'];
					}

					$result = $this->model_extension_payment_pp_express->call($call_data);

					$transaction = array(
						'paypal_order_id' => $paypal_order['paypal_order_id'],
						'transaction_id' => '',
						'parent_transaction_id' => $this->request->post['transaction_id'],
						'note' => $this->request->post['refund_message'],
						'msgsubid' => $call_data['MSGSUBID'],
						'receipt_id' => '',
						'payment_type' => '',
						'payment_status' => 'Refunded',
						'transaction_entity' => 'payment',
						'pending_reason' => '',
						'amount' => '-' . (isset($call_data['AMT']) ? $call_data['AMT'] : $current_transaction['amount']),
						'debug_data' => json_encode($result)
					);

					if ($result == false) {
						$transaction['payment_status'] = 'Failed';
						$this->model_extension_payment_pp_express->addTransaction($transaction, $call_data);
						$this->response->redirect($this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $paypal_order['order_id'], true));
					} else if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {

						$transaction['transaction_id'] = $result['REFUNDTRANSACTIONID'];
						$transaction['payment_type'] = $result['REFUNDSTATUS'];
						$transaction['pending_reason'] = $result['PENDINGREASON'];
						$transaction['amount'] = '-' . $result['GROSSREFUNDAMT'];

						$this->model_extension_payment_pp_express->addTransaction($transaction);

						//edit transaction to refunded status
						if ($result['TOTALREFUNDEDAMOUNT'] == $this->request->post['amount_original']) {
							$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Refunded' WHERE `transaction_id` = '" . $this->db->escape($this->request->post['transaction_id']) . "' LIMIT 1");
						} else {
							$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Partially-Refunded' WHERE `transaction_id` = '" . $this->db->escape($this->request->post['transaction_id']) . "' LIMIT 1");
						}

						//redirect back to the order
						$this->response->redirect($this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $paypal_order['order_id'], true));
					} else {
						$this->model_extension_payment_pp_express->log(json_encode($result));
						$this->session->data['error'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : 'There was an error') . (isset($result['L_LONGMESSAGE0']) ? '<br />' . $result['L_LONGMESSAGE0'] : '');
						$this->response->redirect($this->url->link('extension/payment/pp_express/refund', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $this->request->post['transaction_id'], true));
					}
				} else {
					$this->session->data['error'] = $this->language->get('error_data_missing');
					$this->response->redirect($this->url->link('extension/payment/pp_express/refund', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $this->request->post['transaction_id'], true));
				}
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_data');
			$this->response->redirect($this->url->link('extension/payment/pp_express/refund', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $this->request->post['transaction_id'], true));
		}
	}

	/**
	 * used to void an authorised payment
	 */
	public function void() {
		$json = array();

		$this->load->language('extension/payment/pp_express_order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('extension/payment/pp_express');

		$paypal_info = $this->model_extension_payment_pp_express->getOrder($order_id);

		if ($paypal_info) {
			$request = array(
				'METHOD'          => 'DoVoid',
				'AUTHORIZATIONID' => $paypal_info['authorization_id'],
				'MSGSUBID'        => uniqid(mt_rand(), true)
			);

			$response_info = $this->model_extension_payment_pp_express->call($request);

			if (isset($response_info['ACK']) && ($response_info['ACK'] != 'Failure') && ($response_info['ACK'] != 'FailureWithWarning')) {
				$transaction = array(
					'paypal_order_id'       => $paypal_info['paypal_order_id'],
					'transaction_id'        => '',
					'parent_id'             => $paypal_info['authorization_id'],
					'note'                  => '',
					'msgsubid'              => '',
					'receipt_id'            => '',
					'payment_type'          => 'void',
					'payment_status'        => 'Void',
					'pending_reason'        => '',
					'transaction_entity'    => 'auth',
					'amount'                => '',
					'debug_data'            => json_encode($response_info)
				);

				$this->model_extension_payment_pp_express->addTransaction($transaction);

				$this->model_extension_payment_pp_express->editPayPalOrderStatus($order_id, 'Complete');

				$json['capture_status'] = 'Complete';

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : $this->language->get('error_transaction'));
			}
		} else {
			$json['error'] = $this->language->get('error_not_found');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Cancel an active recurring
	public function recurringCancel() {
		$json = array();

		$this->load->language('extension/recurring/pp_express');

		//cancel an active recurring
		$this->load->model('account/recurring');

		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}

		$recurring_info = $this->model_account_recurring->getOrderRecurring($order_recurring_id);

		if ($recurring_info && $recurring_info['reference']) {
			if ($this->config->get('payment_pp_express_test')) {
				$api_url = 'https://api-3t.sandbox.paypal.com/nvp';
				$api_username = $this->config->get('payment_pp_express_sandbox_username');
				$api_password = $this->config->get('payment_pp_express_sandbox_password');
				$api_signature = $this->config->get('payment_pp_express_sandbox_signature');
			} else {
				$api_url = 'https://api-3t.paypal.com/nvp';
				$api_username = $this->config->get('payment_pp_express_username');
				$api_password = $this->config->get('payment_pp_express_password');
				$api_signature = $this->config->get('payment_pp_express_signature');
			}

			$request = array(
				'USER'         => $api_username,
				'PWD'          => $api_password,
				'SIGNATURE'    => $api_signature,
				'VERSION'      => '109.0',
				'BUTTONSOURCE' => 'OpenCart_2.0_EC',
				'METHOD'       => 'SetExpressCheckout',
				'METHOD'       => 'ManageRecurringPaymentsProfileStatus',
				'PROFILEID'    => $recurring_info['reference'],
				'ACTION'       => 'Cancel'
			);

			$curl = curl_init($api_url);

			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($curl);

			if (!$response) {
				$this->log(sprintf($this->language->get('error_curl'), curl_errno($curl), curl_error($curl)));
			}

			curl_close($curl);

			$response_info = array();

			parse_str($response, $response_info);

			if (isset($response_info['PROFILEID'])) {
				$this->model_account_recurring->editOrderRecurringStatus($order_recurring_id, 4);
				$this->model_account_recurring->addOrderRecurringTransaction($order_recurring_id, 5);

				$json['success'] = $this->language->get('text_cancelled');
			} else {
				$json['error'] = sprintf($this->language->get('error_not_cancelled'), $response_info['L_LONGMESSAGE0']);
			}
		} else {
			$json['error'] = $this->language->get('error_not_found');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function resend() {
		$json = array();

		$this->load->language('extension/payment/pp_express');

		if (isset($this->request->get['paypal_order_transaction_id'])) {
			$paypal_order_transaction_id = $this->request->get['paypal_order_transaction_id'];
		} else {
			$paypal_order_transaction_id = 0;
		}

		$this->load->model('extension/payment/pp_express');

		$transaction = $this->model_extension_payment_pp_express->getFailedTransaction($paypal_order_transaction_id);

		if ($transaction) {

			$call_data = json_decode($transaction['call_data'], true);

			$result = $this->model_extension_payment_pp_express->call($call_data);

			if ($result) {

				$parent_transaction = $this->model_extension_payment_pp_express->getLocalTransaction($transaction['parent_id']);

				if ($parent_transaction['amount'] == abs($transaction['amount'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Refunded' WHERE `transaction_id` = '" . $this->db->escape($transaction['parent_id']) . "' LIMIT 1");
				} else {
					$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Partially-Refunded' WHERE `transaction_id` = '" . $this->db->escape($transaction['parent_id']) . "' LIMIT 1");
				}

				if (isset($result['REFUNDTRANSACTIONID'])) {
					$transaction['transaction_id'] = $result['REFUNDTRANSACTIONID'];
				} else {
					$transaction['transaction_id'] = $result['TRANSACTIONID'];
				}

				if (isset($result['PAYMENTTYPE'])) {
					$transaction['payment_type'] = $result['PAYMENTTYPE'];
				} else {
					$transaction['payment_type'] = $result['REFUNDSTATUS'];
				}

				if (isset($result['PAYMENTSTATUS'])) {
					$transaction['payment_status'] = $result['PAYMENTSTATUS'];
				} else {
					$transaction['payment_status'] = 'Refunded';
				}

				if (isset($result['AMT'])) {
					$transaction['amount'] = $result['AMT'];
				} else {
					$transaction['amount'] = $transaction['amount'];
				}

				$transaction['pending_reason'] = (isset($result['PENDINGREASON']) ? $result['PENDINGREASON'] : '');

				$this->model_extension_payment_pp_express->updateTransaction($transaction);

				$json['success'] = $this->language->get('success_transaction_resent');
			} else {
				$json['error'] = $this->language->get('error_timeout');
			}
		} else {
			$json['error'] = $this->language->get('error_transaction_missing');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function search() {
		$this->load->language('extension/payment/pp_express_search');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['user_token'] = $this->session->data['user_token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_pp_express'),
			'href' => $this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pp_express/search', 'user_token=' . $this->session->data['user_token'], true),
		);

		$this->load->model('extension/payment/pp_express');

		$data['currency_codes'] = $this->model_extension_payment_pp_express->getCurrencies();

		$data['default_currency'] = $this->config->get('payment_pp_express_currency');

		$data['date_start'] = date("Y-m-d", strtotime('-30 days'));
		$data['date_end'] = date("Y-m-d");
		$data['view_link'] = $this->url->link('extension/payment/pp_express/info', 'user_token=' . $this->session->data['user_token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_express_search', $data));
	}

	public function info() {
		$this->load->language('extension/payment/pp_express_view');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_pp_express'),
			'href' => $this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pp_express/info', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $this->request->get['transaction_id'], true),
		);

		$this->load->model('extension/payment/pp_express');

		$data['transaction'] = $this->model_extension_payment_pp_express->getTransaction($this->request->get['transaction_id']);
		$data['lines'] = $this->formatRows($data['transaction']);
		$data['view_link'] = $this->url->link('extension/payment/pp_express/info', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('extension/payment/pp_express/search', 'user_token=' . $this->session->data['user_token'], true);
		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_express_view', $data));
	}

	public function doSearch() {
		/**
		 * used to search for transactions from a user account
		 */
		if (isset($this->request->post['date_start'])) {

			$this->load->model('extension/payment/pp_express');

			$call_data = array();
			$call_data['METHOD'] = 'TransactionSearch';
			$call_data['STARTDATE'] = gmdate($this->request->post['date_start'] . "\TH:i:s\Z");

			if (!empty($this->request->post['date_end'])) {
				$call_data['ENDDATE'] = gmdate($this->request->post['date_end'] . "\TH:i:s\Z");
			}

			if (!empty($this->request->post['transaction_class'])) {
				$call_data['TRANSACTIONCLASS'] = $this->request->post['transaction_class'];
			}

			if (!empty($this->request->post['status'])) {
				$call_data['STATUS'] = $this->request->post['status'];
			}

			if (!empty($this->request->post['buyer_email'])) {
				$call_data['EMAIL'] = $this->request->post['buyer_email'];
			}

			if (!empty($this->request->post['merchant_email'])) {
				$call_data['RECEIVER'] = $this->request->post['merchant_email'];
			}

			if (!empty($this->request->post['receipt_id'])) {
				$call_data['RECEIPTID'] = $this->request->post['receipt_id'];
			}

			if (!empty($this->request->post['transaction_id'])) {
				$call_data['TRANSACTIONID'] = $this->request->post['transaction_id'];
			}

			if (!empty($this->request->post['invoice_number'])) {
				$call_data['INVNUM'] = $this->request->post['invoice_number'];
			}

			if (!empty($this->request->post['auction_item_number'])) {
				$call_data['AUCTIONITEMNUMBER'] = $this->request->post['auction_item_number'];
			}

			if (!empty($this->request->post['amount'])) {
				$call_data['AMT'] = number_format($this->request->post['amount'], 2);
				$call_data['CURRENCYCODE'] = $this->request->post['currency_code'];
			}

			if (!empty($this->request->post['recurring_id'])) {
				$call_data['PROFILEID'] = $this->request->post['recurring_id'];
			}

			if (!empty($this->request->post['name_salutation'])) {
				$call_data['SALUTATION'] = $this->request->post['name_salutation'];
			}

			if (!empty($this->request->post['name_first'])) {
				$call_data['FIRSTNAME'] = $this->request->post['name_first'];
			}

			if (!empty($this->request->post['name_middle'])) {
				$call_data['MIDDLENAME'] = $this->request->post['name_middle'];
			}

			if (!empty($this->request->post['name_last'])) {
				$call_data['LASTNAME'] = $this->request->post['name_last'];
			}

			if (!empty($this->request->post['name_suffix'])) {
				$call_data['SUFFIX'] = $this->request->post['name_suffix'];
			}

			$result = $this->model_extension_payment_pp_express->call($call_data);

			if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning' && $result['ACK'] != 'Warning') {
				$response['error'] = false;
				$response['result'] = $this->formatRows($result);
			} else {
				$response['error'] = true;
				$response['error_msg'] = $result['L_LONGMESSAGE0'];
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($response));
		} else {
			$response['error'] = true;
			$response['error_msg'] = 'Enter a start date';
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($response));
		}
	}

	public function live() {
		if (isset($this->request->get['merchantId'])) {
			$this->load->language('extension/payment/pp_express');

			$this->load->model('extension/payment/pp_express');
			$this->load->model('setting/setting');

			$token = $this->model_extension_payment_pp_express->getTokens('live');

			if (isset($token->access_token)) {
				$user_info = $this->model_extension_payment_pp_express->getUserInfo($this->request->get['merchantId'], 'live', $token->access_token);
			} else {
				$this->session->data['error_api'] = $this->language->get('error_api');
			}

			if (isset($user_info->api_user_name)) {
				$this->model_setting_setting->editSettingValue('payment_pp_express', 'payment_pp_express_username', $user_info->api_user_name);
				$this->model_setting_setting->editSettingValue('payment_pp_express', 'payment_pp_express_password', $user_info->api_password);
				$this->model_setting_setting->editSettingValue('payment_pp_express', 'payment_pp_express_signature', $user_info->signature);
			} else {
				$this->session->data['error_api'] = $this->language->get('error_api');
			}
		}

		$this->response->redirect($this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function sandbox() {
		if (isset($this->request->get['merchantId'])) {
			$this->load->language('extension/payment/pp_express');

			$this->load->model('extension/payment/pp_express');
			$this->load->model('setting/setting');

			$token = $this->model_extension_payment_pp_express->getTokens('sandbox');

			if (isset($token->access_token)) {
				$user_info = $this->model_extension_payment_pp_express->getUserInfo($this->request->get['merchantId'], 'sandbox', $token->access_token);
			} else {
				$this->session->data['error_api'] = $this->language->get('error_api_sandbox');
			}

			if (isset($user_info->api_user_name)) {
				$this->model_setting_setting->editSettingValue('payment_pp_express', 'payment_pp_express_sandbox_username', $user_info->api_user_name);
				$this->model_setting_setting->editSettingValue('payment_pp_express', 'payment_pp_express_sandbox_password', $user_info->api_password);
				$this->model_setting_setting->editSettingValue('payment_pp_express', 'payment_pp_express_sandbox_signature', $user_info->signature);
			} else {
				$this->session->data['error_api'] = $this->language->get('error_api_sandbox');
			}
		}
		$this->response->redirect($this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true));
	}

	private function formatRows($data) {
		$return = array();

		foreach ($data as $k => $v) {
			$elements = preg_split("/(\d+)/", $k, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			if (isset($elements[1]) && isset($elements[0])) {
				if ($elements[0] == 'L_TIMESTAMP') {
					$v = str_replace('T', ' ', $v);
					$v = str_replace('Z', '', $v);
				}
				$return[$elements[1]][$elements[0]] = $v;
			}
		}

		return $return;
	}

	public function recurringButtons() {
		$this->load->model('sale/recurring');

		$recurring = $this->model_sale_recurring->getRecurring($this->request->get['order_recurring_id']);

		$data['buttons'] = array();

		if ($recurring['status'] == 2 || $recurring['status'] == 3) {
			$data['buttons'][] = array(
				'text' => $this->language->get('button_cancel_recurring'),
				'link' => $this->url->link('extension/payment/pp_express/recurringCancel', 'order_recurring_id=' . $this->request->get['order_recurring_id'] . '&user_token=' . $this->request->get['user_token'], true)
			);
		}

		return $this->load->view('sale/recurring_button', $data);
	}

	public function connectRedirect() {
		if ($this->user->hasPermission('modify', 'extension/extension/payment') && $this->user->hasPermission('modify', 'extension/payment/pp_express')) {
			// Install the module before doing the redirect
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('payment', 'pp_express');

			$this->install();

			$this->load->model('localisation/country');

			$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

			$post_data = array(
				'return_url' => $this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true),
				'store_url' => HTTPS_CATALOG,
				'store_version' => VERSION,
				'store_country' => (isset($country['iso_code_3']) ? $country['iso_code_3'] : ''),
			);

			// Create Live link
			$curl = curl_init($this->opencart_connect_url);

			$post_data['environment'] = 'live';

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));

			$curl_response = curl_exec($curl);
			$curl_response = json_decode($curl_response, true);

			curl_close($curl);

			if (isset($curl_response['url']) && !empty($curl_response['url'])) {
				$this->response->redirect($curl_response['url']);
			} else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
			}
		} else {
			$this->response->redirect($this->url->link('error/permission', 'user_token=' . $this->session->data['user_token'], true));
		}
	}

	public function preferredSolution() {
		$this->load->language('extension/payment/pp_express');

		$data['connect_link'] = '';
		$data['module_link'] = '';

		if ($this->config->get('payment_pp_express_username') || $this->config->get('payment_pp_express_sandbox_username')) {
			$data['module_link'] = $this->url->link('extension/payment/pp_express', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			if ($this->user->hasPermission('modify', 'extension/extension/payment')) {
				$data['connect_link'] = $this->url->link('extension/payment/pp_express/connectRedirect', 'user_token=' . $this->session->data['user_token'], true);
			}
		}

		if ($this->config->get("payment_pp_express_status") == 1) {
			$data['payment_pp_express_status'] = "enabled";
		} elseif ($this->config->get("payment_pp_express_status") == null) {
			$data['payment_pp_express_status'] = "";
		} else {
			$data['payment_pp_express_status'] = "disabled";
		}

		return $this->load->view('extension/payment/pp_express_preferred', $data);
	}
}
