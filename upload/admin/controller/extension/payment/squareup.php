<?php

class ControllerExtensionPaymentSquareup extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/squareup');

		$this->load->model('extension/payment/squareup');
		$this->load->model('setting/setting');

		$this->load->library('squareup');

		if ($this->request->server['HTTPS']) {
			$server = HTTPS_SERVER;
		} else {
			$server = HTTP_SERVER;
		}

		$previous_setting = $this->model_setting_setting->getSetting('payment_squareup');

		try {
			if ($this->config->get('payment_squareup_access_token')) {
				if (!$this->squareup->verifyToken($this->config->get('payment_squareup_access_token'))) {
					unset($previous_setting['payment_squareup_merchant_id']);
					unset($previous_setting['payment_squareup_merchant_name']);
					unset($previous_setting['payment_squareup_access_token']);
					unset($previous_setting['payment_squareup_refresh_token']);
					unset($previous_setting['payment_squareup_access_token_expires']);
					unset($previous_setting['payment_squareup_locations']);
					unset($previous_setting['payment_squareup_sandbox_locations']);

					$this->config->set('payment_squareup_merchant_id', null);
				} else {
					if (!$this->config->get('payment_squareup_locations')) {
						$first_location_id = null;

						$previous_setting['payment_squareup_locations'] = $this->squareup->listLocations($this->config->get('payment_squareup_access_token'), $first_location_id);
						$previous_setting['payment_squareup_location_id'] = $first_location_id;
					}
				}
			}

			if (!isset($first_location_id)) {
				$first_location_id = null;
			}

			if (!$this->config->get('payment_squareup_sandbox_locations') && $this->config->get('payment_squareup_sandbox_token')) {
				$previous_setting['payment_squareup_sandbox_locations'] = $this->squareup->listLocations($this->config->get('payment_squareup_sandbox_token'), $first_location_id);
				$previous_setting['payment_squareup_sandbox_location_id'] = $first_location_id;
			}

			$this->model_setting_setting->editSetting('payment_squareup', $previous_setting);
		} catch (\Squareup\Exception $e) {
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => sprintf($this->language->get('text_location_error'), $e->getMessage())
			));
		}

		$previous_config = new Config();

		foreach ($previous_setting as $key => $value) {
			$previous_config->set($key, $value);
		}        

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_squareup', array_merge($previous_setting, $this->request->post));

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->get['save_and_auth'])) {
				$auth_link = $this->squareup->authLink($this->request->post['payment_squareup_client_id']);
				$this->response->redirect($auth_link);
			} else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
			}
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['error_status']                       = $this->getValidationError('status');
		$data['error_display_name']                 = $this->getValidationError('display_name');
		$data['error_client_id']                    = $this->getValidationError('client_id');
		$data['error_client_secret']                = $this->getValidationError('client_secret');
		$data['error_delay_capture']                = $this->getValidationError('delay_capture');
		$data['error_sandbox_client_id']            = $this->getValidationError('sandbox_client_id');
		$data['error_sandbox_token']                = $this->getValidationError('sandbox_token');
		$data['error_location']                     = $this->getValidationError('location');
		$data['error_cron_email']                   = $this->getValidationError('cron_email');
		$data['error_cron_acknowledge']             = $this->getValidationError('cron_acknowledge');

		$default_content_security_policy  = "default-src 'self';\n";
		$default_content_security_policy .= "script-src 'self' https://js.squareup.com https://js.squareupsandbox.com https://web.squarecdn.com https://sandbox.web.squarecdn.com 'unsafe-inline' 'unsafe-eval';\n";
		$default_content_security_policy .= "style-src 'self' https://js.squareup.com https://js.squareupsandbox.com https://web.squarecdn.com https://sandbox.web.squarecdn.com https://fonts.googleapis.com 'unsafe-inline';\n";
		$default_content_security_policy .= "font-src 'self' https://fonts.gstatic.com https://square-fonts-production-f.squarecdn.com https://d1g145x70srn7h.cloudfront.net https://cash-f.squarecdn.com;\n";
		$default_content_security_policy .= "img-src 'self' data: https://js.squareup.com https://js.squareupsandbox.com https://web.squarecdn.com https://sandbox.web.squarecdn.com;\n";
		$default_content_security_policy .= "frame-src 'self' https://js.squareup.com https://js.squareupsandbox.com https://web.squarecdn.com https://sandbox.web.squarecdn.com https://connect.squareup.com https://connect.squareupsandbox.com https://api.squareupsandbox.com https://api.squareup.com;\n";
		$default_content_security_policy .= "connect-src 'self' https://connect.squareup.com https://connect.squareupsandbox.com https://pci-connect.squareup.com https://pci-connect.squareupsandbox.com https://*.squareup.com;\n";
		$default_content_security_policy .= "base-uri 'self';\n";
		$default_content_security_policy .= "form-action 'self' https://api.squareupsandbox.com https://api.squareup.com;";

		$data['payment_square_default_csp'] = $default_content_security_policy;

		$data['payment_squareup_status']                    = $this->getSettingValue('payment_squareup_status');
		$data['payment_squareup_status_authorized']         = $this->getSettingValue('payment_squareup_status_authorized',$this->model_extension_payment_squareup->inferOrderStatusId('processing'));
		$data['payment_squareup_status_captured']           = $this->getSettingValue('payment_squareup_status_captured', $this->model_extension_payment_squareup->inferOrderStatusId('processed'));
		$data['payment_squareup_status_voided']             = $this->getSettingValue('payment_squareup_status_voided', $this->model_extension_payment_squareup->inferOrderStatusId('void'));
		$data['payment_squareup_status_failed']             = $this->getSettingValue('payment_squareup_status_failed', $this->model_extension_payment_squareup->inferOrderStatusId('fail'));
		$data['payment_squareup_display_name']              = $this->getSettingValue('payment_squareup_display_name');
		$data['payment_squareup_client_id']                 = $this->getSettingValue('payment_squareup_client_id');
		$data['payment_squareup_client_secret']             = $this->getSettingValue('payment_squareup_client_secret');
		$data['payment_squareup_enable_sandbox']            = $this->getSettingValue('payment_squareup_enable_sandbox');
		$data['payment_squareup_debug']                     = $this->getSettingValue('payment_squareup_debug');
		$data['payment_squareup_sort_order']                = $this->getSettingValue('payment_squareup_sort_order');
		$data['payment_squareup_total']                     = $this->getSettingValue('payment_squareup_total');
		$data['payment_squareup_geo_zone_id']               = $this->getSettingValue('payment_squareup_geo_zone_id');
		$data['payment_squareup_sandbox_client_id']         = $this->getSettingValue('payment_squareup_sandbox_client_id');
		$data['payment_squareup_sandbox_token']             = $this->getSettingValue('payment_squareup_sandbox_token');
		$data['payment_squareup_locations']                 = $this->getSettingValue('payment_squareup_locations', $previous_config->get('payment_squareup_locations'));
		$data['payment_squareup_location_id']               = $this->getSettingValue('payment_squareup_location_id');
		$data['payment_squareup_sandbox_locations']         = $this->getSettingValue('payment_squareup_sandbox_locations', $previous_config->get('payment_squareup_sandbox_locations'));
		$data['payment_squareup_sandbox_location_id']       = $this->getSettingValue('payment_squareup_sandbox_location_id');
		$data['payment_squareup_quick_pay']                 = $this->getSettingValue('payment_squareup_quick_pay', '1');
		$data['payment_squareup_delay_capture']             = $this->getSettingValue('payment_squareup_delay_capture', '0');
		$data['payment_squareup_content_security']          = $this->getSettingValue('payment_squareup_content_security', $default_content_security_policy);
		$data['payment_squareup_recurring_status']          = $this->getSettingValue('payment_squareup_recurring_status');
		$data['payment_squareup_cron_email_status']         = $this->getSettingValue('payment_squareup_cron_email_status');
		$data['payment_squareup_cron_email']                = $this->getSettingValue('payment_squareup_cron_email', $this->config->get('config_email'));
		$data['payment_squareup_cron_token']                = $this->getSettingValue('payment_squareup_cron_token');
		$data['payment_squareup_cron_acknowledge']          = $this->getSettingValue('payment_squareup_cron_acknowledge', null, true);
		$data['payment_squareup_notify_recurring_success']  = $this->getSettingValue('payment_squareup_notify_recurring_success');
		$data['payment_squareup_notify_recurring_fail']     = $this->getSettingValue('payment_squareup_notify_recurring_fail');
		$data['payment_squareup_merchant_id']               = $this->getSettingValue('payment_squareup_merchant_id', $previous_config->get('payment_squareup_merchant_id'));
		$data['payment_squareup_merchant_name']             = $this->getSettingValue('payment_squareup_merchant_name', $previous_config->get('payment_squareup_merchant_name'));

		if ($previous_config->get('payment_squareup_access_token') && $previous_config->get('payment_squareup_access_token_expires')) {
			$expiration_time = date_create_from_format('Y-m-d\TH:i:s\Z', $previous_config->get('payment_squareup_access_token_expires'));
			$now = date_create();

			$delta = $expiration_time->getTimestamp() - $now->getTimestamp();
			$expiration_date_formatted = $expiration_time->format('l, F jS, Y h:i:s A, e');

			if ($delta < 0) {
				$this->pushAlert(array(
					'type' => 'danger',
					'icon' => 'exclamation-circle',
					'text' => sprintf($this->language->get('text_token_expired'), $this->url->link('extension/payment/squareup/refresh_token', 'user_token=' . $this->session->data['user_token'], true))
				));
			} else if ($delta < (5 * 24 * 60 * 60)) { // token is valid, just about to expire
				$this->pushAlert(array(
					'type' => 'warning',
					'icon' => 'exclamation-circle',
					'text' => sprintf($this->language->get('text_token_expiry_warning'), $expiration_date_formatted, $this->url->link('extension/payment/squareup/refresh_token', 'user_token=' . $this->session->data['user_token'], true))
				));
			}

			$data['access_token_expires_time'] = $expiration_date_formatted;
		} else if ($previous_config->get('payment_squareup_client_id')) {
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => sprintf($this->language->get('text_token_revoked'), $this->squareup->authLink($previous_config->get('payment_squareup_client_id')))
			));

			$data['access_token_expires_time'] = $this->language->get('text_na');
		}

		if ($previous_config->get('payment_squareup_client_id')) {
			$data['payment_squareup_auth_link'] = $this->squareup->authLink($previous_config->get('payment_squareup_client_id'));
		} else {
			$data['payment_squareup_auth_link'] = null;
		}

		$data['payment_squareup_redirect_uri'] = str_replace('&amp;', '&', $this->url->link('extension/payment/squareup/oauth_callback', '', true));
		$data['payment_squareup_refresh_link'] = $this->url->link('extension/payment/squareup/refresh_token', 'user_token=' . $this->session->data['user_token'], true);

		if ($this->config->get('payment_squareup_enable_sandbox')) {
			$this->pushAlert(array(
				'type' => 'warning',
				'icon' => 'exclamation-circle',
				'text' => $this->language->get('text_sandbox_enabled')
			));
		}

		if (isset($this->error['warning'])) {
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => $this->error['warning']
			));
		}

		// Insert success message from the session
		if (isset($this->session->data['success'])) {
			$this->pushAlert(array(
				'type' => 'success',
				'icon' => 'exclamation-circle',
				'text' => $this->session->data['success']
			));

			unset($this->session->data['success']);
		}

		if ($this->request->server['HTTPS']) {
			// Push the SSL reminder alert
			$this->pushAlert(array(
				'type' => 'info',
				'icon' => 'lock',
				'text' => $this->language->get('text_notification_ssl')
			));
		} else {
			// Push the SSL reminder alert
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => $this->language->get('error_no_ssl')
			));
		}

		$tabs = array(
			'tab-transaction',
			'tab-setting',
			'tab-recurring',
			'tab-cron'
		);

		if (isset($this->request->get['tab']) && in_array($this->request->get['tab'], $tabs)) {
			$data['tab'] = $this->request->get['tab'];
		} else if (isset($this->error['cron_email']) || isset($this->error['cron_acknowledge'])) {
			$data['tab'] = 'tab-cron';
		} else if ($this->error) {
			$data['tab'] = 'tab-setting';
		} else {
			$data['tab'] = $tabs[1];
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
			'href' => $this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = html_entity_decode($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true));
		$data['action_save_auth'] = html_entity_decode($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'] . '&save_and_auth=1', true));
		$data['cancel'] = html_entity_decode($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		$data['url_list_payments'] = html_entity_decode($this->url->link('extension/payment/squareup/payments', 'user_token=' . $this->session->data['user_token'] . '&page={PAGE}', true));

		$this->load->model('localisation/language');
		$data['languages'] = array();
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$data['languages'][] = array(
				'language_id' => $language['language_id'],
				'name' => $language['name'] . ($language['code'] == $this->config->get('config_language') ? $this->language->get('text_default') : ''),
				'image' => 'language/' . $language['code'] . '/'. $language['code'] . '.png'
			);
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['payment_squareup_cron_command'] = PHP_BINDIR . '/php -d session.save_path=' . session_save_path() . ' ' . DIR_SYSTEM . 'library/squareup/cron.php ' . parse_url($server, PHP_URL_HOST) . ' 443 > /dev/null 2> /dev/null';
		
		if (!$this->config->get('payment_squareup_cron_token')) {
			$data['payment_squareup_cron_token'] = md5(mt_rand());
		}

		$data['payment_squareup_cron_url'] = 'https://' . parse_url($server, PHP_URL_HOST) . dirname(parse_url($server, PHP_URL_PATH)) . '/index.php?route=extension/recurring/squareup/recurring&cron_token={CRON_TOKEN}';

		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
			$session = new Session($this->config->get('session_engine'), $this->registry);
			
			$session->start();
					
			$this->model_user_api->deleteApiSessionBySessionId($session->getId());
			
			$this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
			
			$session->data['api_id'] = $api_info['api_id'];

			$data['api_token'] = $session->getId();
		} else {
			$data['api_token'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['alerts'] = $this->pullAlerts();

		$this->clearAlerts();

		$this->response->setOutput($this->load->view('extension/payment/squareup', $data));
	}

	public function payment_info() {
		$this->load->language('extension/payment/squareup');

		$this->load->model('extension/payment/squareup');

		$this->load->library('squareup');

		if (isset($this->request->get['squareup_payment_id'])) {
			$squareup_payment_id = $this->request->get['squareup_payment_id'];
		} else {
			$squareup_payment_id = 0;
		}

		$payment_info = $this->model_extension_payment_squareup->getPayment($squareup_payment_id);

		if (empty($payment_info)) {
			$this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->document->setTitle(sprintf($this->language->get('heading_title_payment'), $payment_info['payment_id']));

		$data['alerts'] = $this->pullAlerts();

		$this->clearAlerts();

		$data['text_edit'] = sprintf($this->language->get('heading_title_payment'), $payment_info['payment_id']);

		$amount = $this->squareup->standardDenomination($payment_info['amount'], $payment_info['currency']);
		$amount = $this->currency->format($amount, $payment_info['currency'], 1);

		$refunded_currency = empty($payment_info['refunded_currency']) ? $payment_info['currency'] : $payment_info['refunded_currency'];
		$refunded_amount = $this->squareup->standardDenomination($payment_info['refunded_amount'], $refunded_currency);
		$refunded_amount = $this->currency->format($refunded_amount, $refunded_currency, 1);

		$data['is_fully_refunded'] = ($refunded_amount==$amount) ? true : false;

		$data['confirm_capture'] = sprintf($this->language->get('text_confirm_capture'), $amount);
		$data['confirm_void'] = sprintf($this->language->get('text_confirm_void'), $amount);
		$data['confirm_refund'] = $this->language->get('text_confirm_refund');
		$data['insert_amount'] = sprintf($this->language->get('text_insert_amount'), $amount, $payment_info['currency']);
		$data['text_loading'] = $this->language->get('text_loading_short');

		$data['opencart_order_id'] = $payment_info['opencart_order_id'];
		$data['payment_id'] = $payment_info['payment_id'];
		$data['merchant_id'] = $payment_info['merchant_id'];
		$data['location_id'] = $payment_info['location_id'];
		$data['order_id'] = $payment_info['order_id'];
		$data['customer_id'] = $payment_info['customer_id'];
		$data['status'] = $payment_info['status'];
		$data['source_type'] = $payment_info['source_type'];
		$data['amount'] = $amount;
		$data['currency'] = $payment_info['currency'];
		$data['square_product'] = $payment_info['square_product'];
		$data['application_id'] = $payment_info['application_id'];
		$data['refunded_amount'] = $refunded_amount;
		$data['refunded_currency'] = $refunded_currency;
		$data['card_fingerprint'] = $payment_info['card_fingerprint'];
		$data['first_name'] = $payment_info['first_name'];
		$data['last_name'] = $payment_info['last_name'];
		$data['address_line_1'] = $payment_info['address_line_1'];
		$data['address_line_2'] = $payment_info['address_line_2'];
		$data['address_line_3'] = $payment_info['address_line_3'];
		$data['locality'] = $payment_info['locality'];
		$data['sublocality'] = $payment_info['sublocality'];
		$data['sublocality_2'] = $payment_info['sublocality_2'];
		$data['sublocality_3'] = $payment_info['sublocality_3'];
		$data['administrative_district_level_1'] = $payment_info['administrative_district_level_1'];
		$data['administrative_district_level_2'] = $payment_info['administrative_district_level_2'];
		$data['administrative_district_level_3'] = $payment_info['administrative_district_level_3'];
		$data['postal_code'] = $payment_info['postal_code'];
		$data['country'] = $payment_info['country'];
		$data['user_agent'] = $payment_info['user_agent'];
		$data['ip'] = $payment_info['ip'];
		$data['created_at'] = date($this->language->get('datetime_format'), strtotime($payment_info['created_at']));
		$data['updated_at'] = date($this->language->get('datetime_format'), strtotime($payment_info['updated_at']));
		$data['cancel'] = $this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'] . '&tab=tab-payment', true);
		$data['url_order'] = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $payment_info['opencart_order_id'], true);
		$data['url_void'] = $this->url->link('extension/payment/squareup' . '/cancel', 'user_token=' . $this->session->data['user_token'] . '&preserve_alert=true&squareup_payment_id=' . $payment_info['squareup_payment_id'], true);
		$data['url_capture'] = $this->url->link('extension/payment/squareup' . '/capture', 'user_token=' . $this->session->data['user_token'] . '&preserve_alert=true&squareup_payment_id=' . $payment_info['squareup_payment_id'], true);
		$data['url_refund'] = $this->url->link('extension/payment/squareup' . '/refund', 'user_token=' . $this->session->data['user_token'] . '&preserve_alert=true&squareup_payment_id=' . $payment_info['squareup_payment_id'], true);
		$data['url_refresh'] = $this->url->link('extension/payment/squareup' . '/refresh', 'user_token=' . $this->session->data['user_token'] . '&preserve_alert=true&squareup_payment_id=' . $payment_info['squareup_payment_id'], true);
		$data['is_authorized'] = in_array($payment_info['status'], array('APPROVED'));
		$data['is_captured'] = in_array($payment_info['status'], array('COMPLETED'));

		$data['payment_squareup_quick_pay'] = $this->config->get('payment_squareup_quick_pay');

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
			'href' => $this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => sprintf($this->language->get('heading_title_payment'), $payment_info['squareup_payment_id']),
			'href' => $this->url->link('extension/payment/squareup/payment_info', 'user_token=' . $this->session->data['user_token'] . '&squareup_payment_id=' . $squareup_payment_id, true)
		);

		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
			$session = new Session($this->config->get('session_engine'), $this->registry);
			
			$session->start();
					
			$this->model_user_api->deleteApiSessionBySessionId($session->getId());
			
			$this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
			
			$session->data['api_id'] = $api_info['api_id'];

			$data['api_token'] = $session->getId();
		} else {
			$data['api_token'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/squareup_payment_info', $data));
	}

	public function payments() {
		$this->load->library('squareup');
		$this->load->language('extension/payment/squareup','squareup');

		$this->load->model('extension/payment/squareup');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$result = array(
			'payments' => array(),
			'pagination' => ''
		);

		$filter_data = array(
			'start' => ($page - 1) * (int)$this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		if (isset($this->request->get['order_id'])) {
			$filter_data['order_id'] = $this->request->get['order_id'];
		}

		$payments_total = $this->model_extension_payment_squareup->getTotalPayments($filter_data);
		$payments = $this->model_extension_payment_squareup->getPayments($filter_data);

		$this->load->model('sale/order');

		foreach ($payments as $payment) {
			$amount = $this->squareup->standardDenomination($payment['amount'],$payment['currency']);
			$amount = $this->currency->format($amount, $payment['currency'], 1);
			$refunded_currency = empty($payment['refunded_currency']) ? $payment['currency'] : $payment['refunded_currency'];
			$refunded_amount = $this->squareup->standardDenomination($payment['refunded_amount'],$refunded_currency);
			$refunded_amount = $this->currency->format($refunded_amount, $refunded_currency, 1);

			$order_info = $this->model_sale_order->getOrder($payment['opencart_order_id']);
			
			$result['payments'][] = array(
				'squareup_payment_id' => $payment['squareup_payment_id'],
				'payment_id' => $payment['payment_id'],
				'url_order' => $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $payment['opencart_order_id'], true),
				'url_void' => $this->url->link('extension/payment/squareup/cancel', 'user_token=' . $this->session->data['user_token'] . '&squareup_payment_id=' . $payment['squareup_payment_id'], true),
				'url_capture' => $this->url->link('extension/payment/squareup/capture', 'user_token=' . $this->session->data['user_token'] . '&squareup_payment_id=' . $payment['squareup_payment_id'], true),
				'url_refund' => $this->url->link('extension/payment/squareup/refund', 'user_token=' . $this->session->data['user_token'] . '&squareup_payment_id=' . $payment['squareup_payment_id'], true),
				'url_refresh' => $this->url->link('extension/payment/squareup/refresh', 'user_token=' . $this->session->data['user_token'] . '&squareup_payment_id=' . $payment['squareup_payment_id'], true),
				'confirm_capture' => sprintf($this->language->get('squareup')->get('text_confirm_capture'), $amount),
				'confirm_void' => sprintf($this->language->get('squareup')->get('text_confirm_void'), $amount),
				'confirm_refund' => $this->language->get('squareup')->get('text_confirm_refund'),
				'confirm_refresh' => $this->language->get('squareup')->get('text_confirm_refresh'),
				'insert_amount' => sprintf($this->language->get('squareup')->get('text_insert_amount'), $amount, $payment['currency']),
				'order_id' => $payment['opencart_order_id'],
				'source_type' => $payment['source_type'],
				'status' => $payment['status'],
				'amount' => $amount,
				'refunded_amount' => $refunded_amount,
				'customer' => empty($order_info) ? '' : $order_info['firstname'] . ' ' . $order_info['lastname'],
				'ip' => $payment['ip'],
				'date_created' => date($this->language->get('datetime_format'), strtotime($payment['created_at'])),
				'date_updated' => date($this->language->get('datetime_format'), strtotime($payment['updated_at'])),
				'url_info' => $this->url->link('extension/payment/squareup/payment_info', 'user_token=' . $this->session->data['user_token'] . '&squareup_payment_id=' . $payment['squareup_payment_id'], true)
			);
		}

		$pagination = new Pagination();
		$pagination->total = $payments_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = '{page}';

		$result['pagination'] = $pagination->render();

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($result));
	}

	public function refresh_token() {
		$this->load->language('extension/payment/squareup');

		if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => $this->language->get('error_permission')
			));

			$this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->load->model('setting/setting');

		$this->load->library('squareup');

		try {
			$response = $this->squareup->refreshToken();

			if (!isset($response['access_token']) || !isset($response['token_type']) || !isset($response['expires_at']) || !isset($response['merchant_id']) ||
				$response['merchant_id'] != $this->config->get('payment_squareup_merchant_id')) {
				$this->pushAlert(array(
					'type' => 'danger',
					'icon' => 'exclamation-circle',
					'text' => $this->language->get('error_refresh_access_token') 
				));
			} else {
				$settings = $this->model_setting_setting->getSetting('payment_squareup');

				$settings['payment_squareup_access_token'] = $response['access_token']; 
				$settings['payment_squareup_access_token_expires'] = $response['expires_at'];

				$this->model_setting_setting->editSetting('payment_squareup', $settings); 

				$this->pushAlert(array(
					'type' => 'success',
					'icon' => 'exclamation-circle',
					'text' => $this->language->get('text_refresh_access_token_success')
				));
			}
		} catch (\Squareup\Exception $e) {
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => sprintf($this->language->get('error_token'), $e->getMessage())
			));
		}

		$this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function oauth_callback() {
		$this->load->language('extension/payment/squareup');

		if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => $this->language->get('error_permission')
			));

			$this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->load->model('setting/setting');

		$this->load->library('squareup');

		if (isset($this->request->get['error']) || isset($this->request->get['error_description'])) {
			// auth error
			if ($this->request->get['error'] == 'access_denied' && $this->request->get['error_description'] == 'user_denied') {
				// user rejected giving auth permissions to his store
				$this->pushAlert(array(
					'type' => 'warning',
					'icon' => 'exclamation-circle',
					'text' => $this->language->get('error_user_rejected_connect_attempt')
				));
			}

			$this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true));
		}

		// verify parameters for the redirect from Square (against random url crawling)
		if (!isset($this->request->get['state']) || !isset($this->request->get['code']) || !isset($this->request->get['response_type'])) {
			// missing or wrong info
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => $this->language->get('error_possible_xss')
			));

			$this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true));
		}

		// verify the state (against cross site requests)
		if (!isset($this->session->data['payment_squareup_oauth_state']) || $this->session->data['payment_squareup_oauth_state'] != $this->request->get['state']) {
			// state mismatch
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => $this->language->get('error_possible_xss')
			));

			$this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true));
		}

		try {
			$token = $this->squareup->exchangeCodeForAccessAndRefreshTokens($this->request->get['code']);
			
			$previous_setting = $this->model_setting_setting->getSetting('payment_squareup');

			$first_location_id = null;

			$previous_setting['payment_squareup_locations'] = $this->squareup->listLocations($token['access_token'], $first_location_id);

			if (!empty($previous_setting['payment_squareup_location_id'])) {
				$previous_setting['payment_squareup_location_id'] = $first_location_id;
			} else {
				$previous_location_ids = array();
				foreach ($previous_setting['payment_squareup_locations'] as $location) {
					$previous_location_ids[] = $location['id'];
				}
				if (!in_array($first_location_id,$previous_location_ids)) {
					$previous_setting['payment_squareup_location_id'] = $first_location_id;
				}
			}
			/*
			if (
				!isset($previous_setting['payment_squareup_location_id']) || 
				(isset($previous_setting['payment_squareup_location_id']) && !in_array(
					$previous_setting['payment_squareup_location_id'], 
					array_map(
						function($location) {
							return $location['id'];
						},
						$previous_setting['payment_squareup_locations']
					)
				))
			) {
				$previous_setting['payment_squareup_location_id'] = $first_location_id;
			}
			*/

//          if (!$this->config->get('payment_squareup_sandbox_locations') && $this->config->get('payment_squareup_sandbox_token')) {
			if ($this->config->get('payment_squareup_sandbox_token')) {
				$previous_setting['payment_squareup_sandbox_locations'] = $this->squareup->listLocations($this->config->get('payment_squareup_sandbox_token'), $first_location_id);
				$previous_setting['payment_squareup_sandbox_location_id'] = $first_location_id;
			}

			$previous_setting['payment_squareup_merchant_id'] = $token['merchant_id'];
			$previous_setting['payment_squareup_merchant_name'] = ''; // only available in v1 of the API, not populated for now
			$previous_setting['payment_squareup_access_token'] = $token['access_token'];
			$previous_setting['payment_squareup_refresh_token'] = $token['refresh_token'];
			$previous_setting['payment_squareup_access_token_expires'] = $token['expires_at'];

			$this->model_setting_setting->editSetting('payment_squareup', $previous_setting);

			unset($this->session->data['payment_squareup_oauth_state']);
			unset($this->session->data['payment_squareup_oauth_redirect']);

			$this->pushAlert(array(
				'type' => 'success',
				'icon' => 'exclamation-circle',
				'text' => $this->language->get('text_refresh_access_token_success')
			));
		} catch (\Squareup\Exception $e) {
			$this->pushAlert(array(
				'type' => 'danger',
				'icon' => 'exclamation-circle',
				'text' => sprintf($this->language->get('error_token'), $e->getMessage())
			));
		}

		$this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function capture() {
		$this->load->language('extension/payment/squareup');

		$this->load->model('extension/payment/squareup');

		$this->load->library('squareup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['squareup_payment_id'])) {
			$squareup_payment_id = $this->request->get['squareup_payment_id'];
		} else {
			$squareup_payment_id = 0;
		}

		$payment_info = $this->model_extension_payment_squareup->getPayment($squareup_payment_id);

		if (empty($payment_info)) {
			$json['error'] = $this->language->get('error_payment_missing');
		} else {
			try {
				if ($this->config->get('payment_squareup_enable_sandbox')) {
					$access_token = $this->config->get('payment_squareup_sandbox_token');
				} else {
					$access_token = $this->config->get('payment_squareup_access_token');
				}

				$payment_id = $payment_info['payment_id'];

				if (empty($json['error'])) {
					$payment = $this->squareup->completePayment($access_token,$payment_id);
					if (!empty($payment['payment'])) {
						$updated_at = $payment['payment']['updated_at'];
						$status = $payment['payment']['status'];
						
						$this->model_extension_payment_squareup->updatePaymentStatus($squareup_payment_id, $status, $updated_at);
						$json['success'] = $this->language->get('text_success_capture');
						$json['order_history_data'] = array(
							'notify' => 1,
							'order_id' => $payment_info['opencart_order_id'],
							'order_status_id' => $this->config->get('payment_squareup_status_captured'),
							'comment' => '',
						);
					} else {
						$json['error'] = $this->language->get('error_capture_payment');
					}
				}

			} catch (\Squareup\Exception $e) {
				$json['error'] = $e->getMessage();
			}
		}

		if (isset($this->request->get['preserve_alert'])) {
			if (!empty($json['error'])) {
				$this->pushAlert(array(
					'type' => 'danger',
					'icon' => 'exclamation-circle',
					'text' => $json['error']
				));
			}

			if (!empty($json['success'])) {
				$this->pushAlert(array(
					'type' => 'success',
					'icon' => 'exclamation-circle',
					'text' => $json['success']
				));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function cancel() {
		$this->load->language('extension/payment/squareup');

		$this->load->model('extension/payment/squareup');

		$this->load->library('squareup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['squareup_payment_id'])) {
			$squareup_payment_id = $this->request->get['squareup_payment_id'];
		} else {
			$squareup_payment_id = 0;
		}

		$payment_info = $this->model_extension_payment_squareup->getPayment($squareup_payment_id);

		if (empty($payment_info)) {
			$json['error'] = $this->language->get('error_payment_missing');
		} else {
			try {
				if ($this->config->get('payment_squareup_enable_sandbox')) {
					$access_token = $this->config->get('payment_squareup_sandbox_token');
				} else {
					$access_token = $this->config->get('payment_squareup_access_token');
				}

				$payment_id = $payment_info['payment_id'];

				if (empty($json['error'])) {
					$payment = $this->squareup->cancelPayment($access_token,$payment_id);
					if (!empty($payment['payment'])) {
						$updated_at = $payment['payment']['updated_at'];
						$status = $payment['payment']['status'];
						$this->model_extension_payment_squareup->updatePaymentStatus($squareup_payment_id, $status, $updated_at);
						$json['success'] = $this->language->get('text_success_void');
						$json['order_history_data'] = array(
							'notify' => 1,
							'order_id' => $payment_info['opencart_order_id'],
							'order_status_id' => $this->config->get('payment_squareup_status_voided'),
							'comment' => '',
						);
					} else {
						$json['error'] = $this->language->get('error_cancel_payment');
					}
				}

			} catch (\Squareup\Exception $e) {
				$json['error'] = $e->getMessage();
			}
		}

		if (isset($this->request->get['preserve_alert'])) {
			if (!empty($json['error'])) {
				$this->pushAlert(array(
					'type' => 'danger',
					'icon' => 'exclamation-circle',
					'text' => $json['error']
				));
			}

			if (!empty($json['success'])) {
				$this->pushAlert(array(
					'type' => 'success',
					'icon' => 'exclamation-circle',
					'text' => $json['success']
				));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function refund() {
		$this->load->language('extension/payment/squareup');

		$this->load->model('extension/payment/squareup');

		$this->load->library('squareup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['squareup_payment_id'])) {
			$squareup_payment_id = $this->request->get['squareup_payment_id'];
		} else {
			$squareup_payment_id = 0;
		}

		$payment_info = $this->model_extension_payment_squareup->getPayment($squareup_payment_id);

		if (empty($payment_info)) {
			$json['error'] = $this->language->get('error_payment_missing');
		} else {
			try {
				if (!empty($this->request->post['reason'])) {
					$reason = $this->request->post['reason'];
				} else {
					$reason = $this->language->get('text_no_reason_provided');
				}

				if (!empty($this->request->post['amount'])) {
					$amount = preg_replace('~[^0-9\.\,]~', '', $this->request->post['amount']);

					if (strpos($amount, ',') !== false && strpos($amount, '.') !== false) {
						$amount = (float)str_replace(',', '', $amount);
					} else if (strpos($amount, ',') !== false && strpos($amount, '.') === false) {
						$amount = (float)str_replace(',', '.', $amount);
					} else {
						$amount = (float)$amount;
					}
				} else {
					$amount = 0;
				}

				$currency = $payment_info['currency'];

				if ($this->config->get('payment_squareup_enable_sandbox')) {
					$access_token = $this->config->get('payment_squareup_sandbox_token');
				} else {
					$access_token = $this->config->get('payment_squareup_access_token');
				}

				$payment_id = $payment_info['payment_id'];
				
				// double check the amount, it must not succeed amount - refunded_amount
				$paid_amount = $payment_info['amount'];
				$refunded_amount = $payment_info['refunded_amount'];
				$planned_amount = $this->squareup->lowestDenomination($amount,$currency);
				if ($planned_amount > $paid_amount - $refunded_amount) {
					$json['error'] = $this->language->get('error_refund_too_large');
				}

				if (empty($json['error'])) {
					$last_refund = $this->squareup->refundPayment($access_token, $payment_id, $amount, $currency, $reason);
					$updated_at = $last_refund['refund']['updated_at'];
					$new_refunded_amount = $refunded_amount + $last_refund['refund']['amount_money']['amount'];
					$this->model_extension_payment_squareup->updatePaymentRefund($squareup_payment_id, $updated_at, $new_refunded_amount);
					$json['success'] = $this->language->get('text_success_refund');

					$last_refunded_amount = $this->currency->format(
						$this->squareup->standardDenomination(
							$last_refund['refund']['amount_money']['amount'], 
							$last_refund['refund']['amount_money']['currency']
						), 
						$last_refund['refund']['amount_money']['currency'],
						1
					);

					$comment = sprintf($this->language->get('text_refunded_amount'), $last_refunded_amount, $last_refund['refund']['status'], $last_refund['refund']['reason']);

					$json['order_history_data'] = array(
						'notify' => 1,
						'order_id' => $payment_info['opencart_order_id'],
						'order_status_id' => $this->model_extension_payment_squareup->getOrderStatusId($payment_info['opencart_order_id']),
						'comment' => $comment,
					);

				}

			} catch (\Squareup\Exception $e) {
				$json['error'] = $e->getMessage();
			}
		}

		if (isset($this->request->get['preserve_alert'])) {
			if (!empty($json['error'])) {
				$this->pushAlert(array(
					'type' => 'danger',
					'icon' => 'exclamation-circle',
					'text' => $json['error']
				));
			}

			if (!empty($json['success'])) {
				$this->pushAlert(array(
					'type' => 'success',
					'icon' => 'exclamation-circle',
					'text' => $json['success']
				));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function refresh() {
		$this->load->language('extension/payment/squareup');

		$this->load->model('extension/payment/squareup');

		$this->load->library('squareup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['squareup_payment_id'])) {
			$squareup_payment_id = $this->request->get['squareup_payment_id'];
		} else {
			$squareup_payment_id = 0;
		}

		$payment_info = $this->model_extension_payment_squareup->getPayment($squareup_payment_id);

		if (empty($payment_info)) {
			$json['error'] = $this->language->get('error_payment_missing');
		} else {
			try {
				if ($this->config->get('payment_squareup_enable_sandbox')) {
					$access_token = $this->config->get('payment_squareup_sandbox_token');
				} else {
					$access_token = $this->config->get('payment_squareup_access_token');
				}

				$payment_id = $payment_info['payment_id'];

				if (empty($json['error'])) {
					$payment = $this->squareup->getPayment($access_token,$payment_id);
					if (!empty($payment['payment'])) {
						$status = $payment['payment']['status'];
						$this->model_extension_payment_squareup->updatePayment($squareup_payment_id, $payment);
						$json['success'] = $this->language->get('text_success_refresh');
						$json['order_history_data'] = array(
							'notify' => 0,
							'order_id' => $payment_info['opencart_order_id'],
							'order_status_id' => $this->paymentStatusToOrderStatus($status),
							'comment' => '',
						);
					} else {
						$json['error'] = $this->language->get('error_refresh_payment');
					}
				}

			} catch (\Squareup\Exception $e) {
				$json['error'] = $e->getMessage();
			}
		}

		$payment = $this->squareup->getPayment($access_token,$payment_id);

		if (isset($this->request->get['preserve_alert'])) {
			if (!empty($json['error'])) {
				$this->pushAlert(array(
					'type' => 'danger',
					'icon' => 'exclamation-circle',
					'text' => $json['error']
				));
			}

			if (!empty($json['success'])) {
				$this->pushAlert(array(
					'type' => 'success',
					'icon' => 'exclamation-circle',
					'text' => $json['success']
				));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function order() {
		$this->load->language('extension/payment/squareup');

		$data['url_list_payments'] = html_entity_decode($this->url->link('extension/payment/squareup/payments', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'] . '&page={PAGE}', true));
		$data['user_token'] = $this->session->data['user_token'];
		$data['order_id'] = (int)$this->request->get['order_id'];

		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
			$session = new Session($this->config->get('session_engine'), $this->registry);
			
			$session->start();
					
			$this->model_user_api->deleteApiSessionBySessionId($session->getId());
			
			$this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
			
			$session->data['api_id'] = $api_info['api_id'];

			$data['api_token'] = $session->getId();
		} else {
			$data['api_token'] = '';
		}

		return $this->load->view('extension/payment/squareup_order', $data);
	}

	protected function registerEventHandlers() {
		$this->load->model('setting/event');
		$code = 'payment_squareup';
		$app ='catalog/';
		$route = 'extension/payment/squareup/eventViewCheckoutConfirmAfter';
		$trigger = 'view/checkout/confirm/after';
		$this->model_setting_event->addEvent( $code, $app.$trigger, $route );
	}

	protected function removeEventHandlers() {
		$this->load->model('setting/event');
		$code = 'payment_squareup';
		$this->model_setting_event->deleteEventByCode( $code );
	}

	public function install() {
		$this->load->model('extension/payment/squareup');
		
		$this->model_extension_payment_squareup->createTables();

		$this->registerEventHandlers();
	}

	public function uninstall() {
		$this->load->model('extension/payment/squareup');

		$this->model_extension_payment_squareup->dropTables();

		$this->removeEventHandlers();
	}

	public function recurringButtons() {
		if (!$this->user->hasPermission('modify', 'sale/recurring')) {
			return;
		}

		$this->load->model('extension/payment/squareup');

		$this->load->language('extension/payment/squareup');

		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}

		$recurring_info = $this->model_sale_recurring->getRecurring($order_recurring_id);

		$data['button_text'] = $this->language->get('button_cancel_recurring');

		if ($recurring_info['status'] == ModelExtensionPaymentSquareup::RECURRING_ACTIVE) {
			$data['order_recurring_id'] = $order_recurring_id;
		} else {
			$data['order_recurring_id'] = '';
		}

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($recurring_info['order_id']);

		$data['order_id'] = $recurring_info['order_id'];
		$data['store_id'] = $order_info['store_id'];
		$data['order_status_id'] = $order_info['order_status_id'];
		$data['comment'] = $this->language->get('text_order_history_cancel');
		$data['notify'] = 1;

		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
			$session = new Session($this->config->get('session_engine'), $this->registry);
			
			$session->start();
					
			$this->model_user_api->deleteApiSessionBySessionId($session->getId());
			
			$this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
			
			$session->data['api_id'] = $api_info['api_id'];

			$data['api_token'] = $session->getId();
		} else {
			$data['api_token'] = '';
		}

		$data['cancel'] = html_entity_decode($this->url->link('extension/payment/squareup/recurringCancel', 'order_recurring_id=' . $order_recurring_id . '&user_token=' . $this->session->data['user_token'], true));

		return $this->load->view('extension/payment/squareup_recurring_buttons', $data);
	}

	public function recurringCancel() {
		$this->load->language('extension/payment/squareup');

		$json = array();
		
		if (!$this->user->hasPermission('modify', 'sale/recurring')) {
			$json['error'] = $this->language->get('error_permission_recurring');
		} else {
			$this->load->model('sale/recurring');
			
			if (isset($this->request->get['order_recurring_id'])) {
				$order_recurring_id = $this->request->get['order_recurring_id'];
			} else {
				$order_recurring_id = 0;
			}
			
			$recurring_info = $this->model_sale_recurring->getRecurring($order_recurring_id);

			if ($recurring_info) {
				$this->load->model('extension/payment/squareup');

				$this->model_extension_payment_squareup->editOrderRecurringStatus($order_recurring_id, ModelExtensionPaymentSquareup::RECURRING_CANCELLED);

				$json['success'] = $this->language->get('text_canceled_success');
				
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['payment_squareup_client_id'])) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}

		if (empty($this->request->post['payment_squareup_client_secret'])) {
			$this->error['client_secret'] = $this->language->get('error_client_secret');
		}

		if (!empty($this->request->post['payment_squareup_enable_sandbox'])) {
			if (empty($this->request->post['payment_squareup_sandbox_client_id'])) {
				$this->error['sandbox_client_id'] = $this->language->get('error_sandbox_client_id');
			}

			if (empty($this->request->post['payment_squareup_sandbox_token'])) {
				$this->error['sandbox_token'] = $this->language->get('error_sandbox_token');
			}

			if ($this->config->get('payment_squareup_merchant_id') && !$this->config->get('payment_squareup_sandbox_locations')) {
				$this->error['warning'] = $this->language->get('text_no_appropriate_locations_warning');
			}

			if ($this->config->get('payment_squareup_sandbox_locations')) {
				if (isset($this->request->post['payment_squareup_sandbox_location_id'])) {
					$location_ids = array();
					foreach ($this->config->get('payment_squareup_sandbox_locations') as $location) {
						$location_ids[] = $location['id'];
					}
					if (!in_array($this->request->post['payment_squareup_sandbox_location_id'],$location_ids)) {
						$this->error['location'] = $this->language->get('error_no_location_selected');
					}
				}
			}
			/*
			if ($this->config->get('payment_squareup_sandbox_locations') && isset($this->request->post['payment_squareup_sandbox_location_id']) && !in_array($this->request->post['payment_squareup_sandbox_location_id'], array_map(function($location) {
				return $location['id'];
			}, $this->config->get('payment_squareup_sandbox_locations')))) {
				$this->error['location'] = $this->language->get('error_no_location_selected');
			}
			*/
		} else {
			if ($this->config->get('payment_squareup_merchant_id') && !$this->config->get('payment_squareup_locations')) {
				$this->error['warning'] = $this->language->get('text_no_appropriate_locations_warning');
			}

			if ($this->config->get('payment_squareup_locations')) {
				if (isset($this->request->post['payment_squareup_location_id'])) {
					$location_ids = array();
					foreach ($this->config->get('payment_squareup_locations') as $location) {
						$location_ids[] = $location['id'];
					}
					if (!in_array($this->request->post['payment_squareup_location_id'],$location_ids)) {
						$this->error['location'] = $this->language->get('error_no_location_selected');
					}
				}
				
			}

			/*
			if ($this->config->get('payment_squareup_locations') && 
				isset($this->request->post['payment_squareup_location_id']) && 
				!in_array(
					$this->request->post['payment_squareup_location_id'], 
					array_map(function($location) {
						return $location['id'];
					}, 
					$this->config->get('payment_squareup_locations'))
				)
			) {
				$this->error['location'] = $this->language->get('error_no_location_selected');
			}
			*/
		}

		if (!empty($this->request->post['payment_squareup_cron_email_status'])) {
			if (!filter_var($this->request->post['payment_squareup_cron_email'], FILTER_VALIDATE_EMAIL)) {
				$this->error['cron_email'] = $this->language->get('error_invalid_email');
			}
		}

		if (!isset($this->request->get['save_and_auth']) && empty($this->request->post['payment_squareup_cron_acknowledge'])) {
			$this->error['cron_acknowledge'] = $this->language->get('error_cron_acknowledge');
		}

		if ($this->error && empty($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_form');
		}

		return !$this->error;
	}
    
	protected function paymentStatusToOrderStatus($payment_status) {
		switch ($payment_status) {
			case 'APPROVED':
				return $this->config->get('payment_squareup_status_authorized');
			case 'PENDING':
				if ($this->config->get('payment_squareup_delay_capture')) {
					return $this->config->get('payment_squareup_status_authorized');
				} else {
					return $this->config->get('payment_squareup_status_captured');
				}
			case 'COMPLETED':
				return $this->config->get('payment_squareup_status_captured');
			case 'CANCELED':
				return $this->config->get('payment_squareup_status_voided');
			case 'FAILED': 
				return $this->config->get('payment_squareup_status_failed');
			default:
				return 0;
		}
	}

	protected function pushAlert($alert) {
		$this->session->data['payment_squareup_alerts'][] = $alert;
	}

	protected function pullAlerts() {
		if (isset($this->session->data['payment_squareup_alerts'])) {
			return $this->session->data['payment_squareup_alerts'];
		} else {
			return array();
		}
	}

	protected function clearAlerts() {
		unset($this->session->data['payment_squareup_alerts']);
	}

	protected function getSettingValue($key, $default = null, $checkbox = false) {
		if ($checkbox) {
			if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post[$key])) {
				return $default;
			} else {
				return $this->config->get($key);
			}
		}

		if (isset($this->request->post[$key])) {
			return $this->request->post[$key]; 
		} else if ($this->config->has($key)) {
			return $this->config->get($key);
		} else {
			return $default;
		}
	}

	protected function getValidationError($key) {
		if (isset($this->error[$key])) {
			return $this->error[$key];
		} else {
			return '';
		}
	}
}
