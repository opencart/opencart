<?php
class ControllerExtensionPaymentCardConnect extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('setting/setting');

		$this->load->model('extension/payment/cardconnect');

		$this->load->language('extension/payment/cardconnect');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('cardconnect', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/cardconnect', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title']                 = $this->language->get('heading_title');

		$data['tab_settings']                  = $this->language->get('tab_settings');
		$data['tab_order_status']              = $this->language->get('tab_order_status');

		$data['text_edit']                     = $this->language->get('text_edit');
		$data['text_payment']                  = $this->language->get('text_payment');		
		$data['text_authorize']                = $this->language->get('text_authorize');
		$data['text_live']                     = $this->language->get('text_live');
		$data['text_test']                     = $this->language->get('text_test');
		$data['text_enabled']                  = $this->language->get('text_enabled');
		$data['text_disabled']                 = $this->language->get('text_disabled');
		$data['text_all_zones']                = $this->language->get('text_all_zones');

		$data['entry_merchant_id']             = $this->language->get('entry_merchant_id');
		$data['entry_api_username']            = $this->language->get('entry_api_username');
		$data['entry_api_password']            = $this->language->get('entry_api_password');
		$data['entry_token']                   = $this->language->get('entry_token');
		$data['entry_transaction']             = $this->language->get('entry_transaction');
		$data['entry_environment']             = $this->language->get('entry_environment');
		$data['entry_site']                    = $this->language->get('entry_site');
		$data['entry_store_cards']             = $this->language->get('entry_store_cards');
		$data['entry_echeck']                  = $this->language->get('entry_echeck');
		$data['entry_total']                   = $this->language->get('entry_total');
		$data['entry_geo_zone']                = $this->language->get('entry_geo_zone');
		$data['entry_status']                  = $this->language->get('entry_status');
		$data['entry_logging']                 = $this->language->get('entry_logging');
		$data['entry_sort_order']              = $this->language->get('entry_sort_order');
		$data['entry_cron_url']                = $this->language->get('entry_cron_url');
		$data['entry_cron_time']               = $this->language->get('entry_cron_time');
		$data['entry_order_status_pending']    = $this->language->get('entry_order_status_pending');
		$data['entry_order_status_processing'] = $this->language->get('entry_order_status_processing');

		$data['help_merchant_id']              = $this->language->get('help_merchant_id');
		$data['help_api_username']             = $this->language->get('help_api_username');
		$data['help_api_password']             = $this->language->get('help_api_password');
		$data['help_token']                    = $this->language->get('help_token');
		$data['help_transaction']              = $this->language->get('help_transaction');
		$data['help_site']                     = $this->language->get('help_site');
		$data['help_store_cards']              = $this->language->get('help_store_cards');
		$data['help_echeck']                   = $this->language->get('help_echeck');
		$data['help_total']                    = $this->language->get('help_total');
		$data['help_logging']                  = $this->language->get('help_logging');
		$data['help_cron_url']                 = $this->language->get('help_cron_url');
		$data['help_cron_time']                = $this->language->get('help_cron_time');
		$data['help_order_status_pending']     = $this->language->get('help_order_status_pending');
		$data['help_order_status_processing']  = $this->language->get('help_order_status_processing');

		$data['button_save']                   = $this->language->get('button_save');
		$data['button_cancel']                 = $this->language->get('button_cancel');

		$data['action'] = $this->url->link('extension/payment/cardconnect', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['cardconnect_merchant_id'])) {
			$data['cardconnect_merchant_id'] = $this->request->post['cardconnect_merchant_id'];
		} else {
			$data['cardconnect_merchant_id'] = $this->config->get('cardconnect_merchant_id');
		}

		if (isset($this->request->post['cardconnect_api_username'])) {
			$data['cardconnect_api_username'] = $this->request->post['cardconnect_api_username'];
		} else {
			$data['cardconnect_api_username'] = $this->config->get('cardconnect_api_username');
		}

		if (isset($this->request->post['cardconnect_api_password'])) {
			$data['cardconnect_api_password'] = $this->request->post['cardconnect_api_password'];
		} else {
			$data['cardconnect_api_password'] = $this->config->get('cardconnect_api_password');
		}

		if (isset($this->request->post['cardconnect_token'])) {
			$data['cardconnect_token'] = $this->request->post['cardconnect_token'];
		} elseif ($this->config->has('cardconnect_token')) {
			$data['cardconnect_token'] = $this->config->get('cardconnect_token');
		} else {
			$data['cardconnect_token'] = md5(time());
		}

		if (isset($this->request->post['cardconnect_transaction'])) {
			$data['cardconnect_transaction'] = $this->request->post['cardconnect_transaction'];
		} else {
			$data['cardconnect_transaction'] = $this->config->get('cardconnect_transaction');
		}

		if (isset($this->request->post['cardconnect_site'])) {
			$data['cardconnect_site'] = $this->request->post['cardconnect_site'];
		} elseif ($this->config->has('cardconnect_site')) {
			$data['cardconnect_site'] = $this->config->get('cardconnect_site');
		} else {
			$data['cardconnect_site'] = 'fts';
		}

		if (isset($this->request->post['cardconnect_environment'])) {
			$data['cardconnect_environment'] = $this->request->post['cardconnect_environment'];
		} else {
			$data['cardconnect_environment'] = $this->config->get('cardconnect_environment');
		}

		if (isset($this->request->post['cardconnect_store_cards'])) {
			$data['cardconnect_store_cards'] = $this->request->post['cardconnect_store_cards'];
		} else {
			$data['cardconnect_store_cards'] = $this->config->get('cardconnect_store_cards');
		}

		if (isset($this->request->post['cardconnect_echeck'])) {
			$data['cardconnect_echeck'] = $this->request->post['cardconnect_echeck'];
		} else {
			$data['cardconnect_echeck'] = $this->config->get('cardconnect_echeck');
		}

		if (isset($this->request->post['cardconnect_total'])) {
			$data['cardconnect_total'] = $this->request->post['cardconnect_total'];
		} else {
			$data['cardconnect_total'] = $this->config->get('cardconnect_total');
		}

		if (isset($this->request->post['cardconnect_geo_zone'])) {
			$data['cardconnect_geo_zone'] = $this->request->post['cardconnect_geo_zone'];
		} else {
			$data['cardconnect_geo_zone'] = $this->config->get('cardconnect_geo_zone');
		}

		if (isset($this->request->post['cardconnect_status'])) {
			$data['cardconnect_status'] = $this->request->post['cardconnect_status'];
		} else {
			$data['cardconnect_status'] = $this->config->get('cardconnect_status');
		}

		if (isset($this->request->post['cardconnect_logging'])) {
			$data['cardconnect_logging'] = $this->request->post['cardconnect_logging'];
		} else {
			$data['cardconnect_logging'] = $this->config->get('cardconnect_logging');
		}

		if (isset($this->request->post['cardconnect_sort_order'])) {
			$data['cardconnect_sort_order'] = $this->request->post['cardconnect_sort_order'];
		} else {
			$data['cardconnect_sort_order'] = $this->config->get('cardconnect_sort_order');
		}

		$data['cardconnect_cron_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/cardconnect/cron&token=' . $data['cardconnect_token'];

		if ($this->config->get('cardconnect_cron_time')) {
			$data['cardconnect_cron_time'] = date($this->language->get('datetime_format'), strtotime($this->config->get('cardconnect_cron_time')));
		} else {
			$data['cardconnect_cron_time'] = $this->language->get('text_no_cron_time');
		}

		if (isset($this->request->post['cardconnect_order_status_id_pending'])) {
			$data['cardconnect_order_status_id_pending'] = $this->request->post['cardconnect_order_status_id_pending'];
		} elseif ($this->config->has('cardconnect_order_status_id_pending')) {
			$data['cardconnect_order_status_id_pending'] = $this->config->get('cardconnect_order_status_id_pending');
		} else {
			$data['cardconnect_order_status_id_pending'] = '1';
		}

		if (isset($this->request->post['cardconnect_order_status_id_processing'])) {
			$data['cardconnect_order_status_id_processing'] = $this->request->post['cardconnect_order_status_id_processing'];
		} elseif ($this->config->has('cardconnect_order_status_id_processing')) {
			$data['cardconnect_order_status_id_processing'] = $this->config->get('cardconnect_order_status_id_processing');
		} else {
			$data['cardconnect_order_status_id_processing'] = '2';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['cardconnect_merchant_id'])) {
			$data['error_cardconnect_merchant_id'] = $this->error['cardconnect_merchant_id'];
		} else {
			$data['error_cardconnect_merchant_id'] = '';
		}

		if (isset($this->error['cardconnect_api_username'])) {
			$data['error_cardconnect_api_username'] = $this->error['cardconnect_api_username'];
		} else {
			$data['error_cardconnect_api_username'] = '';
		}

		if (isset($this->error['cardconnect_api_password'])) {
			$data['error_cardconnect_api_password'] = $this->error['cardconnect_api_password'];
		} else {
			$data['error_cardconnect_api_password'] = '';
		}

		if (isset($this->error['cardconnect_token'])) {
			$data['error_cardconnect_token'] = $this->error['cardconnect_token'];
		} else {
			$data['error_cardconnect_token'] = '';
		}

		if (isset($this->error['cardconnect_site'])) {
			$data['error_cardconnect_site'] = $this->error['cardconnect_site'];
		} else {
			$data['error_cardconnect_site'] = '';
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/cardconnect', $data));
	}

	public function install() {
		if ($this->user->hasPermission('modify', 'extension/extension')) {
			$this->load->model('extension/payment/cardconnect');

			$this->model_extension_payment_cardconnect->install();
		}
	}

	public function uninstall() {
		if ($this->user->hasPermission('modify', 'extension/extension')) {
			$this->load->model('extension/payment/cardconnect');

			$this->model_extension_payment_cardconnect->uninstall();
		}
	}

	public function order() {
		if ($this->config->get('cardconnect_status')) {
			$this->load->model('extension/payment/cardconnect');

			$order_id = $this->request->get['order_id'];

			$cardconnect_order = $this->model_extension_payment_cardconnect->getOrder($this->request->get['order_id']);

			if ($cardconnect_order) {
				$this->load->language('extension/payment/cardconnect');

				if ($cardconnect_order['payment_method'] == 'card') {
					$cardconnect_order['payment_method'] = $this->language->get('text_card');
				} else {
					$cardconnect_order['payment_method'] = $this->language->get('text_echeck');
				}

				$cardconnect_order['total_formatted'] = $this->currency->format($cardconnect_order['total'], $cardconnect_order['currency_code'], false, true);

				$cardconnect_order['total_captured'] = $this->model_extension_payment_cardconnect->getTotalCaptured($cardconnect_order['cardconnect_order_id']);

				$cardconnect_order['total_captured_formatted'] = $this->currency->format($cardconnect_order['total_captured'], $cardconnect_order['currency_code'], false, true);

				foreach($cardconnect_order['transactions'] as &$transaction) {
					switch ($transaction['type']) {
						case 'payment':
							$transaction['type'] = 'Payment';
							break;
						case 'auth':
							$transaction['type'] = 'Authorize';
							break;
						case 'refund':
							$transaction['type'] = 'Refund';
							break;
						case 'void':
							$transaction['type'] = 'Void';
							break;
						default:
							$transaction['type'] = 'Payment';
					}

					$transaction['amount'] = $this->currency->format($transaction['amount'], $cardconnect_order['currency_code'], false, true);

					if ($transaction['status'] == 'Y') {
						$transaction['status'] = 'Accepted';
					} else if ($transaction['status'] == 'N') {
						$transaction['status'] = 'Rejected';
					}

					$transaction['date_modified'] = date($this->language->get('datetime_format'), strtotime($transaction['date_modified']));

					$transaction['date_added'] = date($this->language->get('datetime_format'), strtotime($transaction['date_added']));
				}

				$data['cardconnect_order'] = $cardconnect_order;

				$data['text_yes']                  = $this->language->get('text_yes');
				$data['text_no']                   = $this->language->get('text_no');
				$data['text_payment_info']         = $this->language->get('text_payment_info');
				$data['text_payment_method']       = $this->language->get('text_payment_method');
				$data['text_reference']            = $this->language->get('text_reference');
				$data['text_update']               = $this->language->get('text_update');
				$data['text_order_total']          = $this->language->get('text_order_total');
				$data['text_total_captured']       = $this->language->get('text_total_captured');
				$data['text_capture_payment']      = $this->language->get('text_capture_payment');
				$data['text_refund_payment']       = $this->language->get('text_refund_payment');
				$data['text_void']                 = $this->language->get('text_void');
				$data['text_transactions']         = $this->language->get('text_transactions');
				$data['text_column_type']          = $this->language->get('text_column_type');
				$data['text_column_reference']     = $this->language->get('text_column_reference');
				$data['text_column_amount']        = $this->language->get('text_column_amount');
				$data['text_column_status']        = $this->language->get('text_column_status');
				$data['text_column_date_modified'] = $this->language->get('text_column_date_modified');
				$data['text_column_date_added']    = $this->language->get('text_column_date_added');
				$data['text_column_update']        = $this->language->get('text_column_update');
				$data['text_column_void']          = $this->language->get('text_column_void');
				$data['text_confirm_capture']      = $this->language->get('text_confirm_capture');
				$data['text_confirm_refund']       = $this->language->get('text_confirm_refund');

				$data['button_inquire_all']        = $this->language->get('button_inquire_all');
				$data['button_capture']            = $this->language->get('button_capture');
				$data['button_refund']             = $this->language->get('button_refund');
				$data['button_void_all']           = $this->language->get('button_void_all');
				$data['button_inquire']            = $this->language->get('button_inquire');
				$data['button_void']               = $this->language->get('button_void');

				$data['order_id'] = $this->request->get['order_id'];

				$data['token'] = $this->request->get['token'];

				return $this->load->view('extension/payment/cardconnect_order', $data);
			}
		}
	}

	public function inquire() {
		$this->load->language('extension/payment/cardconnect');

		$json = array();

		if ($this->config->get('cardconnect_status')) {
			if (isset($this->request->post['order_id']) && isset($this->request->post['retref'])) {
				$this->load->model('extension/payment/cardconnect');

				$cardconnect_order = $this->model_extension_payment_cardconnect->getOrder($this->request->post['order_id']);

				if ($cardconnect_order) {
					$inquire_response = $this->model_extension_payment_cardconnect->inquire($cardconnect_order, $this->request->post['retref']);

					if (isset($inquire_response['respstat']) && $inquire_response['respstat'] == 'C') {
						$json['error'] = $inquire_response['resptext'];
					} else {
						$this->model_extension_payment_cardconnect->updateTransactionStatusByRetref($this->request->post['retref'], $inquire_response['setlstat']);

						$json['status'] = $inquire_response['setlstat'];

						$json['date_modified'] = date($this->language->get('datetime_format'));

						$json['success'] = $this->language->get('text_inquire_success');
					}
				} else {
					$json['error'] = $this->language->get('error_no_order');
				}
			} else {
				$json['error'] = $this->language->get('error_data_missing');
			}
		} else {
			$json['error'] = $this->language->get('error_not_enabled');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function capture() {
		$this->load->language('extension/payment/cardconnect');

		$json = array();

		if ($this->config->get('cardconnect_status')) {
			if (isset($this->request->post['order_id']) && isset($this->request->post['amount'])) {
				if ($this->request->post['amount'] > 0) {
					$this->load->model('extension/payment/cardconnect');

					$cardconnect_order = $this->model_extension_payment_cardconnect->getOrder($this->request->post['order_id']);

					if ($cardconnect_order) {
						$capture_response = $this->model_extension_payment_cardconnect->capture($cardconnect_order, $this->request->post['amount']);

						if (!isset($capture_response['retref'])) {
							$json['error'] = $this->language->get('error_invalid_response');
						} else if (isset($capture_response['respstat']) && $capture_response['respstat'] == 'C') {
							$json['error'] = $capture_response['resptext'];
						} else {
							$this->model_extension_payment_cardconnect->addTransaction($cardconnect_order['cardconnect_order_id'], 'payment', $capture_response['retref'], $this->request->post['amount'], $capture_response['setlstat']);

							$total_captured = $this->model_extension_payment_cardconnect->getTotalCaptured($cardconnect_order['cardconnect_order_id']);

							$json['retref'] = $capture_response['retref'];
							$json['amount'] = $this->currency->format($this->request->post['amount'], $cardconnect_order['currency_code'], false, true);
							$json['status'] = $capture_response['setlstat'];
							$json['date_modified'] = date($this->language->get('datetime_format'));
							$json['date_added'] = date($this->language->get('datetime_format'));
							$json['total_captured'] = $this->currency->format($total_captured, $cardconnect_order['currency_code'], false, true);

							$json['success'] = $this->language->get('text_capture_success');
						}
					} else {
						$json['error'] = $this->language->get('error_no_order');
					}
				} else {
					$json['error'] = $this->language->get('error_amount_zero');
				}
			} else {
				$json['error'] = $this->language->get('error_data_missing');
			}
		} else {
			$json['error'] = $this->language->get('error_not_enabled');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function refund() {
		$this->load->language('extension/payment/cardconnect');

		$json = array();

		if ($this->config->get('cardconnect_status')) {
			if (isset($this->request->post['order_id']) && isset($this->request->post['amount'])) {
				if ($this->request->post['amount'] > 0) {
					$this->load->model('extension/payment/cardconnect');

					$cardconnect_order = $this->model_extension_payment_cardconnect->getOrder($this->request->post['order_id']);

					if ($cardconnect_order) {
						$refund_response = $this->model_extension_payment_cardconnect->refund($cardconnect_order, $this->request->post['amount']);

						if (!isset($refund_response['retref'])) {
							$json['error'] = $this->language->get('error_invalid_response');
						} else if (isset($refund_response['respstat']) && $refund_response['respstat'] == 'C') {
							$json['error'] = $refund_response['resptext'];
						} else {
							$this->model_extension_payment_cardconnect->addTransaction($cardconnect_order['cardconnect_order_id'], 'refund', $refund_response['retref'], $this->request->post['amount'] * -1, $refund_response['resptext']);

							$total_captured = $this->model_extension_payment_cardconnect->getTotalCaptured($cardconnect_order['cardconnect_order_id']);

							$json['retref'] = $refund_response['retref'];
							$json['amount'] = $this->currency->format($this->request->post['amount'] * -1, $cardconnect_order['currency_code'], false, true);
							$json['status'] = $refund_response['resptext'];
							$json['date_modified'] = date($this->language->get('datetime_format'));
							$json['date_added'] = date($this->language->get('datetime_format'));
							$json['total_captured'] = $this->currency->format($total_captured, $cardconnect_order['currency_code'], false, true);

							$json['success'] = $this->language->get('text_refund_success');
						}
					} else {
						$json['error'] = $this->language->get('error_no_order');
					}
				} else {
					$json['error'] = $this->language->get('error_amount_zero');
				}
			} else {
				$json['error'] = $this->language->get('error_data_missing');
			}
		} else {
			$json['error'] = $this->language->get('error_not_enabled');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function void() {
		$this->load->language('extension/payment/cardconnect');

		$json = array();

		if ($this->config->get('cardconnect_status')) {
			if (isset($this->request->post['order_id']) && isset($this->request->post['retref'])) {
				$this->load->model('extension/payment/cardconnect');

				$cardconnect_order = $this->model_extension_payment_cardconnect->getOrder($this->request->post['order_id']);

				if ($cardconnect_order) {
					$void_response = $this->model_extension_payment_cardconnect->void($cardconnect_order, $this->request->post['retref']);

					if (!isset($void_response['authcode']) || $void_response['authcode'] != 'REVERS') {
						$json['error'] = $void_response['resptext'];
					} else {
						$json['retref'] = $void_response['retref'];
						$json['amount'] = $this->currency->format(0.00, $cardconnect_order['currency_code'], false, true);
						$json['status'] = $void_response['resptext'];
						$json['date_modified'] = date($this->language->get('datetime_format'));
						$json['date_added'] = date($this->language->get('datetime_format'));
						$json['success'] = $this->language->get('text_void_success');
					}
				} else {
					$json['error'] = $this->language->get('error_no_order');
				}
			} else {
				$json['error'] = $this->language->get('error_data_missing');
			}
		} else {
			$json['error'] = $this->language->get('error_not_enabled');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/cardconnect')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['cardconnect_merchant_id']) {
			$this->error['cardconnect_merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['cardconnect_api_username']) {
			$this->error['cardconnect_api_username'] = $this->language->get('error_api_username');
		}

		if (!$this->request->post['cardconnect_api_password']) {
			$this->error['cardconnect_api_password'] = $this->language->get('error_api_password');
		}

		if (!$this->request->post['cardconnect_token']) {
			$this->error['cardconnect_token'] = $this->language->get('error_token');
		}

		if (!$this->request->post['cardconnect_site']) {
			$this->error['cardconnect_site'] = $this->language->get('error_site');
		}

		return !$this->error;
	}
}