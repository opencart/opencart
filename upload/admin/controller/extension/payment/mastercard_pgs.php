<?php
class ControllerExtensionPaymentMastercardPGS extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/mastercard_pgs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		$this->load->model('extension/payment/mastercard_pgs');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_mastercard_pgs', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
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

		$tabs = array(
			'tab-transaction',
			'tab-setting'
		);

		if (isset($this->request->get['tab']) && in_array($this->request->get['tab'], $tabs)) {
			$data['tab'] = $this->request->get['tab'];
		} else if ($this->error) {
			$data['tab'] = 'tab-setting';
		} else {
			$data['tab'] = $tabs[0];
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
			'href' => $this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'], true);
		$data['rehook_events'] = $this->url->link('extension/payment/mastercard_pgs/hook_events', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		$data['url_list_transactions'] = html_entity_decode($this->url->link('extension/payment/mastercard_pgs/transactions', 'user_token=' . $this->session->data['user_token'] . '&page={PAGE}', true));

		if (isset($this->request->post['payment_mastercard_pgs_total'])) {
			$data['payment_mastercard_pgs_total'] = $this->request->post['payment_mastercard_pgs_total'];
		} else {
			$data['payment_mastercard_pgs_total'] = $this->config->get('payment_mastercard_pgs_total');
		}

		if (isset($this->request->post['payment_mastercard_pgs_approved_authorization_order_status_id'])) {
			$data['payment_mastercard_pgs_approved_authorization_order_status_id'] = $this->request->post['payment_mastercard_pgs_approved_authorization_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_approved_authorization_order_status_id'] = $this->config->get('payment_mastercard_pgs_approved_authorization_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_approved_capture_order_status_id'])) {
			$data['payment_mastercard_pgs_approved_capture_order_status_id'] = $this->request->post['payment_mastercard_pgs_approved_capture_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_approved_capture_order_status_id'] = $this->config->get('payment_mastercard_pgs_approved_capture_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_approved_payment_order_status_id'])) {
			$data['payment_mastercard_pgs_approved_payment_order_status_id'] = $this->request->post['payment_mastercard_pgs_approved_payment_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_approved_payment_order_status_id'] = $this->config->get('payment_mastercard_pgs_approved_payment_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_approved_refund_order_status_id'])) {
			$data['payment_mastercard_pgs_approved_refund_order_status_id'] = $this->request->post['payment_mastercard_pgs_approved_refund_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_approved_refund_order_status_id'] = $this->config->get('payment_mastercard_pgs_approved_refund_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_approved_void_order_status_id'])) {
			$data['payment_mastercard_pgs_approved_void_order_status_id'] = $this->request->post['payment_mastercard_pgs_approved_void_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_approved_void_order_status_id'] = $this->config->get('payment_mastercard_pgs_approved_void_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_approved_verification_order_status_id'])) {
			$data['payment_mastercard_pgs_approved_verification_order_status_id'] = $this->request->post['payment_mastercard_pgs_approved_verification_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_approved_verification_order_status_id'] = $this->config->get('payment_mastercard_pgs_approved_verification_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_unspecified_failure_order_status_id'])) {
			$data['payment_mastercard_pgs_unspecified_failure_order_status_id'] = $this->request->post['payment_mastercard_pgs_unspecified_failure_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_unspecified_failure_order_status_id'] = $this->config->get('payment_mastercard_pgs_unspecified_failure_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_declined_order_status_id'])) {
			$data['payment_mastercard_pgs_declined_order_status_id'] = $this->request->post['payment_mastercard_pgs_declined_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_declined_order_status_id'] = $this->config->get('payment_mastercard_pgs_declined_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_timed_out_order_status_id'])) {
			$data['payment_mastercard_pgs_timed_out_order_status_id'] = $this->request->post['payment_mastercard_pgs_timed_out_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_timed_out_order_status_id'] = $this->config->get('payment_mastercard_pgs_timed_out_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_expired_card_order_status_id'])) {
			$data['payment_mastercard_pgs_expired_card_order_status_id'] = $this->request->post['payment_mastercard_pgs_expired_card_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_expired_card_order_status_id'] = $this->config->get('payment_mastercard_pgs_expired_card_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_insufficient_funds_order_status_id'])) {
			$data['payment_mastercard_pgs_insufficient_funds_order_status_id'] = $this->request->post['payment_mastercard_pgs_insufficient_funds_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_insufficient_funds_order_status_id'] = $this->config->get('payment_mastercard_pgs_insufficient_funds_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_acquirer_system_error_order_status_id'])) {
			$data['payment_mastercard_pgs_acquirer_system_error_order_status_id'] = $this->request->post['payment_mastercard_pgs_acquirer_system_error_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_acquirer_system_error_order_status_id'] = $this->config->get('payment_mastercard_pgs_acquirer_system_error_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_system_error_order_status_id'])) {
			$data['payment_mastercard_pgs_system_error_order_status_id'] = $this->request->post['payment_mastercard_pgs_system_error_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_system_error_order_status_id'] = $this->config->get('payment_mastercard_pgs_system_error_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_not_supported_order_status_id'])) {
			$data['payment_mastercard_pgs_not_supported_order_status_id'] = $this->request->post['payment_mastercard_pgs_not_supported_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_not_supported_order_status_id'] = $this->config->get('payment_mastercard_pgs_not_supported_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_declined_do_not_contact_order_status_id'])) {
			$data['payment_mastercard_pgs_declined_do_not_contact_order_status_id'] = $this->request->post['payment_mastercard_pgs_declined_do_not_contact_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_declined_do_not_contact_order_status_id'] = $this->config->get('payment_mastercard_pgs_declined_do_not_contact_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_aborted_order_status_id'])) {
			$data['payment_mastercard_pgs_aborted_order_status_id'] = $this->request->post['payment_mastercard_pgs_aborted_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_aborted_order_status_id'] = $this->config->get('payment_mastercard_pgs_aborted_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_blocked_order_status_id'])) {
			$data['payment_mastercard_pgs_blocked_order_status_id'] = $this->request->post['payment_mastercard_pgs_blocked_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_blocked_order_status_id'] = $this->config->get('payment_mastercard_pgs_blocked_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_cancelled_order_status_id'])) {
			$data['payment_mastercard_pgs_cancelled_order_status_id'] = $this->request->post['payment_mastercard_pgs_cancelled_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_cancelled_order_status_id'] = $this->config->get('payment_mastercard_pgs_cancelled_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_deferred_transaction_received_order_status_id'])) {
			$data['payment_mastercard_pgs_deferred_transaction_received_order_status_id'] = $this->request->post['payment_mastercard_pgs_deferred_transaction_received_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_deferred_transaction_received_order_status_id'] = $this->config->get('payment_mastercard_pgs_deferred_transaction_received_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_referred_order_status_id'])) {
			$data['payment_mastercard_pgs_referred_order_status_id'] = $this->request->post['payment_mastercard_pgs_referred_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_referred_order_status_id'] = $this->config->get('payment_mastercard_pgs_referred_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_authentication_failed_order_status_id'])) {
			$data['payment_mastercard_pgs_authentication_failed_order_status_id'] = $this->request->post['payment_mastercard_pgs_authentication_failed_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_authentication_failed_order_status_id'] = $this->config->get('payment_mastercard_pgs_authentication_failed_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_invalid_csc_order_status_id'])) {
			$data['payment_mastercard_pgs_invalid_csc_order_status_id'] = $this->request->post['payment_mastercard_pgs_invalid_csc_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_invalid_csc_order_status_id'] = $this->config->get('payment_mastercard_pgs_invalid_csc_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_lock_failure_order_status_id'])) {
			$data['payment_mastercard_pgs_lock_failure_order_status_id'] = $this->request->post['payment_mastercard_pgs_lock_failure_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_lock_failure_order_status_id'] = $this->config->get('payment_mastercard_pgs_lock_failure_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_submitted_order_status_id'])) {
			$data['payment_mastercard_pgs_submitted_order_status_id'] = $this->request->post['payment_mastercard_pgs_submitted_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_submitted_order_status_id'] = $this->config->get('payment_mastercard_pgs_submitted_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_not_enrolled_3d_secure_order_status_id'])) {
			$data['payment_mastercard_pgs_not_enrolled_3d_secure_order_status_id'] = $this->request->post['payment_mastercard_pgs_not_enrolled_3d_secure_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_not_enrolled_3d_secure_order_status_id'] = $this->config->get('payment_mastercard_pgs_not_enrolled_3d_secure_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_pending_order_status_id'])) {
			$data['payment_mastercard_pgs_pending_order_status_id'] = $this->request->post['payment_mastercard_pgs_pending_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_pending_order_status_id'] = $this->config->get('payment_mastercard_pgs_pending_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_exceeded_retry_limit_order_status_id'])) {
			$data['payment_mastercard_pgs_exceeded_retry_limit_order_status_id'] = $this->request->post['payment_mastercard_pgs_exceeded_retry_limit_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_exceeded_retry_limit_order_status_id'] = $this->config->get('payment_mastercard_pgs_exceeded_retry_limit_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_duplicate_batch_order_status_id'])) {
			$data['payment_mastercard_pgs_duplicate_batch_order_status_id'] = $this->request->post['payment_mastercard_pgs_duplicate_batch_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_duplicate_batch_order_status_id'] = $this->config->get('payment_mastercard_pgs_duplicate_batch_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_declined_avs_order_status_id'])) {
			$data['payment_mastercard_pgs_declined_avs_order_status_id'] = $this->request->post['payment_mastercard_pgs_declined_avs_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_declined_avs_order_status_id'] = $this->config->get('payment_mastercard_pgs_declined_avs_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_declined_csc_order_status_id'])) {
			$data['payment_mastercard_pgs_declined_csc_order_status_id'] = $this->request->post['payment_mastercard_pgs_declined_csc_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_declined_csc_order_status_id'] = $this->config->get('payment_mastercard_pgs_declined_csc_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_declined_avs_csc_order_status_id'])) {
			$data['payment_mastercard_pgs_declined_avs_csc_order_status_id'] = $this->request->post['payment_mastercard_pgs_declined_avs_csc_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_declined_avs_csc_order_status_id'] = $this->config->get('payment_mastercard_pgs_declined_avs_csc_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_declined_payment_plan_order_status_id'])) {
			$data['payment_mastercard_pgs_declined_payment_plan_order_status_id'] = $this->request->post['payment_mastercard_pgs_declined_payment_plan_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_declined_payment_plan_order_status_id'] = $this->config->get('payment_mastercard_pgs_declined_payment_plan_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_approved_pending_settlement_order_status_id'])) {
			$data['payment_mastercard_pgs_approved_pending_settlement_order_status_id'] = $this->request->post['payment_mastercard_pgs_approved_pending_settlement_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_approved_pending_settlement_order_status_id'] = $this->config->get('payment_mastercard_pgs_approved_pending_settlement_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_partially_approved_order_status_id'])) {
			$data['payment_mastercard_pgs_partially_approved_order_status_id'] = $this->request->post['payment_mastercard_pgs_partially_approved_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_partially_approved_order_status_id'] = $this->config->get('payment_mastercard_pgs_partially_approved_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_unknown_order_status_id'])) {
			$data['payment_mastercard_pgs_unknown_order_status_id'] = $this->request->post['payment_mastercard_pgs_unknown_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_unknown_order_status_id'] = $this->config->get('payment_mastercard_pgs_unknown_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_risk_rejected_order_status_id'])) {
			$data['payment_mastercard_pgs_risk_rejected_order_status_id'] = $this->request->post['payment_mastercard_pgs_risk_rejected_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_risk_rejected_order_status_id'] = $this->config->get('payment_mastercard_pgs_risk_rejected_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_risk_review_pending_order_status_id'])) {
			$data['payment_mastercard_pgs_risk_review_pending_order_status_id'] = $this->request->post['payment_mastercard_pgs_risk_review_pending_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_risk_review_pending_order_status_id'] = $this->config->get('payment_mastercard_pgs_risk_review_pending_order_status_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_risk_review_rejected_order_status_id'])) {
			$data['payment_mastercard_pgs_risk_review_rejected_order_status_id'] = $this->request->post['payment_mastercard_pgs_risk_review_rejected_order_status_id'];
		} else {
			$data['payment_mastercard_pgs_risk_review_rejected_order_status_id'] = $this->config->get('payment_mastercard_pgs_risk_review_rejected_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_mastercard_pgs_geo_zone_id'])) {
			$data['payment_mastercard_pgs_geo_zone_id'] = $this->request->post['payment_mastercard_pgs_geo_zone_id'];
		} else {
			$data['payment_mastercard_pgs_geo_zone_id'] = $this->config->get('payment_mastercard_pgs_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_mastercard_pgs_status'])) {
			$data['payment_mastercard_pgs_status'] = $this->request->post['payment_mastercard_pgs_status'];
		} else {
			$data['payment_mastercard_pgs_status'] = $this->config->get('payment_mastercard_pgs_status');
		}

		if (isset($this->request->post['payment_mastercard_pgs_sort_order'])) {
			$data['payment_mastercard_pgs_sort_order'] = $this->request->post['payment_mastercard_pgs_sort_order'];
		} else {
			$data['payment_mastercard_pgs_sort_order'] = $this->config->get('payment_mastercard_pgs_sort_order');
		}

		if (isset($this->request->post['payment_mastercard_pgs_gateway'])) {
			$data['payment_mastercard_pgs_gateway'] = $this->request->post['payment_mastercard_pgs_gateway'];
		} else {
			$data['payment_mastercard_pgs_gateway'] = $this->config->get('payment_mastercard_pgs_gateway');
		}

		if (isset($this->request->post['payment_mastercard_pgs_gateway_other'])) {
			$data['payment_mastercard_pgs_gateway_other'] = $this->request->post['payment_mastercard_pgs_gateway_other'];
		} else {
			$data['payment_mastercard_pgs_gateway_other'] = $this->config->get('payment_mastercard_pgs_gateway_other');
		}

		if (isset($this->error['gateway_other'])) {
			$data['error_gateway_other'] = $this->error['gateway_other'];
		} else {
			$data['error_gateway_other'] = '';
		}

		$data['gateways'] = array();

		$data['gateways'][] = array(
			'code' => 'na',
			'text' => $this->language->get('text_gateway_na')
		);

		$data['gateways'][] = array(
			'code' => 'eu',
			'text' => $this->language->get('text_gateway_eu')
		);

		$data['gateways'][] = array(
			'code' => 'ap',
			'text' => $this->language->get('text_gateway_ap')
		);

		$data['gateways'][] = array(
			'code' => 'other',
			'text' => $this->language->get('text_gateway_other')
		);

		if (isset($this->request->post['payment_mastercard_pgs_onclick'])) {
			$data['payment_mastercard_pgs_onclick'] = $this->request->post['payment_mastercard_pgs_onclick'];
		} else {
			$data['payment_mastercard_pgs_onclick'] = $this->config->get('payment_mastercard_pgs_onclick');
		}

		if (isset($this->request->post['payment_mastercard_pgs_checkout'])) {
			$data['payment_mastercard_pgs_checkout'] = $this->request->post['payment_mastercard_pgs_checkout'];
		} else {
			$data['payment_mastercard_pgs_checkout'] = $this->config->get('payment_mastercard_pgs_checkout');
		}

		if (isset($this->request->post['payment_mastercard_pgs_google_analytics_property_id'])) {
			$data['payment_mastercard_pgs_google_analytics_property_id'] = $this->request->post['payment_mastercard_pgs_google_analytics_property_id'];
		} else {
			$data['payment_mastercard_pgs_google_analytics_property_id'] = $this->config->get('payment_mastercard_pgs_google_analytics_property_id');
		}

		if (isset($this->request->post['payment_mastercard_pgs_merchant'])) {
			$data['payment_mastercard_pgs_merchant'] = $this->request->post['payment_mastercard_pgs_merchant'];
		} else {
			$data['payment_mastercard_pgs_merchant'] = $this->config->get('payment_mastercard_pgs_merchant');
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if (isset($this->request->post['payment_mastercard_pgs_notification_secret'])) {
			$data['payment_mastercard_pgs_notification_secret'] = $this->request->post['payment_mastercard_pgs_notification_secret'];
		} else {
			$data['payment_mastercard_pgs_notification_secret'] = $this->config->get('payment_mastercard_pgs_notification_secret');
		}

		if (isset($this->error['notification_secret'])) {
			$data['error_notification_secret'] = $this->error['notification_secret'];
		} else {
			$data['error_notification_secret'] = '';
		}

		if (isset($this->request->post['payment_mastercard_pgs_integration_password'])) {
			$data['payment_mastercard_pgs_integration_password'] = $this->request->post['payment_mastercard_pgs_integration_password'];
		} else {
			$data['payment_mastercard_pgs_integration_password'] = $this->config->get('payment_mastercard_pgs_integration_password');
		}

		if (isset($this->error['integration_password'])) {
			$data['error_integration_password'] = $this->error['integration_password'];
		} else {
			$data['error_integration_password'] = '';
		}

		if (isset($this->request->post['payment_mastercard_pgs_debug_log'])) {
			$data['payment_mastercard_pgs_debug_log'] = $this->request->post['payment_mastercard_pgs_debug_log'];
		} else {
			$data['payment_mastercard_pgs_debug_log'] = $this->config->get('payment_mastercard_pgs_debug_log');
		}

		if (isset($this->request->post['payment_mastercard_pgs_tokenize'])) {
			$data['payment_mastercard_pgs_tokenize'] = $this->request->post['payment_mastercard_pgs_tokenize'];
		} else {
			$data['payment_mastercard_pgs_tokenize'] = $this->config->get('payment_mastercard_pgs_tokenize');
		}

		if (isset($this->request->post['payment_mastercard_pgs_display_name'])) {
			$data['payment_mastercard_pgs_display_name'] = $this->request->post['payment_mastercard_pgs_display_name'];
		} else {
			$data['payment_mastercard_pgs_display_name'] = $this->config->get('payment_mastercard_pgs_display_name');
		}

		$data['default_display_name'] = $this->language->get('heading_title');

		$this->load->model('localisation/language');
		$data['languages'] = array();

		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$data['languages'][] = array(
				'language_id' => $language['language_id'],
				'name' => $language['name'] . ($language['code'] == $this->config->get('config_language') ? $this->language->get('text_default') : ''),
				'image' => 'language/' . $language['code'] . '/'. $language['code'] . '.png'
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/mastercard_pgs', $data));
	}

	public function transaction_info() {
		$this->load->language('extension/payment/mastercard_pgs');

		$this->load->model('extension/payment/mastercard_pgs');

		if (isset($this->request->get['mastercard_pgs_transaction_id'])) {
			$mastercard_pgs_transaction_id = $this->request->get['mastercard_pgs_transaction_id'];
		} else {
			$mastercard_pgs_transaction_id = 0;
		}

		$transaction_info = $this->model_extension_payment_mastercard_pgs->getTransaction($mastercard_pgs_transaction_id);

		if (empty($transaction_info)) {
			$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->document->setTitle(sprintf($this->language->get('heading_title_transaction'), $transaction_info['transaction_id']));

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['text_edit'] = sprintf($this->language->get('heading_title_transaction'), $transaction_info['transaction_id']);

		$data['text_loading'] = $this->language->get('text_loading_short');

		$data['transaction_id'] = $transaction_info['transaction_id'];
		$data['partner_solution_id'] = $transaction_info['partnerSolutionId'];
		$data['merchant'] = $transaction_info['merchant'];
		$data['order_id'] = $transaction_info['order_id'];
		$data['status'] = $transaction_info['response_gatewayCode'];
		$data['result'] = $transaction_info['result'];
		$data['type'] = $transaction_info['transaction_type'];
		$data['amount'] = $transaction_info['transaction_amount'];
		$data['currency'] = $transaction_info['transaction_currency'];
		$data['risk_code'] = $transaction_info['risk_response_gatewayCode'];
		$data['risk_score'] = $transaction_info['risk_response_totalScore'];
		$data['browser'] = $transaction_info['device_browser'];
		$data['ip'] = $transaction_info['device_ipAddress'];
		$data['api_version'] = $transaction_info['version'];
		$data['date_created'] = date($this->language->get('datetime_format'), strtotime($transaction_info['timeOfRecord']));
		
		$data['cancel'] = $this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'] . '&tab=tab-transaction', true);

		$data['url_order'] = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $transaction_info['order_id'], true);
		$data['url_void'] = $this->url->link('extension/payment/mastercard_pgs/void', 'user_token=' . $this->session->data['user_token'] . '&mastercard_pgs_transaction_id=' . $transaction_info['mastercard_pgs_transaction_id'], true);
		$data['url_capture'] = $this->url->link('extension/payment/mastercard_pgs/capture', 'user_token=' . $this->session->data['user_token'] . '&mastercard_pgs_transaction_id=' . $transaction_info['mastercard_pgs_transaction_id'], true);
		$data['url_refund'] = $this->url->link('extension/payment/mastercard_pgs/refund', 'user_token=' . $this->session->data['user_token'] . '&mastercard_pgs_transaction_id=' . $transaction_info['mastercard_pgs_transaction_id'], true);

		$data['is_authorized'] = in_array($transaction_info['transaction_type'], array('AUTHORIZATION', 'AUTHORIZATION_UPDATE'));
		$data['is_captured'] = in_array($transaction_info['transaction_type'], array('CAPTURE'));
		$data['is_payment'] = in_array($transaction_info['transaction_type'], array('PAYMENT'));

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
			'href' => $this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => sprintf($this->language->get('heading_title_transaction'), $transaction_info['transaction_id']),
			'href' => $this->url->link('extension/payment/mastercard_pgs/transaction_info', 'user_token=' . $this->session->data['user_token'] . '&mastercard_pgs_transaction_id=' . $mastercard_pgs_transaction_id, true)
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/mastercard_pgs_transaction_info', $data));
	}

	public function transactions() {
		$this->load->language('extension/payment/mastercard_pgs');

		$this->load->model('extension/payment/mastercard_pgs');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$result = array(
			'transactions' => array(),
			'pagination' => ''
		);

		$filter_data = array(
			'start' => $page - 1,
			'limit' => $this->config->get('config_limit_admin')
		);

        if (isset($this->request->get['order_id'])) {
            $filter_data['order_id'] = $this->request->get['order_id'];
        }

		$transactions_total = $this->model_extension_payment_mastercard_pgs->getTotalTransactions($filter_data);
		$transactions = $this->model_extension_payment_mastercard_pgs->getTransactions($filter_data);

		foreach ($transactions as $transaction) {
			$result['transactions'][] = array(
				'transaction_id' => $transaction['transaction_id'],
				'merchant' => $transaction['merchant'],
				'url_order' => $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $transaction['order_id'], true),
				'order_id' => $transaction['order_id'],
				'type' => $transaction['transaction_type'],
				'amount' => $this->currency->format($transaction['transaction_amount'], $transaction['transaction_currency']),
				'risk' => $transaction['risk_response_totalScore'],
				'ip' => $transaction['device_ipAddress'],
				'date_created' => date($this->language->get('datetime_format'), strtotime($transaction['timeOfRecord'])),
				'url_info' => $this->url->link('extension/payment/mastercard_pgs/transaction_info', 'user_token=' . $this->session->data['user_token'] . '&mastercard_pgs_transaction_id=' . $transaction['mastercard_pgs_transaction_id'], true)
			);
		}

		$pagination = new Pagination();
		$pagination->total = $transactions_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = '{page}';

		$result['pagination'] = $pagination->render();

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($result));
	}

	public function install() {
		$this->load->model('extension/payment/mastercard_pgs');

		$this->model_extension_payment_mastercard_pgs->createTables();

		$this->hook_events(true);
	}

	public function uninstall() {
		$this->load->model('extension/payment/mastercard_pgs');

		$this->model_extension_payment_mastercard_pgs->dropTables();
		$this->model_extension_payment_mastercard_pgs->unhookEvents();
	}

	public function hook_events($inline = false) {
		$this->load->language('extension/payment/mastercard_pgs');
		
		$this->load->model('extension/payment/mastercard_pgs');

		$this->model_extension_payment_mastercard_pgs->unhookEvents();
		$this->model_extension_payment_mastercard_pgs->hookEvents();

		if (!$inline) {
			$this->session->data['success'] = $this->language->get('text_success_events');

			$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'], true));
		}
	}

	public function capture() {
		$this->load->language('extension/payment/mastercard_pgs');
		
		$this->load->model('extension/payment/mastercard_pgs');

		if (isset($this->request->get['mastercard_pgs_transaction_id'])) {
			$mastercard_pgs_transaction_id = $this->request->get['mastercard_pgs_transaction_id'];
		} else {
			$mastercard_pgs_transaction_id = 0;
		}

		$transaction_info = $this->model_extension_payment_mastercard_pgs->getTransaction($mastercard_pgs_transaction_id);

		if (empty($transaction_info)) {
			$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'], true));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_capture()) {
			$api_data = array();
			$api_data['apiOperation'] = 'CAPTURE';
			$api_data['partnerSolutionId'] = $this->model_extension_payment_mastercard_pgs->getPartnerSolutionId();
			$api_data['transaction']['amount'] = $this->request->post['transaction_amount'];
			$api_data['transaction']['currency'] = $transaction_info['transaction_currency'];

			if ($new_transaction_id = $this->model_extension_payment_mastercard_pgs->findTransactionId($transaction_info['order_id'])) {
				$response = $this->model_extension_payment_mastercard_pgs->api('order/' . $transaction_info['order_id'] . '/transaction/' . $new_transaction_id, $api_data, 'PUT');

				if (!empty($response['result']) && $response['result'] == 'SUCCESS') {
					$this->session->data['success'] = $this->language->get('text_success_capture');

					$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'] . '&tab=tab-transaction', true));
				} else if (!empty($response['result']) && $response['result'] == 'ERROR' && $response['error']['cause'] == 'INVALID_REQUEST') {
					$this->error['warning'] = sprintf($this->language->get('error_api'), $response['error']['explanation']);
				} else {
					$this->error['warning'] = $this->language->get('error_unknown');
				}
			} else {
				$this->error['warning'] = $this->language->get('error_api_transaction');
			}
		}

		if (isset($this->error['warning'])) {
			$this->session->data['error_warning'] = $this->error['warning'];
		}

		$this->response->redirect($this->url->link('extension/payment/mastercard_pgs/transaction_info', 'user_token=' . $this->session->data['user_token'] . '&mastercard_pgs_transaction_id=' . $mastercard_pgs_transaction_id, true));
	}

	public function void() {
		$this->load->language('extension/payment/mastercard_pgs');
		
		$this->load->model('extension/payment/mastercard_pgs');

		if (isset($this->request->get['mastercard_pgs_transaction_id'])) {
			$mastercard_pgs_transaction_id = $this->request->get['mastercard_pgs_transaction_id'];
		} else {
			$mastercard_pgs_transaction_id = 0;
		}

		$transaction_info = $this->model_extension_payment_mastercard_pgs->getTransaction($mastercard_pgs_transaction_id);

		if (empty($transaction_info)) {
			$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'], true));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_void()) {
			$api_data = array();
			$api_data['apiOperation'] = 'VOID';
			$api_data['partnerSolutionId'] = $this->model_extension_payment_mastercard_pgs->getPartnerSolutionId();
			$api_data['transaction']['targetTransactionId'] = $transaction_info['transaction_id'];

			if ($new_transaction_id = $this->model_extension_payment_mastercard_pgs->findTransactionId($transaction_info['order_id'])) {
				$response = $this->model_extension_payment_mastercard_pgs->api('order/' . $transaction_info['order_id'] . '/transaction/' . $new_transaction_id, $api_data, 'PUT');

				if (!empty($response['result']) && $response['result'] == 'SUCCESS') {
					$this->session->data['success'] = $this->language->get('text_success_void');

					$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'] . '&tab=tab-transaction', true));
				} else if (!empty($response['result']) && $response['result'] == 'ERROR' && $response['error']['cause'] == 'INVALID_REQUEST') {
					$this->error['warning'] = sprintf($this->language->get('error_api'), $response['error']['explanation']);
				} else {
					$this->error['warning'] = $this->language->get('error_unknown');
				}
			} else {
				$this->error['warning'] = $this->language->get('error_api_transaction');
			}
		}

		if (isset($this->error['warning'])) {
			$this->session->data['error_warning'] = $this->error['warning'];
		}

		$this->response->redirect($this->url->link('extension/payment/mastercard_pgs/transaction_info', 'user_token=' . $this->session->data['user_token'] . '&mastercard_pgs_transaction_id=' . $mastercard_pgs_transaction_id, true));
	}

	public function refund() {
		$this->load->language('extension/payment/mastercard_pgs');
		
		$this->load->model('extension/payment/mastercard_pgs');

		if (isset($this->request->get['mastercard_pgs_transaction_id'])) {
			$mastercard_pgs_transaction_id = $this->request->get['mastercard_pgs_transaction_id'];
		} else {
			$mastercard_pgs_transaction_id = 0;
		}

		$transaction_info = $this->model_extension_payment_mastercard_pgs->getTransaction($mastercard_pgs_transaction_id);

		if (empty($transaction_info)) {
			$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'], true));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_refund()) {
			$api_data = array();
			$api_data['apiOperation'] = 'REFUND';
			$api_data['partnerSolutionId'] = $this->model_extension_payment_mastercard_pgs->getPartnerSolutionId();
			$api_data['transaction']['amount'] = $this->request->post['transaction_amount'];
			$api_data['transaction']['currency'] = $transaction_info['transaction_currency'];

			if ($new_transaction_id = $this->model_extension_payment_mastercard_pgs->findTransactionId($transaction_info['order_id'])) {
				$response = $this->model_extension_payment_mastercard_pgs->api('order/' . $transaction_info['order_id'] . '/transaction/' . $new_transaction_id, $api_data, 'PUT');

				if (!empty($response['result']) && $response['result'] == 'SUCCESS') {
					$this->session->data['success'] = $this->language->get('text_success_refund');

					$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'user_token=' . $this->session->data['user_token'] . '&tab=tab-transaction', true));
				} else if (!empty($response['result']) && $response['result'] == 'ERROR' && $response['error']['cause'] == 'INVALID_REQUEST') {
					$this->error['warning'] = sprintf($this->language->get('error_api'), $response['error']['explanation']);
				} else {
					$this->error['warning'] = $this->language->get('error_unknown');
				}
			} else {
				$this->error['warning'] = $this->language->get('error_api_transaction');
			}
		}

		if (isset($this->error['warning'])) {
			$this->session->data['error_warning'] = $this->error['warning'];
		}

		$this->response->redirect($this->url->link('extension/payment/mastercard_pgs/transaction_info', 'user_token=' . $this->session->data['user_token'] . '&mastercard_pgs_transaction_id=' . $mastercard_pgs_transaction_id, true));
	}

	public function delete_tokens($route, $args) {
		if (!empty($args[0]) && $this->user->hasPermission('modify', 'extension/payment/mastercard_pgs')) {
			$this->load->model('extension/payment/mastercard_pgs');
			$this->model_extension_payment_mastercard_pgs->deleteTokens($args[0]);
		}
	}

    public function order() {
        $this->load->language('extension/payment/mastercard_pgs');

        $data['url_list_transactions'] = html_entity_decode($this->url->link('extension/payment/mastercard_pgs/transactions', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'] . '&page={PAGE}', true));

        return $this->load->view('extension/payment/mastercard_pgs_order', $data);
    }

	protected function validate_capture() {
		if (!$this->user->hasPermission('modify', 'extension/payment/mastercard_pgs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$transaction_info = $this->model_extension_payment_mastercard_pgs->getTransaction($this->request->get['mastercard_pgs_transaction_id']);

		if (!in_array($transaction_info['transaction_type'], array('AUTHORIZATION', 'AUTHORIZATION_UPDATE'))) {
			$this->error['warning'] = $this->language->get('error_not_authorization');
		}

		$amount = !empty($this->request->post['transaction_amount']) ? round($this->request->post['transaction_amount'], 2) : 0;

		if (empty($amount)) {
			$this->error['warning'] = $this->language->get('error_invalid_amount');
		}

		return !$this->error;
	}

	protected function validate_void() {
		if (!$this->user->hasPermission('modify', 'extension/payment/mastercard_pgs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$transaction_info = $this->model_extension_payment_mastercard_pgs->getTransaction($this->request->get['mastercard_pgs_transaction_id']);

		if (!in_array($transaction_info['transaction_type'], array('AUTHORIZATION', 'AUTHORIZATION_UPDATE', 'CAPTURE'))) {
			$this->error['warning'] = $this->language->get('error_not_authorization');
		}

		return !$this->error;
	}

	protected function validate_refund() {
		if (!$this->user->hasPermission('modify', 'extension/payment/mastercard_pgs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$transaction_info = $this->model_extension_payment_mastercard_pgs->getTransaction($this->request->get['mastercard_pgs_transaction_id']);

		if (!in_array($transaction_info['transaction_type'], array('CAPTURE', 'PAYMENT'))) {
			$this->error['warning'] = $this->language->get('error_not_capture');
		}

		$amount = !empty($this->request->post['transaction_amount']) ? round($this->request->post['transaction_amount'], 2) : 0;

		if (empty($amount)) {
			$this->error['warning'] = $this->language->get('error_invalid_amount');
		}

		return !$this->error;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/mastercard_pgs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['payment_mastercard_pgs_merchant']) || strlen($this->request->post['payment_mastercard_pgs_merchant']) > 16) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (empty($this->request->post['payment_mastercard_pgs_notification_secret']) || strlen($this->request->post['payment_mastercard_pgs_notification_secret']) != 32) {
			$this->error['notification_secret'] = $this->language->get('error_notification_secret');
		}

		if (empty($this->request->post['payment_mastercard_pgs_integration_password']) || strlen($this->request->post['payment_mastercard_pgs_integration_password']) != 32) {
			$this->error['integration_password'] = $this->language->get('error_integration_password');
		}

		if ($this->request->post['payment_mastercard_pgs_gateway'] == 'other' && empty($this->request->post['payment_mastercard_pgs_gateway_other'])) {
			$this->error['gateway_other'] = $this->language->get('error_gateway_other');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}