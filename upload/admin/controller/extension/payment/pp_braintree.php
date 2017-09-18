<?php
class ControllerExtensionPaymentPPBraintree extends Controller {
	private $error = array();
	private $gateway = null;
	private $opencart_connect_url = 'https://www.opencart.com/index.php?route=external/braintree_auth/connect';
	private $opencart_retrieve_url = 'https://www.opencart.com/index.php?route=external/braintree_auth/retrieve';

	public function index() {
		$this->load->language('extension/payment/pp_braintree');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			foreach ($this->request->post['payment_pp_braintree_account'] as $currency => $account) {
				if (!isset($account['status'])) {
					$this->request->post['payment_pp_braintree_account'][$currency]['status'] = 0;
				}
			}

			$this->model_setting_setting->editSetting('payment_pp_braintree', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$data['button_configure'] = $this->url->link('extension/module/pp_braintree_button/configure', 'user_token=' . $this->session->data['user_token'], true);

		$data['user_token'] = $this->session->data['user_token'];

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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pp_braintree', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/pp_braintree', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_pp_braintree_merchant_id'])) {
			$data['payment_pp_braintree_merchant_id'] = $this->request->post['payment_pp_braintree_merchant_id'];
		} else {
			$data['payment_pp_braintree_merchant_id'] = $this->config->get('payment_pp_braintree_merchant_id');
		}

		if (isset($this->request->post['payment_pp_braintree_public_key'])) {
			$data['payment_pp_braintree_public_key'] = $this->request->post['payment_pp_braintree_public_key'];
		} else {
			$data['payment_pp_braintree_public_key'] = $this->config->get('payment_pp_braintree_public_key');
		}

		if (isset($this->request->post['payment_pp_braintree_private_key'])) {
			$data['payment_pp_braintree_private_key'] = $this->request->post['payment_pp_braintree_private_key'];
		} else {
			$data['payment_pp_braintree_private_key'] = $this->config->get('payment_pp_braintree_private_key');
		}

		if (isset($this->request->post['payment_pp_braintree_access_token'])) {
			$data['payment_pp_braintree_access_token'] = $this->request->post['payment_pp_braintree_access_token'];
		} else {
			$data['payment_pp_braintree_access_token'] = $this->config->get('payment_pp_braintree_access_token');
		}

		if (isset($this->request->post['payment_pp_braintree_refresh_token'])) {
			$data['payment_pp_braintree_refresh_token'] = $this->request->post['payment_pp_braintree_refresh_token'];
		} else {
			$data['payment_pp_braintree_refresh_token'] = $this->config->get('payment_pp_braintree_refresh_token');
		}

		if (isset($this->request->post['payment_pp_braintree_environment'])) {
			$data['payment_pp_braintree_environment'] = $this->request->post['payment_pp_braintree_environment'];
		} else {
			$data['payment_pp_braintree_environment'] = $this->config->get('payment_pp_braintree_environment');
		}

		if (isset($this->request->post['payment_pp_braintree_settlement_immediate'])) {
			$data['payment_pp_braintree_settlement_immediate'] = $this->request->post['payment_pp_braintree_settlement_immediate'];
		} else {
			$data['payment_pp_braintree_settlement_immediate'] = $this->config->get('payment_pp_braintree_settlement_immediate');
		}

		if (isset($this->request->post['payment_pp_braintree_card_vault'])) {
			$data['payment_pp_braintree_card_vault'] = $this->request->post['payment_pp_braintree_card_vault'];
		} else {
			$data['payment_pp_braintree_card_vault'] = $this->config->get('payment_pp_braintree_card_vault');
		}

		if (isset($this->request->post['payment_pp_braintree_card_check_vault'])) {
			$data['payment_pp_braintree_card_check_vault'] = $this->request->post['payment_pp_braintree_card_check_vault'];
		} else {
			$data['payment_pp_braintree_card_check_vault'] = $this->config->get('payment_pp_braintree_card_check_vault');
		}

		if (isset($this->request->post['payment_pp_braintree_paypal_vault'])) {
			$data['payment_pp_braintree_paypal_vault'] = $this->request->post['payment_pp_braintree_paypal_vault'];
		} else {
			$data['payment_pp_braintree_paypal_vault'] = $this->config->get('payment_pp_braintree_paypal_vault');
		}

		if (isset($this->request->post['payment_pp_braintree_paypal_check_vault'])) {
			$data['payment_pp_braintree_paypal_check_vault'] = $this->request->post['payment_pp_braintree_paypal_check_vault'];
		} else {
			$data['payment_pp_braintree_paypal_check_vault'] = $this->config->get('payment_pp_braintree_paypal_check_vault');
		}

		if (isset($this->request->post['payment_pp_braintree_vault_cvv_3ds'])) {
			$data['payment_pp_braintree_vault_cvv_3ds'] = $this->request->post['payment_pp_braintree_vault_cvv_3ds'];
		} else {
			$data['payment_pp_braintree_vault_cvv_3ds'] = $this->config->get('payment_pp_braintree_vault_cvv_3ds');
		}

		if (isset($this->request->post['payment_pp_braintree_debug'])) {
			$data['payment_pp_braintree_debug'] = $this->request->post['payment_pp_braintree_debug'];
		} else {
			$data['payment_pp_braintree_debug'] = $this->config->get('payment_pp_braintree_debug');
		}

		if (isset($this->request->post['payment_pp_braintree_total'])) {
			$data['payment_pp_braintree_total'] = $this->request->post['payment_pp_braintree_total'];
		} else {
			$data['payment_pp_braintree_total'] = $this->config->get('payment_pp_braintree_total');
		}

		if (isset($this->request->post['payment_pp_braintree_geo_zone_id'])) {
			$data['payment_pp_braintree_geo_zone_id'] = $this->request->post['payment_pp_braintree_geo_zone_id'];
		} else {
			$data['payment_pp_braintree_geo_zone_id'] = $this->config->get('payment_pp_braintree_geo_zone_id');
		}

		if (isset($this->request->post['payment_pp_braintree_status'])) {
			$data['payment_pp_braintree_status'] = $this->request->post['payment_pp_braintree_status'];
		} else {
			$data['payment_pp_braintree_status'] = $this->config->get('payment_pp_braintree_status');
		}

		if (isset($this->request->post['payment_pp_braintree_sort_order'])) {
			$data['payment_pp_braintree_sort_order'] = $this->request->post['payment_pp_braintree_sort_order'];
		} else {
			$data['payment_pp_braintree_sort_order'] = $this->config->get('payment_pp_braintree_sort_order');
		}

		if (isset($this->request->post['payment_pp_braintree_account'])) {
			$data['payment_pp_braintree_account'] = $this->request->post['payment_pp_braintree_account'];
		} else {
			$data['payment_pp_braintree_account'] = $this->config->get('payment_pp_braintree_account');
		}

		if (isset($this->request->post['payment_pp_braintree_authorization_expired_id'])) {
			$data['payment_pp_braintree_authorization_expired_id'] = $this->request->post['payment_pp_braintree_authorization_expired_id'];
		} else {
			$data['payment_pp_braintree_authorization_expired_id'] = $this->config->get('payment_pp_braintree_authorization_expired_id');
		}

		if (isset($this->request->post['payment_pp_braintree_authorized_id'])) {
			$data['payment_pp_braintree_authorized_id'] = $this->request->post['payment_pp_braintree_authorized_id'];
		} else {
			$data['payment_pp_braintree_authorized_id'] = $this->config->get('payment_pp_braintree_authorized_id');
		}

		if (isset($this->request->post['payment_pp_braintree_authorizing_id'])) {
			$data['payment_pp_braintree_authorizing_id'] = $this->request->post['payment_pp_braintree_authorizing_id'];
		} else {
			$data['payment_pp_braintree_authorizing_id'] = $this->config->get('payment_pp_braintree_authorizing_id');
		}

		if (isset($this->request->post['payment_pp_braintree_settlement_pending_id'])) {
			$data['payment_pp_braintree_settlement_pending_id'] = $this->request->post['payment_pp_braintree_settlement_pending_id'];
		} else {
			$data['payment_pp_braintree_settlement_pending_id'] = $this->config->get('payment_pp_braintree_settlement_pending_id');
		}

		if (isset($this->request->post['payment_pp_braintree_failed_id'])) {
			$data['payment_pp_braintree_failed_id'] = $this->request->post['payment_pp_braintree_failed_id'];
		} else {
			$data['payment_pp_braintree_failed_id'] = $this->config->get('payment_pp_braintree_failed_id');
		}

		if (isset($this->request->post['payment_pp_braintree_gateway_rejected_id'])) {
			$data['payment_pp_braintree_gateway_rejected_id'] = $this->request->post['payment_pp_braintree_gateway_rejected_id'];
		} else {
			$data['payment_pp_braintree_gateway_rejected_id'] = $this->config->get('payment_pp_braintree_gateway_rejected_id');
		}

		if (isset($this->request->post['payment_pp_braintree_processor_declined_id'])) {
			$data['payment_pp_braintree_processor_declined_id'] = $this->request->post['payment_pp_braintree_processor_declined_id'];
		} else {
			$data['payment_pp_braintree_processor_declined_id'] = $this->config->get('payment_pp_braintree_processor_declined_id');
		}

		if (isset($this->request->post['payment_pp_braintree_settled_id'])) {
			$data['payment_pp_braintree_settled_id'] = $this->request->post['payment_pp_braintree_settled_id'];
		} else {
			$data['payment_pp_braintree_settled_id'] = $this->config->get('payment_pp_braintree_settled_id');
		}

		if (isset($this->request->post['payment_pp_braintree_settling_id'])) {
			$data['payment_pp_braintree_settling_id'] = $this->request->post['payment_pp_braintree_settling_id'];
		} else {
			$data['payment_pp_braintree_settling_id'] = $this->config->get('payment_pp_braintree_settling_id');
		}

		if (isset($this->request->post['payment_pp_braintree_submitted_for_settlement_id'])) {
			$data['payment_pp_braintree_submitted_for_settlement_id'] = $this->request->post['payment_pp_braintree_submitted_for_settlement_id'];
		} else {
			$data['payment_pp_braintree_submitted_for_settlement_id'] = $this->config->get('payment_pp_braintree_submitted_for_settlement_id');
		}

		if (isset($this->request->post['payment_pp_braintree_voided_id'])) {
			$data['payment_pp_braintree_voided_id'] = $this->request->post['payment_pp_braintree_voided_id'];
		} else {
			$data['payment_pp_braintree_voided_id'] = $this->config->get('payment_pp_braintree_voided_id');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_status'])) {
			$data['payment_pp_braintree_3ds_status'] = $this->request->post['payment_pp_braintree_3ds_status'];
		} else {
			$data['payment_pp_braintree_3ds_status'] = $this->config->get('payment_pp_braintree_3ds_status');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_unsupported_card'])) {
			$data['payment_pp_braintree_3ds_unsupported_card'] = $this->request->post['payment_pp_braintree_3ds_unsupported_card'];
		} else {
			$data['payment_pp_braintree_3ds_unsupported_card'] = $this->config->get('payment_pp_braintree_3ds_unsupported_card');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_lookup_error'])) {
			$data['payment_pp_braintree_3ds_lookup_error'] = $this->request->post['payment_pp_braintree_3ds_lookup_error'];
		} else {
			$data['payment_pp_braintree_3ds_lookup_error'] = $this->config->get('payment_pp_braintree_3ds_lookup_error');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_lookup_enrolled'])) {
			$data['payment_pp_braintree_3ds_lookup_enrolled'] = $this->request->post['payment_pp_braintree_3ds_lookup_enrolled'];
		} else {
			$data['payment_pp_braintree_3ds_lookup_enrolled'] = $this->config->get('payment_pp_braintree_3ds_lookup_enrolled');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_lookup_not_enrolled'])) {
			$data['payment_pp_braintree_3ds_lookup_not_enrolled'] = $this->request->post['payment_pp_braintree_3ds_lookup_not_enrolled'];
		} else {
			$data['payment_pp_braintree_3ds_lookup_not_enrolled'] = $this->config->get('payment_pp_braintree_3ds_lookup_not_enrolled');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_not_participating'])) {
			$data['payment_pp_braintree_3ds_not_participating'] = $this->request->post['payment_pp_braintree_3ds_not_participating'];
		} else {
			$data['payment_pp_braintree_3ds_not_participating'] = $this->config->get('payment_pp_braintree_3ds_not_participating');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_unavailable'])) {
			$data['payment_pp_braintree_3ds_unavailable'] = $this->request->post['payment_pp_braintree_3ds_unavailable'];
		} else {
			$data['payment_pp_braintree_3ds_unavailable'] = $this->config->get('payment_pp_braintree_3ds_unavailable');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_signature_failed'])) {
			$data['payment_pp_braintree_3ds_signature_failed'] = $this->request->post['payment_pp_braintree_3ds_signature_failed'];
		} else {
			$data['payment_pp_braintree_3ds_signature_failed'] = $this->config->get('payment_pp_braintree_3ds_signature_failed');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_successful'])) {
			$data['payment_pp_braintree_3ds_successful'] = $this->request->post['payment_pp_braintree_3ds_successful'];
		} else {
			$data['payment_pp_braintree_3ds_successful'] = $this->config->get('payment_pp_braintree_3ds_successful');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_attempt_successful'])) {
			$data['payment_pp_braintree_3ds_attempt_successful'] = $this->request->post['payment_pp_braintree_3ds_attempt_successful'];
		} else {
			$data['payment_pp_braintree_3ds_attempt_successful'] = $this->config->get('payment_pp_braintree_3ds_attempt_successful');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_failed'])) {
			$data['payment_pp_braintree_3ds_failed'] = $this->request->post['payment_pp_braintree_3ds_failed'];
		} else {
			$data['payment_pp_braintree_3ds_failed'] = $this->config->get('payment_pp_braintree_3ds_failed');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_unable_to_auth'])) {
			$data['payment_pp_braintree_3ds_unable_to_auth'] = $this->request->post['payment_pp_braintree_3ds_unable_to_auth'];
		} else {
			$data['payment_pp_braintree_3ds_unable_to_auth'] = $this->config->get('payment_pp_braintree_3ds_unable_to_auth');
		}

		if (isset($this->request->post['payment_pp_braintree_3ds_error'])) {
			$data['payment_pp_braintree_3ds_error'] = $this->request->post['payment_pp_braintree_3ds_error'];
		} else {
			$data['payment_pp_braintree_3ds_error'] = $this->config->get('payment_pp_braintree_3ds_error');
		}

		if (isset($this->request->post['payment_pp_braintree_paypal_option'])) {
			$data['payment_pp_braintree_paypal_option'] = $this->request->post['payment_pp_braintree_paypal_option'];
		} else {
			$data['payment_pp_braintree_paypal_option'] = $this->config->get('payment_pp_braintree_paypal_option');
		}

		if (isset($this->request->post['payment_pp_braintree_paypal_button_colour'])) {
			$data['payment_pp_braintree_paypal_button_colour'] = $this->request->post['payment_pp_braintree_paypal_button_colour'];
		} else {
			$data['payment_pp_braintree_paypal_button_colour'] = $this->config->get('payment_pp_braintree_paypal_button_colour');
		}

		if (isset($this->request->post['payment_pp_braintree_paypal_button_size'])) {
			$data['payment_pp_braintree_paypal_button_size'] = $this->request->post['payment_pp_braintree_paypal_button_size'];
		} else {
			$data['payment_pp_braintree_paypal_button_size'] = $this->config->get('payment_pp_braintree_paypal_button_size');
		}

		if (isset($this->request->post['payment_pp_braintree_paypal_button_shape'])) {
			$data['payment_pp_braintree_paypal_button_shape'] = $this->request->post['payment_pp_braintree_paypal_button_shape'];
		} else {
			$data['payment_pp_braintree_paypal_button_shape'] = $this->config->get('payment_pp_braintree_paypal_button_shape');
		}

		if (isset($this->request->post['payment_pp_braintree_billing_agreement'])) {
			$data['payment_pp_braintree_billing_agreement'] = $this->request->post['payment_pp_braintree_billing_agreement'];
		} else {
			$data['payment_pp_braintree_billing_agreement'] = $this->config->get('payment_pp_braintree_billing_agreement');
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
				'return_url' => $this->url->link('extension/payment/pp_braintree', 'user_token=' . $this->session->data['user_token'], true),
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
				$braintree_settings = $this->model_setting_setting->getSetting('payment_pp_braintree');
				$braintree_settings['payment_pp_braintree_merchant_id'] = $config_response['merchant_id'];
				$braintree_settings['payment_pp_braintree_access_token'] = $config_response['access_token'];
				$braintree_settings['payment_pp_braintree_refresh_token'] = $config_response['refresh_token'];
				$braintree_settings['payment_pp_braintree_environment'] = $config_response['environment'];
				$braintree_settings['payment_pp_braintree_public_key'] = '';
				$braintree_settings['payment_pp_braintree_private_key'] = '';

				$this->model_setting_setting->editSetting('payment_pp_braintree', $braintree_settings);

				$data['payment_pp_braintree_merchant_id'] = $config_response['merchant_id'];
				$data['payment_pp_braintree_access_token'] = $config_response['access_token'];
				$data['payment_pp_braintree_refresh_token'] = $config_response['refresh_token'];
				$data['payment_pp_braintree_environment'] = $config_response['environment'];
				$data['payment_pp_braintree_public_key'] = '';
				$data['payment_pp_braintree_private_key'] = '';

				$data['success'] = $this->language->get('text_success_connect');
			}
		}

