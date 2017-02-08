<?php
class ControllerExtensionPaymentBraintree extends Controller {
	private $error = array();
	private $gateway = null;
	private $opencart_connect_url = 'https://staging.opencart.com/index.php?route=external/braintree_auth/connect';
	private $opencart_retrieve_url = 'https://staging.opencart.com/index.php?route=external/braintree_auth/retrieve';
//	private $opencart_connect_url = 'https://www.opencart.com/index.php?route=external/braintree_auth/connect';
//	private $opencart_retrieve_url = 'https://www.opencart.com/index.php?route=external/braintree_auth/retrieve';

	public function index() {
		$this->load->language('extension/payment/braintree');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			foreach ($this->request->post['braintree_account'] as $currency => $account) {
				if (!isset($account['status'])) {
					$this->request->post['braintree_account'][$currency]['status'] = 0;
				}
			}

			$this->model_setting_setting->editSetting('braintree', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_production'] = $this->language->get('text_production');
		$data['text_sandbox'] = $this->language->get('text_sandbox');
		$data['text_currency'] = $this->language->get('text_currency');
		$data['text_immediate'] = $this->language->get('text_immediate');
		$data['text_deferred'] = $this->language->get('text_deferred');
		$data['text_hosted'] = $this->language->get('text_hosted');
		$data['text_dropin'] = $this->language->get('text_dropin');
		$data['text_merchant_account_id'] = $this->language->get('text_merchant_account_id');
		$data['text_na'] = $this->language->get('text_na');
		$data['text_all'] = $this->language->get('text_all');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_credit'] = $this->language->get('text_credit');
		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_paypal'] = $this->language->get('text_paypal');
		$data['text_enable_transactions'] = $this->language->get('text_enable_transactions');
		$data['text_app_connected'] = $this->language->get('text_app_connected');
		$data['text_paypal_button_config'] = $this->language->get('text_paypal_button_config');
		$data['text_paypal_gold'] = $this->language->get('text_paypal_gold');
		$data['text_paypal_blue'] = $this->language->get('text_paypal_blue');
		$data['text_paypal_silver'] = $this->language->get('text_paypal_silver');
		$data['text_paypal_tiny'] = $this->language->get('text_paypal_tiny');
		$data['text_paypal_small'] = $this->language->get('text_paypal_small');
		$data['text_paypal_medium'] = $this->language->get('text_paypal_medium');
		$data['text_paypal_pill'] = $this->language->get('text_paypal_pill');
		$data['text_paypal_rectangular'] = $this->language->get('text_paypal_rectangular');
		$data['text_paypal_preview'] = $this->language->get('text_paypal_preview');
		$data['text_braintree_learn'] = $this->language->get('text_braintree_learn');
		$data['text_3ds'] = $this->language->get('text_3ds');
		$data['text_cvv'] = $this->language->get('text_cvv');

		$data['column_transaction_id'] = $this->language->get('column_transaction_id');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_order'] = $this->language->get('column_order');
		$data['column_date_added'] = $this->language->get('column_date_added');

		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['entry_public_key'] = $this->language->get('entry_public_key');
		$data['entry_private_key'] = $this->language->get('entry_private_key');
		$data['entry_environment'] = $this->language->get('entry_environment');
		$data['entry_settlement_type'] = $this->language->get('entry_settlement_type');
		$data['entry_integration_type'] = $this->language->get('entry_integration_type');
		$data['entry_vault'] = $this->language->get('entry_vault');
		$data['entry_vault_cvv_3ds'] = $this->language->get('entry_vault_cvv_3ds');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_authorization_expired'] = $this->language->get('entry_authorization_expired');
		$data['entry_authorized'] = $this->language->get('entry_authorized');
		$data['entry_authorizing'] = $this->language->get('entry_authorizing');
		$data['entry_settlement_pending'] = $this->language->get('entry_settlement_pending');
		$data['entry_failed'] = $this->language->get('entry_failed');
		$data['entry_gateway_rejected'] = $this->language->get('entry_gateway_rejected');
		$data['entry_processor_declined'] = $this->language->get('entry_processor_declined');
		$data['entry_settled'] = $this->language->get('entry_settled');
		$data['entry_settling'] = $this->language->get('entry_settling');
		$data['entry_submitted_for_settlement'] = $this->language->get('entry_submitted_for_settlement');
		$data['entry_voided'] = $this->language->get('entry_voided');
		$data['entry_3ds_status'] = $this->language->get('entry_3ds_status');
		$data['entry_3ds_full_liability_shift'] = $this->language->get('entry_3ds_full_liability_shift');
		$data['entry_transaction_id'] = $this->language->get('entry_transaction_id');
		$data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
		$data['entry_date_from'] = $this->language->get('entry_date_from');
		$data['entry_date_to'] = $this->language->get('entry_date_to');
		$data['entry_payment_type'] = $this->language->get('entry_payment_type');
		$data['entry_card_type'] = $this->language->get('entry_card_type');
		$data['entry_amount_from'] = $this->language->get('entry_amount_from');
		$data['entry_amount_to'] = $this->language->get('entry_amount_to');
		$data['entry_transaction_status'] = $this->language->get('entry_transaction_status');
		$data['entry_merchant_account_id'] = $this->language->get('entry_merchant_account_id');
		$data['entry_connection'] = $this->language->get('entry_connection');
		$data['entry_paypal_option'] = $this->language->get('entry_paypal_option');
		$data['entry_paypal_button_colour'] = $this->language->get('entry_paypal_button_colour');
		$data['entry_paypal_button_shape'] = $this->language->get('entry_paypal_button_shape');
		$data['entry_paypal_button_size'] = $this->language->get('entry_paypal_button_size');
		$data['entry_paypal_billing_agreement'] = $this->language->get('entry_paypal_billing_agreement');

		$data['help_settlement_type'] = $this->language->get('help_settlement_type');
		$data['help_vault'] = $this->language->get('help_vault');
		$data['help_vault_cvv_3ds'] = $this->language->get('help_vault_cvv_3ds');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_total'] = $this->language->get('help_total');
		$data['help_paypal_option'] = $this->language->get('help_paypal_option');
		$data['help_paypal_billing_agreement'] = $this->language->get('help_paypal_billing_agreement');
		$data['help_3ds_full_liability_shift'] = $this->language->get('help_3ds_full_liability_shift');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['tab_setting'] = $this->language->get('tab_setting');
		$data['tab_currency'] = $this->language->get('tab_currency');
		$data['tab_order_status'] = $this->language->get('tab_order_status');
		$data['tab_3ds'] = $this->language->get('tab_3ds');
		$data['tab_transaction'] = $this->language->get('tab_transaction');
		$data['tab_vault'] = $this->language->get('tab_vault');
		$data['tab_paypal'] = $this->language->get('tab_paypal');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['account'])) {
			$data['error_account'] = $this->error['account'];
		} else {
			$data['error_account'] = array();
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/braintree', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/braintree', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['braintree_merchant_id'])) {
			$data['braintree_merchant_id'] = $this->request->post['braintree_merchant_id'];
		} else {
			$data['braintree_merchant_id'] = $this->config->get('braintree_merchant_id');
		}

		if (isset($this->request->post['braintree_public_key'])) {
			$data['braintree_public_key'] = $this->request->post['braintree_public_key'];
		} else {
			$data['braintree_public_key'] = $this->config->get('braintree_public_key');
		}

		if (isset($this->request->post['braintree_private_key'])) {
			$data['braintree_private_key'] = $this->request->post['braintree_private_key'];
		} else {
			$data['braintree_private_key'] = $this->config->get('braintree_private_key');
		}

		if (isset($this->request->post['braintree_access_token'])) {
			$data['braintree_access_token'] = $this->request->post['braintree_access_token'];
		} else {
			$data['braintree_access_token'] = $this->config->get('braintree_access_token');
		}

		if (isset($this->request->post['braintree_refresh_token'])) {
			$data['braintree_refresh_token'] = $this->request->post['braintree_refresh_token'];
		} else {
			$data['braintree_refresh_token'] = $this->config->get('braintree_refresh_token');
		}

		if (isset($this->request->post['braintree_environment'])) {
			$data['braintree_environment'] = $this->request->post['braintree_environment'];
		} else {
			$data['braintree_environment'] = $this->config->get('braintree_environment');
		}

		if (isset($this->request->post['braintree_settlement_immediate'])) {
			$data['braintree_settlement_immediate'] = $this->request->post['braintree_settlement_immediate'];
		} else {
			$data['braintree_settlement_immediate'] = $this->config->get('braintree_settlement_immediate');
		}

		if (isset($this->request->post['braintree_vault'])) {
			$data['braintree_vault'] = $this->request->post['braintree_vault'];
		} else {
			$data['braintree_vault'] = $this->config->get('braintree_vault');
		}

		if (isset($this->request->post['braintree_vault_cvv_3ds'])) {
			$data['braintree_vault_cvv_3ds'] = $this->request->post['braintree_vault_cvv_3ds'];
		} else {
			$data['braintree_vault_cvv_3ds'] = $this->config->get('braintree_vault_cvv_3ds');
		}

		if (isset($this->request->post['braintree_debug'])) {
			$data['braintree_debug'] = $this->request->post['braintree_debug'];
		} else {
			$data['braintree_debug'] = $this->config->get('braintree_debug');
		}

		if (isset($this->request->post['braintree_total'])) {
			$data['braintree_total'] = $this->request->post['braintree_total'];
		} else {
			$data['braintree_total'] = $this->config->get('braintree_total');
		}

		if (isset($this->request->post['braintree_geo_zone_id'])) {
			$data['braintree_geo_zone_id'] = $this->request->post['braintree_geo_zone_id'];
		} else {
			$data['braintree_geo_zone_id'] = $this->config->get('braintree_geo_zone_id');
		}

		if (isset($this->request->post['braintree_status'])) {
			$data['braintree_status'] = $this->request->post['braintree_status'];
		} else {
			$data['braintree_status'] = $this->config->get('braintree_status');
		}

		if (isset($this->request->post['braintree_sort_order'])) {
			$data['braintree_sort_order'] = $this->request->post['braintree_sort_order'];
		} else {
			$data['braintree_sort_order'] = $this->config->get('braintree_sort_order');
		}

		if (isset($this->request->post['braintree_account'])) {
			$data['braintree_account'] = $this->request->post['braintree_account'];
		} else {
			$data['braintree_account'] = $this->config->get('braintree_account');
		}

		if (isset($this->request->post['braintree_authorization_expired_id'])) {
			$data['braintree_authorization_expired_id'] = $this->request->post['braintree_authorization_expired_id'];
		} else {
			$data['braintree_authorization_expired_id'] = $this->config->get('braintree_authorization_expired_id');
		}

		if (isset($this->request->post['braintree_authorized_id'])) {
			$data['braintree_authorized_id'] = $this->request->post['braintree_authorized_id'];
		} else {
			$data['braintree_authorized_id'] = $this->config->get('braintree_authorized_id');
		}

		if (isset($this->request->post['braintree_authorizing_id'])) {
			$data['braintree_authorizing_id'] = $this->request->post['braintree_authorizing_id'];
		} else {
			$data['braintree_authorizing_id'] = $this->config->get('braintree_authorizing_id');
		}

		if (isset($this->request->post['braintree_settlement_pending_id'])) {
			$data['braintree_settlement_pending_id'] = $this->request->post['braintree_settlement_pending_id'];
		} else {
			$data['braintree_settlement_pending_id'] = $this->config->get('braintree_settlement_pending_id');
		}

		if (isset($this->request->post['braintree_failed_id'])) {
			$data['braintree_failed_id'] = $this->request->post['braintree_failed_id'];
		} else {
			$data['braintree_failed_id'] = $this->config->get('braintree_failed_id');
		}

		if (isset($this->request->post['braintree_gateway_rejected_id'])) {
			$data['braintree_gateway_rejected_id'] = $this->request->post['braintree_gateway_rejected_id'];
		} else {
			$data['braintree_gateway_rejected_id'] = $this->config->get('braintree_gateway_rejected_id');
		}

		if (isset($this->request->post['braintree_processor_declined_id'])) {
			$data['braintree_processor_declined_id'] = $this->request->post['braintree_processor_declined_id'];
		} else {
			$data['braintree_processor_declined_id'] = $this->config->get('braintree_processor_declined_id');
		}

		if (isset($this->request->post['braintree_settled_id'])) {
			$data['braintree_settled_id'] = $this->request->post['braintree_settled_id'];
		} else {
			$data['braintree_settled_id'] = $this->config->get('braintree_settled_id');
		}

		if (isset($this->request->post['braintree_settling_id'])) {
			$data['braintree_settling_id'] = $this->request->post['braintree_settling_id'];
		} else {
			$data['braintree_settling_id'] = $this->config->get('braintree_settling_id');
		}

		if (isset($this->request->post['braintree_submitted_for_settlement_id'])) {
			$data['braintree_submitted_for_settlement_id'] = $this->request->post['braintree_submitted_for_settlement_id'];
		} else {
			$data['braintree_submitted_for_settlement_id'] = $this->config->get('braintree_submitted_for_settlement_id');
		}

		if (isset($this->request->post['braintree_voided_id'])) {
			$data['braintree_voided_id'] = $this->request->post['braintree_voided_id'];
		} else {
			$data['braintree_voided_id'] = $this->config->get('braintree_voided_id');
		}

		if (isset($this->request->post['braintree_3ds_status'])) {
			$data['braintree_3ds_status'] = $this->request->post['braintree_3ds_status'];
		} else {
			$data['braintree_3ds_status'] = $this->config->get('braintree_3ds_status');
		}

		if (isset($this->request->post['braintree_3ds_full_liability_shift'])) {
			$data['braintree_3ds_full_liability_shift'] = $this->request->post['braintree_3ds_full_liability_shift'];
		} else {
			$data['braintree_3ds_full_liability_shift'] = $this->config->get('braintree_3ds_full_liability_shift');
		}

		if (isset($this->request->post['braintree_paypal_option'])) {
			$data['braintree_paypal_option'] = $this->request->post['braintree_paypal_option'];
		} else {
			$data['braintree_paypal_option'] = $this->config->get('braintree_paypal_option');
		}

		if (isset($this->request->post['braintree_paypal_button_colour'])) {
			$data['braintree_paypal_button_colour'] = $this->request->post['braintree_paypal_button_colour'];
		} else {
			$data['braintree_paypal_button_colour'] = $this->config->get('braintree_paypal_button_colour');
		}

		if (isset($this->request->post['braintree_paypal_button_size'])) {
			$data['braintree_paypal_button_size'] = $this->request->post['braintree_paypal_button_size'];
		} else {
			$data['braintree_paypal_button_size'] = $this->config->get('braintree_paypal_button_size');
		}

		if (isset($this->request->post['braintree_paypal_button_shape'])) {
			$data['braintree_paypal_button_shape'] = $this->request->post['braintree_paypal_button_shape'];
		} else {
			$data['braintree_paypal_button_shape'] = $this->config->get('braintree_paypal_button_shape');
		}

		if (isset($this->request->post['braintree_billing_agreement'])) {
			$data['braintree_billing_agreement'] = $this->request->post['braintree_billing_agreement'];
		} else {
			$data['braintree_billing_agreement'] = $this->config->get('braintree_billing_agreement');
		}

		$data['transaction_statuses'] = array(
			'authorization_expired',
			'authorized',
			'authorizing',
			'settlement_pending',
			'failed',
			'gateway_rejected',
			'processor_declined',
			'settled',
			'settling',
			'submitted_for_settlement',
			'voided'
		);

		$data['card_types'] = array(
			'Visa',
			'MasterCard',
			'American Express',
			'Discover',
			'JCB',
			'Maestro'
		);

		if (isset($this->request->get['retrieve_code'])) {
			$data['retrieve_code'] = $this->request->get['retrieve_code'];

			$curl = curl_init($this->opencart_retrieve_url);

			$post_data = array(
				'return_url' => $this->url->link('extension/payment/braintree', 'token=' . $this->session->data['token'], true),
				'retrieve_code' => $this->request->get['retrieve_code'],
				'store_version' => VERSION,
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

			if (isset($config_response['merchant_id']) && isset($config_response['access_token']) && isset($config_response['refresh_token'])) {
				$braintree_settings = $this->model_setting_setting->getSetting('braintree');
				$braintree_settings['braintree_merchant_id'] = $config_response['merchant_id'];
				$braintree_settings['braintree_access_token'] = $config_response['access_token'];
				$braintree_settings['braintree_refresh_token'] = $config_response['refresh_token'];
				$braintree_settings['braintree_public_key'] = '';
				$braintree_settings['braintree_private_key'] = '';

				$this->model_setting_setting->editSetting('braintree', $braintree_settings);

				$data['braintree_merchant_id'] = $config_response['merchant_id'];
				$data['braintree_access_token'] = $config_response['access_token'];
				$data['braintree_refresh_token'] = $config_response['refresh_token'];
				$data['braintree_public_key'] = '';
				$data['braintree_private_key'] = '';

				$data['success'] = $this->language->get('text_success_connect');
			}
		}

		$data['auth_connect_url'] = '';

		// If Braintree is not setup yet, request auth token for merchant on-boarding flow
		if ($data['braintree_merchant_id'] == '') {
			$curl = curl_init($this->opencart_connect_url);

			$this->load->model('localisation/country');
			$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

			$post_data = array(
				'return_url' => $this->url->link('extension/payment/braintree', 'token=' . $this->session->data['token'], true),
				'store_url' => HTTPS_CATALOG,
				'store_version' => VERSION,
				'store_country' => (isset($country['iso_code_3']) ? $country['iso_code_3'] : ''),
			);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

			$curl_response = curl_exec($curl);

			$curl_response = json_decode($curl_response, true);

			curl_close($curl);

			if ($curl_response['url']) {
				$data['auth_connect_url'] = $curl_response['url'];
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/braintree', $data));
	}

	public function order() {
		$this->load->language('extension/payment/braintree');

		$data['text_payment_info'] = $this->language->get('text_payment_info');
		$data['token'] = $this->session->data['token'];
		$data['order_id'] = $this->request->get['order_id'];

		return $this->load->view('extension/payment/braintree_order', $data);
	}

	public function getTransaction() {
		$this->load->language('extension/payment/braintree');

		$this->load->model('extension/payment/braintree');
		$this->load->model('sale/order');

		if (!$this->config->get('braintree_status') || (!isset($this->request->get['order_id']) && !isset($this->request->get['transaction_id']))) {
			return;
		}

		$this->initialise($this->config->get('braintree_access_token'), array(
			'braintree_environment' => $this->config->get('braintree_environment'),
			'braintree_merchant_id' => $this->config->get('braintree_merchant_id'),
			'braintree_public_key'	=> $this->config->get('braintree_public_key'),
			'braintree_private_key' => $this->config->get('braintree_private_key')
		));

		if (isset($this->request->get['order_id'])) {
			$search = array(
				Braintree_TransactionSearch::orderId()->is($this->request->get['order_id'])
			);
		} elseif (isset($this->request->get['transaction_id'])) {
			$search = array(
				Braintree_TransactionSearch::id()->is($this->request->get['transaction_id'])
			);
		}

		$search_transactions = $this->model_extension_payment_braintree->getTransactions($this->gateway, $search);

		$transaction = array();

		foreach ($search_transactions as $search_transaction) {
			$transaction = $search_transaction;
		}

		$data['transaction'] = array();

		if ($transaction) {
			$data['text_na'] = $this->language->get('text_na');
			$data['text_no_refund'] = $this->language->get('text_no_refund');
			$data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');

			$data['column_status'] = $this->language->get('column_status');
			$data['column_transaction_id'] = $this->language->get('column_transaction_id');
			$data['column_transaction_type'] = $this->language->get('column_transaction_type');
			$data['column_transaction_date'] = $this->language->get('column_transaction_date');
			$data['column_merchant_account'] = $this->language->get('column_merchant_account');
			$data['column_payment_type'] = $this->language->get('column_payment_type');
			$data['column_amount'] = $this->language->get('column_amount');
			$data['column_order_id'] = $this->language->get('column_order_id');
			$data['column_processor_code'] = $this->language->get('column_processor_code');
			$data['column_cvv_response'] = $this->language->get('column_cvv_response');
			$data['column_avs_response'] = $this->language->get('column_avs_response');
			$data['column_3ds_enrolled'] = $this->language->get('column_3ds_enrolled');
			$data['column_3ds_status'] = $this->language->get('column_3ds_status');
			$data['column_3ds_shifted'] = $this->language->get('column_3ds_shifted');
			$data['column_3ds_shift_possible'] = $this->language->get('column_3ds_shift_possible');
			$data['column_transaction_history'] = $this->language->get('column_transaction_history');
			$data['column_date'] = $this->language->get('column_date');
			$data['column_refund_history'] = $this->language->get('column_refund_history');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_amount'] = $this->language->get('column_amount');
			$data['column_void'] = $this->language->get('column_void');
			$data['column_settle'] = $this->language->get('column_settle');
			$data['column_refund'] = $this->language->get('column_refund');

			$data['button_void'] = $this->language->get('button_void');
			$data['button_settle'] = $this->language->get('button_settle');
			$data['button_refund'] = $this->language->get('button_refund');

			$data['transaction_id'] = $transaction->id;
			$data['token'] = $this->session->data['token'];

			$data['void_action'] = $data['settle_action'] = $data['refund_action'] = false;

			switch ($transaction->status) {
				case 'authorized':
					$data['void_action'] = true;
					$data['settle_action'] = true;
					break;
				case 'submitted_for_settlement':
					$data['void_action'] = true;
					break;
				case 'settling':
					$data['refund_action'] = true;
					break;
				case 'settled':
					$data['refund_action'] = true;
					break;
			}

			$statuses = array();

			foreach ($transaction->statusHistory as $status_history) {
				$created_at = $status_history->timestamp;

				$statuses[] = array(
					'status'     => $status_history->status,
					'date_added' => date($this->language->get('datetime_format'), strtotime($created_at->format('Y-m-d H:i:s e')))
				);
			}

			$data['statuses'] = $statuses;

			$max_settle_amount = $transaction->amount;

			$max_refund_amount = $transaction->amount;

			$data['refunds'] = array();

			foreach (array_reverse($transaction->refundIds) as $refund_id) {
				$refund = $this->model_extension_payment_braintree->getTransaction($this->gateway, $refund_id);

				$successful_statuses = array(
					'authorized',
					'authorizing',
					'settlement_pending',
					'settlement_confirmed',
					'settled',
					'settling',
					'submitted_for_settlement'
				);

				if (in_array($refund->status, $successful_statuses)) {
					$max_refund_amount -= $refund->amount;
				}

				$created_at = $refund->createdAt;

				$data['refunds'][] = array(
					'date_added' => date($this->language->get('datetime_format'), strtotime($created_at->format('Y-m-d H:i:s e'))),
					'amount'	 => $this->currency->format($refund->amount, $refund->currencyIsoCode, '1.00000000', true),
					'status'	 => $refund->status
				);
			}

			//If nothing left to refund, disable refund action
			if (!$max_refund_amount) {
				$data['refund_action'] = false;
			}

			$data['max_settle_amount'] = $this->currency->format($max_settle_amount, $transaction->currencyIsoCode, '1.00000000', false);

			$data['max_refund_amount'] = $this->currency->format($max_refund_amount, $transaction->currencyIsoCode, '1.00000000', false);

			$amount = $this->currency->format($transaction->amount, $transaction->currencyIsoCode, '1.00000000', true);

			$data['symbol_left'] = $this->currency->getSymbolLeft($transaction->currencyIsoCode);
			$data['symbol_right'] = $this->currency->getSymbolRight($transaction->currencyIsoCode);

			$created_at = $transaction->createdAt;

			if ($transaction->threeDSecureInfo) {
				if ($transaction->threeDSecureInfo->liabilityShifted) {
					$liability_shifted = $this->language->get('text_yes');
				} else {
					$liability_shifted = $this->language->get('text_no');
				}
			}

			if ($transaction->threeDSecureInfo) {
				if ($transaction->threeDSecureInfo->liabilityShiftPossible) {
					$liability_shift_possible = $this->language->get('text_yes');
				} else {
					$liability_shift_possible = $this->language->get('text_no');
				}
			}

			$data['transaction'] = array(
				'status'			  => $transaction->status,
				'transaction_id'	  => $transaction->id,
				'type'				  => $transaction->type,
				'date_added'		  => date($this->language->get('datetime_format'), strtotime($created_at->format('Y-m-d H:i:s e'))),
				'merchant_account_id' => $transaction->merchantAccountId,
				'payment_type'		  => $transaction->paymentInstrumentType,
				'currency'			  => $transaction->currencyIsoCode,
				'amount'			  => $amount,
				'order_id'			  => $transaction->orderId,
				'processor_code'	  => $transaction->processorAuthorizationCode,
				'cvv_response'		  => $transaction->cvvResponseCode,
				'avs_response'		  => sprintf($this->language->get('text_avs_response'), $transaction->avsStreetAddressResponseCode, $transaction->avsPostalCodeResponseCode),
				'threeds_enrolled'		  => ($transaction->threeDSecureInfo ? $transaction->threeDSecureInfo->enrolled : ''),
				'threeds_status'		  => ($transaction->threeDSecureInfo ? $transaction->threeDSecureInfo->status : ''),
				'threeds_shifted'		  => ($transaction->threeDSecureInfo ? $liability_shifted : ''),
				'threeds_shift_possible'  => ($transaction->threeDSecureInfo ? $liability_shift_possible : '')
			);

			$data['text_confirm_void'] = $this->language->get('text_confirm_void');
			$data['text_confirm_settle'] = $this->language->get('text_confirm_settle');
			$data['text_confirm_refund'] = $this->language->get('text_confirm_refund');

			$this->response->setOutput($this->load->view('extension/payment/braintree_order_ajax', $data));
		}
	}

	public function transactionCommand() {
		$this->load->language('extension/payment/braintree');

		$this->load->model('extension/payment/braintree');

		$this->initialise($this->config->get('braintree_access_token'), array(
			'braintree_environment' => $this->config->get('braintree_environment'),
			'braintree_merchant_id' => $this->config->get('braintree_merchant_id'),
			'braintree_public_key'	=> $this->config->get('braintree_public_key'),
			'braintree_private_key' => $this->config->get('braintree_private_key')
		));

		$json = array();

		$success = $error = '';

		if ($this->request->post['type'] == 'void') {
			$action = $this->model_extension_payment_braintree->voidTransaction($this->gateway, $this->request->post['transaction_id']);
		} elseif ($this->request->post['type'] == 'settle' && $this->request->post['amount']) {
			$action = $this->model_extension_payment_braintree->settleTransaction($this->gateway, $this->request->post['transaction_id'], $this->request->post['amount']);
		} elseif ($this->request->post['type'] == 'refund' && $this->request->post['amount']) {
			$action = $this->model_extension_payment_braintree->refundTransaction($this->gateway, $this->request->post['transaction_id'], $this->request->post['amount']);
		} else {
			$error = true;
		}

		if (!$error && $action && $action->success) {
			$success = $this->language->get('text_success_action');
		} elseif (!$error && $action && isset($action->message)) {
			$error = sprintf($this->language->get('text_error_settle'), $action->message);
		} else {
			$error = $this->language->get('text_error_generic');
		}

		$json['success'] = $success;
		$json['error'] = $error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function search() {
		$this->load->language('extension/payment/braintree');

		$this->load->model('extension/payment/braintree');
		$this->load->model('customer/customer');
		$this->load->model('sale/order');

		$this->initialise($this->config->get('braintree_access_token'), array(
			'braintree_environment' => $this->config->get('braintree_environment'),
			'braintree_merchant_id' => $this->config->get('braintree_merchant_id'),
			'braintree_public_key'	=> $this->config->get('braintree_public_key'),
			'braintree_private_key' => $this->config->get('braintree_private_key')
		));

		$json = array();

		$success = $error = '';

		if (isset($this->request->get['filter_transaction_id'])) {
			$filter_transaction_id = $this->request->get['filter_transaction_id'];
		} else {
			$filter_transaction_id = null;
		}

		if (isset($this->request->get['filter_transaction_type'])) {
			$filter_transaction_type = $this->request->get['filter_transaction_type'];
		} else {
			$filter_transaction_type = null;
		}

		if (isset($this->request->get['filter_payment_type'])) {
			$filter_payment_type = $this->request->get['filter_payment_type'];
		} else {
			$filter_payment_type = null;
		}

		if (isset($this->request->get['filter_card_type'])) {
			$filter_card_type = $this->request->get['filter_card_type'];
		} else {
			$filter_card_type = null;
		}

		if (isset($this->request->get['filter_merchant_account_id'])) {
			$filter_merchant_account_id = $this->request->get['filter_merchant_account_id'];
		} else {
			$filter_merchant_account_id = null;
		}

		if (isset($this->request->get['filter_transaction_status'])) {
			$filter_transaction_status = $this->request->get['filter_transaction_status'];
		} else {
			$filter_transaction_status = null;
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
		} else {
			$filter_date_from = null;
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$filter_date_to = null;
		}

		if (isset($this->request->get['filter_amount_from'])) {
			$filter_amount_from = $this->request->get['filter_amount_from'];
		} else {
			$filter_amount_from = null;
		}

		if (isset($this->request->get['filter_amount_to'])) {
			$filter_amount_to = $this->request->get['filter_amount_to'];
		} else {
			$filter_amount_to = null;
		}

		$json['transactions'] = array();

		$search = array();

		if ($filter_transaction_id) {
			$search[] = Braintree_TransactionSearch::id()->is($filter_transaction_id);
		}

		if ($filter_transaction_type) {
			if ($filter_transaction_type == 'sale') {
				$transaction_type = Braintree_Transaction::SALE;
			} elseif ($filter_transaction_type == 'credit') {
				$transaction_type = Braintree_Transaction::CREDIT;
			}

			$search[] = Braintree_TransactionSearch::type()->is($transaction_type);
		}

		if ($filter_payment_type) {
			if ($filter_payment_type == 'Credit Card') {
				$payment_type = 'CreditCardDetail';
			} elseif ($filter_payment_type == 'PayPal') {
				$payment_type = 'PayPalDetail';
			}

			$search[] = Braintree_TransactionSearch::paymentInstrumentType()->is($payment_type);
		}

		if ($filter_card_type) {
			switch ($filter_card_type) {
				case 'Visa':
					$card_type = Braintree_CreditCard::VISA;
					break;
				case 'MasterCard':
					$card_type = Braintree_CreditCard::MASTER_CARD;
					break;
				case 'American Express':
					$card_type = Braintree_CreditCard::AMEX;
					break;
				case 'Discover':
					$card_type = Braintree_CreditCard::DISCOVER;
					break;
				case 'JCB':
					$card_type = Braintree_CreditCard::JCB;
					break;
				case 'Maestro':
					$card_type = Braintree_CreditCard::MAESTRO;
					break;
			}

			$search[] = Braintree_TransactionSearch::creditCardCardType()->is($card_type);
		}

		if ($filter_merchant_account_id) {
			$search[] = Braintree_TransactionSearch::merchantAccountId()->is($filter_merchant_account_id);
		}

		if ($filter_transaction_status) {
			$search[] = Braintree_TransactionSearch::status()->in($filter_transaction_status);
		}

		if ($filter_date_from || $filter_date_to) {
			if ($filter_date_from) {
				$date_from = new DateTime($filter_date_from);
			} else {
				$date_from = new DateTime('2012-01-01 00:00');
			}

			if ($filter_date_to) {
				$date_to = new DateTime($filter_date_to . ' +1 day -1 minute');
			} else {
				$date_to = new DateTime('tomorrow -1 minute');
			}

			$search[] = Braintree_TransactionSearch::createdAt()->between($date_from, $date_to);
		}

		if ($filter_amount_from) {
			$amount_from = $filter_amount_from;
		} else {
			$amount_from = 0;
		}

		if ($filter_amount_to) {
			$amount_to = $filter_amount_to;
		} else {
			$amount_to = 9999999;
		}

		$search[] = Braintree_TransactionSearch::amount()->between((float)$amount_from, (float)$amount_to);

		$transactions = $this->model_extension_payment_braintree->getTransactions($this->gateway, $search);

		if ($transactions) {
			foreach ($transactions as $transaction) {
				$customer_url = false;

				if ($transaction->customer['id']) {
					$braintree_customer_id = explode('_', $transaction->customer['id']);

					if (isset($braintree_customer_id[2]) && is_numeric($braintree_customer_id[2])) {
						$customer_info = $this->model_customer_customer->getCustomer($braintree_customer_id[2]);

						if ($customer_info && $customer_info['email'] == $transaction->customer['email']) {
							$customer_url = $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . (int)$braintree_customer_id[2], true);
						}
					}
				}

				$order = false;

				if ($transaction->orderId) {
					$order_info = $this->model_sale_order->getOrder($transaction->orderId);

					if ($order_info && $order_info['email'] == $transaction->customer['email']) {
						$order = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$transaction->orderId, true);
					}
				}

				$created_at = $transaction->createdAt;

				$json['transactions'][] = array(
					'transaction_id'	  => $transaction->id,
					'amount'			  => $transaction->amount,
					'currency_iso'		  => $transaction->currencyIsoCode,
					'status'			  => $transaction->status,
					'type'				  => $transaction->type,
					'merchant_account_id' => $transaction->merchantAccountId,
					'customer'            => $transaction->customer['firstName'] . ' ' . $transaction->customer['lastName'],
					'customer_url'		  => $customer_url,
					'order'				  => $order,
					'date_added'		  => date($this->language->get('datetime_format'), strtotime($created_at->format('Y-m-d H:i:s e')))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function connectRedirect() {
		if ($this->user->hasPermission('modify', 'extension/extension/payment') && $this->user->hasPermission('modify', 'extension/payment/braintree')) {
			$curl = curl_init($this->opencart_connect_url);

			$this->load->model('localisation/country');
			$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

			$post_data = array(
				'return_url' => $this->url->link('extension/payment/braintree', 'token=' . $this->session->data['token'], true),
				'store_url' => HTTPS_CATALOG,
				'store_version' => VERSION,
				'store_country' => (isset($country['iso_code_3']) ? $country['iso_code_3'] : ''),
			);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

			$curl_response = curl_exec($curl);

			$curl_response = json_decode($curl_response, true);

			curl_close($curl);

			if ($curl_response['url']) {
				$this->response->redirect($curl_response['url']);
			} else {
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
			}
		} else {
			$this->response->redirect($this->url->link('error/permission', 'token=' . $this->session->data['token'], true));
		}
	}

	public function preferredSolution() {
		$this->load->language('extension/payment/braintree');

		$data = [];
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_preferred_main'] = $this->language->get('text_preferred_main');
		$data['text_learn_more'] = $this->language->get('text_learn_more');
		$data['text_preferred_li_1'] = $this->language->get('text_preferred_li_1');
		$data['text_preferred_li_2'] = $this->language->get('text_preferred_li_2');
		$data['text_preferred_li_3'] = $this->language->get('text_preferred_li_3');
		$data['text_preferred_li_4'] = $this->language->get('text_preferred_li_4');

		if ($this->user->hasPermission('modify', 'extension/extension/payment') && $this->user->hasPermission('modify', 'extension/payment/braintree')) {
			$data['connect_link'] = $this->url->link('extension/payment/braintree/connectRedirect', 'token=' . $this->session->data['token'], true);
		} else {
			// user does not have permission to modify, show no link
			$data['connect_link'] = '';
		}

		return $this->load->view('extension/payment/braintree_preferred', $data);
	}

	protected function validate() {
		$this->load->model('extension/payment/braintree');

		$check_credentials = true;

		if (version_compare(phpversion(), '5.4.0', '<')) {
			$this->error['warning'] = $this->language->get('error_php_version');
		}

		if (!$this->user->hasPermission('modify', 'extension/payment/braintree')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$check_credentials = false;
		}

		if ($check_credentials) {
			$this->initialise($this->request->post['braintree_access_token'], array(
				'braintree_environment' => $this->request->post['braintree_environment'],
				'braintree_merchant_id' => $this->request->post['braintree_merchant_id'],
				'braintree_public_key'	=> $this->request->post['braintree_public_key'],
				'braintree_private_key' => $this->request->post['braintree_private_key'],
			));

			$verify_credentials = $this->model_extension_payment_braintree->verifyCredentials($this->gateway);

			if (!$verify_credentials) {
				$this->error['warning'] = $this->language->get('error_connection');
			} else {
				foreach ($this->request->post['braintree_account'] as $currency => $braintree_account) {
					if (!empty($braintree_account['merchant_account_id'])) {
						$verify_merchant_account_id = $this->model_extension_payment_braintree->verifyMerchantAccount($this->gateway, $braintree_account['merchant_account_id']);

						if (!$verify_merchant_account_id) {
							$this->error['account'][$currency] = $this->language->get('error_account');
						}
					}
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	private function initialise($access_token = '', $credentials = array()) {
		$this->load->model('extension/payment/braintree');

		if ($access_token != '') {
			$this->gateway = $this->model_extension_payment_braintree->setGateway($access_token);
		} else {
			Braintree_Configuration::environment(isset($credentials['braintree_environment']) ? $credentials['braintree_environment'] : '');
			Braintree_Configuration::merchantId(isset($credentials['braintree_merchant_id']) ? $credentials['braintree_merchant_id'] : '');
			Braintree_Configuration::publicKey(isset($credentials['braintree_public_key']) ? $credentials['braintree_public_key'] : '');
			Braintree_Configuration::privateKey(isset($credentials['braintree_private_key']) ? $credentials['braintree_private_key'] : '');
		}
	}
}