		$data['auth_connect_url'] = '';

		// If Braintree is not setup yet, request auth token for merchant on-boarding flow
		if ($data['payment_pp_braintree_merchant_id'] == '') {
			$curl = curl_init($this->opencart_connect_url);

			$this->load->model('localisation/country');
			$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

			$post_data = array(
				'return_url' => $this->url->link('extension/payment/pp_braintree', 'user_token=' . $this->session->data['user_token'], true),
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

		$data['braintree_config'] = array();
		$data['braintree_config']['three_d_secure_enabled'] = 0;
		$data['braintree_config']['paypal_enabled'] = 0;

		$data['error_braintree_account_3ds'] = $this->language->get('error_braintree_account_3ds');
		$data['error_braintree_account_paypal'] = $this->language->get('error_braintree_account_paypal');

		// load the account info from braintree if the config has been added yet.
		if (!empty($data['payment_pp_braintree_access_token']) || (!empty($data['payment_pp_braintree_environment']) && !empty($data['payment_pp_braintree_merchant_id']) && !empty($data['payment_pp_braintree_public_key']) && !empty($data['payment_pp_braintree_private_key']))) {
			$this->initialise($data['payment_pp_braintree_access_token'], array(
				'payment_pp_braintree_environment' => $data['payment_pp_braintree_environment'],
				'payment_pp_braintree_merchant_id' => $data['payment_pp_braintree_merchant_id'],
				'payment_pp_braintree_public_key'	=> $data['payment_pp_braintree_public_key'],
				'payment_pp_braintree_private_key' => $data['payment_pp_braintree_private_key'],
			));

			$verify_credentials = $this->model_extension_payment_pp_braintree->verifyCredentials($this->gateway);

			if (!$verify_credentials) {
				$this->error['warning'] = $this->language->get('error_connection');
			} else {
				$merchant_config = json_decode(base64_decode($verify_credentials), true);

				if (isset($merchant_config['threeDSecureEnabled']) && $merchant_config['threeDSecureEnabled'] == 1) {
					$data['braintree_config']['three_d_secure_enabled'] = 1;
				}

				if (isset($merchant_config['paypalEnabled']) && $merchant_config['paypalEnabled'] == 1) {
					$data['braintree_config']['paypal_enabled'] = 1;
				}
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_braintree', $data));
	}

	public function install() {
		$this->load->model('setting/setting');

		$defaults = array();

		// 3D secure defaults
		$defaults['payment_pp_braintree_3ds_unsupported_card'] = 1;
		$defaults['payment_pp_braintree_3ds_lookup_error'] = 1;
		$defaults['payment_pp_braintree_3ds_lookup_enrolled'] = 1;
		$defaults['payment_pp_braintree_3ds_lookup_not_enrolled'] = 1;
		$defaults['payment_pp_braintree_3ds_not_participating'] = 1;
		$defaults['payment_pp_braintree_3ds_unavailable'] = 1;
		$defaults['payment_pp_braintree_3ds_signature_failed'] = 0;
		$defaults['payment_pp_braintree_3ds_successful'] = 1;
		$defaults['payment_pp_braintree_3ds_attempt_successful'] = 1;
		$defaults['payment_pp_braintree_3ds_failed'] = 0;
		$defaults['payment_pp_braintree_3ds_unable_to_auth'] = 1;
		$defaults['payment_pp_braintree_3ds_error'] = 1;

		// Order Status defaults
		$defaults['payment_pp_braintree_authorization_expired_id'] = 14;
		$defaults['payment_pp_braintree_authorized_id'] = 2;
		$defaults['payment_pp_braintree_authorizing_id'] = 1;
		$defaults['payment_pp_braintree_failed_id'] = 10;
		$defaults['payment_pp_braintree_gateway_rejected_id'] = 8;
		$defaults['payment_pp_braintree_processor_declined_id'] = 8;
		$defaults['payment_pp_braintree_settled_id'] = 2;
		$defaults['payment_pp_braintree_settling_id'] = 2;
		$defaults['payment_pp_braintree_settlement_pending_id'] = 2;
		$defaults['payment_pp_braintree_submitted_for_settlement_id'] = 2;
		$defaults['payment_pp_braintree_voided_id'] = 16;

		$defaults['payment_pp_braintree_settlement_immediate'] = 1;

		// PayPal options
		$defaults['payment_pp_braintree_paypal_option'] = 1;
		$defaults['payment_pp_braintree_paypal_button_size'] = 'small';
		$defaults['payment_pp_braintree_paypal_button_shape'] = 'rect';

		// Vault options
		$defaults['payment_pp_braintree_card_vault'] = 1;
		$defaults['payment_pp_braintree_paypal_vault'] = 0;
		$defaults['payment_pp_braintree_card_check_vault'] = 1;
		$defaults['payment_pp_braintree_paypal_check_vault'] = 0;

		$this->model_setting_setting->editSetting('payment_pp_braintree', $defaults);
	}

	public function order() {
		$this->load->language('extension/payment/pp_braintree');

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['order_id'] = $this->request->get['order_id'];

		return $this->load->view('extension/payment/pp_braintree_order', $data);
	}

	public function getTransaction() {
		$this->load->language('extension/payment/pp_braintree');

		$this->load->model('extension/payment/pp_braintree');
		$this->load->model('sale/order');

		if (!$this->config->get('payment_pp_braintree_status') || (!isset($this->request->get['order_id']) && !isset($this->request->get['transaction_id']))) {
			return;
		}

		$this->initialise($this->config->get('payment_pp_braintree_access_token'), array(
			'payment_pp_braintree_environment' => $this->config->get('payment_pp_braintree_environment'),
			'payment_pp_braintree_merchant_id' => $this->config->get('payment_pp_braintree_merchant_id'),
			'payment_pp_braintree_public_key'	=> $this->config->get('payment_pp_braintree_public_key'),
			'payment_pp_braintree_private_key' => $this->config->get('payment_pp_braintree_private_key')
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

		$search_transactions = $this->model_extension_payment_pp_braintree->getTransactions($this->gateway, $search);

		$transaction = array();

		foreach ($search_transactions as $search_transaction) {
			$transaction = $search_transaction;
		}

		$data['transaction'] = array();

		if ($transaction) {
			$data['transaction_id'] = $transaction->id;
			
			$data['user_token'] = $this->session->data['user_token'];

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
				$refund = $this->model_extension_payment_pp_braintree->getTransaction($this->gateway, $refund_id);

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

			$this->response->setOutput($this->load->view('extension/payment/pp_braintree_order_ajax', $data));
		}
	}

	public function transactionCommand() {
		$this->load->language('extension/payment/pp_braintree');

		$this->load->model('extension/payment/pp_braintree');

		$this->initialise($this->config->get('payment_pp_braintree_access_token'), array(
			'payment_pp_braintree_environment' => $this->config->get('payment_pp_braintree_environment'),
			'payment_pp_braintree_merchant_id' => $this->config->get('payment_pp_braintree_merchant_id'),
			'payment_pp_braintree_public_key'	=> $this->config->get('payment_pp_braintree_public_key'),
			'payment_pp_braintree_private_key' => $this->config->get('payment_pp_braintree_private_key')
		));

		$json = array();

		$success = $error = '';

		if ($this->request->post['type'] == 'void') {
			$action = $this->model_extension_payment_pp_braintree->voidTransaction($this->gateway, $this->request->post['transaction_id']);
		} elseif ($this->request->post['type'] == 'settle' && $this->request->post['amount']) {
			$action = $this->model_extension_payment_pp_braintree->settleTransaction($this->gateway, $this->request->post['transaction_id'], $this->request->post['amount']);
		} elseif ($this->request->post['type'] == 'refund' && $this->request->post['amount']) {
			$action = $this->model_extension_payment_pp_braintree->refundTransaction($this->gateway, $this->request->post['transaction_id'], $this->request->post['amount']);
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
		$this->load->language('extension/payment/pp_braintree');

		$this->load->model('extension/payment/pp_braintree');
		$this->load->model('customer/customer');
		$this->load->model('sale/order');

		$this->initialise($this->config->get('payment_pp_braintree_access_token'), array(
			'payment_pp_braintree_environment' => $this->config->get('payment_pp_braintree_environment'),
			'payment_pp_braintree_merchant_id' => $this->config->get('payment_pp_braintree_merchant_id'),
			'payment_pp_braintree_public_key'	=> $this->config->get('payment_pp_braintree_public_key'),
			'payment_pp_braintree_private_key' => $this->config->get('payment_pp_braintree_private_key')
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

		$transactions = $this->model_extension_payment_pp_braintree->getTransactions($this->gateway, $search);

		if ($transactions) {
			foreach ($transactions as $transaction) {
				$customer_url = false;

				if ($transaction->customer['id']) {
					$braintree_customer_id = explode('_', $transaction->customer['id']);

					if (isset($braintree_customer_id[2]) && is_numeric($braintree_customer_id[2])) {
						$customer_info = $this->model_customer_customer->getCustomer($braintree_customer_id[2]);

						if ($customer_info && $customer_info['email'] == $transaction->customer['email']) {
							$customer_url = $this->url->link('sale/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . (int)$braintree_customer_id[2], true);
						}
					}
				}

				$order = false;

				if ($transaction->orderId) {
					$order_info = $this->model_sale_order->getOrder($transaction->orderId);

					if ($order_info && $order_info['email'] == $transaction->customer['email']) {
						$order = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int)$transaction->orderId, true);
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
		if ($this->user->hasPermission('modify', 'extension/extension/payment')) {
			// Install the module before doing the redirect
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('payment', 'pp_braintree');

			$this->install();

			$curl = curl_init($this->opencart_connect_url);

			$this->load->model('localisation/country');
			$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

			$post_data = array(
				'return_url' => $this->url->link('extension/payment/pp_braintree', 'user_token=' . $this->session->data['user_token'], true),
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
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
			}
		} else {
			$this->response->redirect($this->url->link('error/permission', 'user_token=' . $this->session->data['user_token'], true));
		}
	}

	public function preferredSolution() {
		$this->load->language('extension/payment/pp_braintree');

		$data['connect_link'] = '';
		$data['module_link'] = '';

		if ($this->config->get('payment_pp_braintree_status') || $this->config->get('payment_pp_braintree_merchant_id') || $this->config->get('payment_pp_braintree_access_token')) {
			$data['module_link'] = $this->url->link('extension/payment/pp_braintree', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			if ($this->user->hasPermission('modify', 'extension/extension/payment')) {
				$data['connect_link'] = $this->url->link('extension/payment/pp_braintree/connectRedirect', 'user_token=' . $this->session->data['user_token'], true);
			}
		}

		if ($this->config->get("payment_pp_braintree_status") == 1) {
			$data['payment_pp_braintree_status'] = "enabled";
		} elseif ($this->config->get("payment_pp_braintree_status") == null) {
			$data['payment_pp_braintree_status'] = "";
		} else {
			$data['payment_pp_braintree_status'] = "disabled";
		}

		return $this->load->view('extension/payment/pp_braintree_preferred', $data);
	}

	protected function validate() {
		$this->load->model('extension/payment/pp_braintree');

		$check_credentials = true;

		if (version_compare(phpversion(), '5.4.0', '<')) {
			$this->error['warning'] = $this->language->get('error_php_version');
		}

		if (!$this->user->hasPermission('modify', 'extension/payment/pp_braintree')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$check_credentials = false;
		}

		if ($check_credentials && $this->request->post['payment_pp_braintree_status'] == 1) {
			$this->initialise($this->request->post['payment_pp_braintree_access_token'], array(
				'payment_pp_braintree_environment' => $this->request->post['payment_pp_braintree_environment'],
				'payment_pp_braintree_merchant_id' => $this->request->post['payment_pp_braintree_merchant_id'],
				'payment_pp_braintree_public_key'	=> $this->request->post['payment_pp_braintree_public_key'],
				'payment_pp_braintree_private_key' => $this->request->post['payment_pp_braintree_private_key'],
			));

			$verify_credentials = $this->model_extension_payment_pp_braintree->verifyCredentials($this->gateway);

			if (!$verify_credentials) {
				$this->error['warning'] = $this->language->get('error_connection');
			} else {
				foreach ($this->request->post['payment_pp_braintree_account'] as $currency => $pp_braintree_account) {
					if (!empty($pp_braintree_account['merchant_account_id'])) {
						$verify_merchant_account_id = $this->model_extension_payment_pp_braintree->verifyMerchantAccount($this->gateway, $pp_braintree_account['merchant_account_id']);

						if (!$verify_merchant_account_id) {
							$this->error['account'][$currency] = $this->language->get('error_account');
						}
					}
				}

				$merchant_config = json_decode(base64_decode($verify_credentials), true);

				// verify the Braintree account is ready to accept 3DS transactions
				if (isset($merchant_config['threeDSecureEnabled']) && ($this->request->post['payment_pp_braintree_3ds_status'] == 1 && $merchant_config['threeDSecureEnabled'] != 1)) {
					$this->error['warning'] = $this->language->get('error_3ds_not_ready');
				}

				// verify the Braintree account is ready to use PayPal Billing Agreements
				if (isset($merchant_config['paypal']['billingAgreementEnabled']) && ($this->request->post['payment_pp_braintree_billing_agreement'] == 1 && $merchant_config['paypal']['billingAgreementEnabled'] != 1)) {
					$this->error['warning'] = $this->language->get('error_paypal_billing_not_ready');
				}

				// verify the Braintree account is ready to accept PayPal transactions
				if (isset($merchant_config['paypalEnabled']) && ($this->request->post['payment_pp_braintree_paypal_option'] == 1 && $merchant_config['paypalEnabled'] != 1)) {
					$this->error['warning'] = $this->language->get('error_paypal_not_ready');
				}

				// verify the environment matches with the token the system is using
				if (isset($merchant_config['environment']) && ($this->request->post['payment_pp_braintree_environment'] != $merchant_config['environment'])) {
					$this->error['warning'] = sprintf($this->language->get('error_environment'), $this->request->post['payment_pp_braintree_environment'], $merchant_config['environment']);
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	private function initialise($access_token = '', $credentials = array()) {
		$this->load->model('extension/payment/pp_braintree');

		if ($access_token != '') {
			$this->gateway = $this->model_extension_payment_pp_braintree->setGateway($access_token);
		} else {
			Braintree_Configuration::environment(isset($credentials['payment_pp_braintree_environment']) ? $credentials['payment_pp_braintree_environment'] : '');
			Braintree_Configuration::merchantId(isset($credentials['payment_pp_braintree_merchant_id']) ? $credentials['payment_pp_braintree_merchant_id'] : '');
			Braintree_Configuration::publicKey(isset($credentials['payment_pp_braintree_public_key']) ? $credentials['payment_pp_braintree_public_key'] : '');
			Braintree_Configuration::privateKey(isset($credentials['payment_pp_braintree_private_key']) ? $credentials['payment_pp_braintree_private_key'] : '');
		}
	}
}
