<?php
class ControllerExtensionPaymentMastercardPGS extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/mastercard_pgs');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->request->server['HTTPS']) {
			$server = HTTPS_SERVER;
		} else {
			$server = HTTP_SERVER;
		}

		$this->document->addStyle($server . 'view/stylesheet/mastercard_pgs.css');

		$this->load->model('setting/setting');

		$this->load->model('extension/payment/mastercard_pgs');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mastercard_pgs', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['tab_setting'] = $this->language->get('tab_setting');
		$data['tab_transaction'] = $this->language->get('tab_transaction');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_lightbox'] = $this->language->get('text_lightbox');
		$data['text_hosted_payment_page'] = $this->language->get('text_hosted_payment_page');
		$data['text_notification_ssl'] = $this->language->get('text_notification_ssl');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_view'] = $this->language->get('text_view');
		$data['text_refresh'] = $this->language->get('text_refresh');
		$data['text_general_settings'] = $this->language->get('text_general_settings');
		$data['text_transaction_statuses'] = $this->language->get('text_transaction_statuses');
		$data['text_pay'] = $this->language->get('text_pay');
		$data['text_authorize'] = $this->language->get('text_authorize');
		$data['text_copy_clipboard'] = $this->language->get('text_copy_clipboard');
		$data['text_show_hide'] = $this->language->get('text_show_hide');
		$data['text_no_transactions'] = $this->language->get('text_no_transactions');
		
		$data['entry_tokenize'] = $this->language->get('entry_tokenize');
		$data['entry_display_name'] = $this->language->get('entry_display_name');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_gateway'] = $this->language->get('entry_gateway');
		$data['entry_gateway_other'] = $this->language->get('entry_gateway_other');
		$data['entry_onclick'] = $this->language->get('entry_onclick');
		$data['entry_checkout'] = $this->language->get('entry_checkout');
		$data['entry_google_analytics_property_id'] = $this->language->get('entry_google_analytics_property_id');
		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_notification_secret'] = $this->language->get('entry_notification_secret');
		$data['entry_integration_password'] = $this->language->get('entry_integration_password');
		$data['entry_debug_log'] = $this->language->get('entry_debug_log');
		$data['entry_approved_authorization_order_status'] = $this->language->get('entry_approved_authorization_order_status');
		$data['entry_approved_capture_order_status'] = $this->language->get('entry_approved_capture_order_status');
		$data['entry_approved_payment_order_status'] = $this->language->get('entry_approved_payment_order_status');
		$data['entry_approved_refund_order_status'] = $this->language->get('entry_approved_refund_order_status');
		$data['entry_approved_void_order_status'] = $this->language->get('entry_approved_void_order_status');
		$data['entry_approved_verification_order_status'] = $this->language->get('entry_approved_verification_order_status');
		$data['entry_unspecified_failure_order_status'] = $this->language->get('entry_unspecified_failure_order_status');
		$data['entry_declined_order_status'] = $this->language->get('entry_declined_order_status');
		$data['entry_timed_out_order_status'] = $this->language->get('entry_timed_out_order_status');
		$data['entry_expired_card_order_status'] = $this->language->get('entry_expired_card_order_status');
		$data['entry_insufficient_funds_order_status'] = $this->language->get('entry_insufficient_funds_order_status');
		$data['entry_acquirer_system_error_order_status'] = $this->language->get('entry_acquirer_system_error_order_status');
		$data['entry_system_error_order_status'] = $this->language->get('entry_system_error_order_status');
		$data['entry_not_supported_order_status'] = $this->language->get('entry_not_supported_order_status');
		$data['entry_declined_do_not_contact_order_status'] = $this->language->get('entry_declined_do_not_contact_order_status');
		$data['entry_aborted_order_status'] = $this->language->get('entry_aborted_order_status');
		$data['entry_blocked_order_status'] = $this->language->get('entry_blocked_order_status');
		$data['entry_cancelled_order_status'] = $this->language->get('entry_cancelled_order_status');
		$data['entry_deferred_transaction_received_order_status'] = $this->language->get('entry_deferred_transaction_received_order_status');
		$data['entry_referred_order_status'] = $this->language->get('entry_referred_order_status');
		$data['entry_authentication_failed_order_status'] = $this->language->get('entry_authentication_failed_order_status');
		$data['entry_invalid_csc_order_status'] = $this->language->get('entry_invalid_csc_order_status');
		$data['entry_lock_failure_order_status'] = $this->language->get('entry_lock_failure_order_status');
		$data['entry_submitted_order_status'] = $this->language->get('entry_submitted_order_status');
		$data['entry_not_enrolled_3d_secure_order_status'] = $this->language->get('entry_not_enrolled_3d_secure_order_status');
		$data['entry_pending_order_status'] = $this->language->get('entry_pending_order_status');
		$data['entry_exceeded_retry_limit_order_status'] = $this->language->get('entry_exceeded_retry_limit_order_status');
		$data['entry_duplicate_batch_order_status'] = $this->language->get('entry_duplicate_batch_order_status');
		$data['entry_declined_avs_order_status'] = $this->language->get('entry_declined_avs_order_status');
		$data['entry_declined_csc_order_status'] = $this->language->get('entry_declined_csc_order_status');
		$data['entry_declined_avs_csc_order_status'] = $this->language->get('entry_declined_avs_csc_order_status');
		$data['entry_declined_payment_plan_order_status'] = $this->language->get('entry_declined_payment_plan_order_status');
		$data['entry_approved_pending_settlement_order_status'] = $this->language->get('entry_approved_pending_settlement_order_status');
		$data['entry_partially_approved_order_status'] = $this->language->get('entry_partially_approved_order_status');
		$data['entry_unknown_order_status'] = $this->language->get('entry_unknown_order_status');
		$data['entry_risk_rejected_order_status'] = $this->language->get('entry_risk_rejected_order_status');
		$data['entry_risk_review_pending_order_status'] = $this->language->get('entry_risk_review_pending_order_status');
		$data['entry_risk_review_rejected_order_status'] = $this->language->get('entry_risk_review_rejected_order_status');

		$data['help_total'] = $this->language->get('help_total');
		$data['help_gateway'] = $this->language->get('help_gateway');
		$data['help_tokenize'] = $this->language->get('help_tokenize');
		$data['help_display_name'] = $this->language->get('help_display_name');
		$data['help_checkout'] = $this->language->get('help_checkout');
		$data['help_merchant'] = $this->language->get('help_merchant');
		$data['help_google_analytics_property_id'] = $this->language->get('help_google_analytics_property_id');
		$data['help_notification_secret'] = $this->language->get('help_notification_secret');
		$data['help_integration_password'] = $this->language->get('help_integration_password');
		$data['help_debug_log'] = $this->language->get('help_debug_log');
		$data['help_approved_authorization_order_status'] = $this->language->get('help_approved_authorization_order_status');
		$data['help_approved_capture_order_status'] = $this->language->get('help_approved_capture_order_status');
		$data['help_approved_payment_order_status'] = $this->language->get('help_approved_payment_order_status');
		$data['help_approved_refund_order_status'] = $this->language->get('help_approved_refund_order_status');
		$data['help_approved_void_order_status'] = $this->language->get('help_approved_void_order_status');
		$data['help_approved_verification_order_status'] = $this->language->get('help_approved_verification_order_status');
		$data['help_unspecified_failure_order_status'] = $this->language->get('help_unspecified_failure_order_status');
		$data['help_declined_order_status'] = $this->language->get('help_declined_order_status');
		$data['help_timed_out_order_status'] = $this->language->get('help_timed_out_order_status');
		$data['help_expired_card_order_status'] = $this->language->get('help_expired_card_order_status');
		$data['help_insufficient_funds_order_status'] = $this->language->get('help_insufficient_funds_order_status');
		$data['help_acquirer_system_error_order_status'] = $this->language->get('help_acquirer_system_error_order_status');
		$data['help_system_error_order_status'] = $this->language->get('help_system_error_order_status');
		$data['help_not_supported_order_status'] = $this->language->get('help_not_supported_order_status');
		$data['help_declined_do_not_contact_order_status'] = $this->language->get('help_declined_do_not_contact_order_status');
		$data['help_aborted_order_status'] = $this->language->get('help_aborted_order_status');
		$data['help_blocked_order_status'] = $this->language->get('help_blocked_order_status');
		$data['help_cancelled_order_status'] = $this->language->get('help_cancelled_order_status');
		$data['help_deferred_transaction_received_order_status'] = $this->language->get('help_deferred_transaction_received_order_status');
		$data['help_referred_order_status'] = $this->language->get('help_referred_order_status');
		$data['help_authentication_failed_order_status'] = $this->language->get('help_authentication_failed_order_status');
		$data['help_invalid_csc_order_status'] = $this->language->get('help_invalid_csc_order_status');
		$data['help_lock_failure_order_status'] = $this->language->get('help_lock_failure_order_status');
		$data['help_submitted_order_status'] = $this->language->get('help_submitted_order_status');
		$data['help_not_enrolled_3d_secure_order_status'] = $this->language->get('help_not_enrolled_3d_secure_order_status');
		$data['help_pending_order_status'] = $this->language->get('help_pending_order_status');
		$data['help_exceeded_retry_limit_order_status'] = $this->language->get('help_exceeded_retry_limit_order_status');
		$data['help_duplicate_batch_order_status'] = $this->language->get('help_duplicate_batch_order_status');
		$data['help_declined_avs_order_status'] = $this->language->get('help_declined_avs_order_status');
		$data['help_declined_csc_order_status'] = $this->language->get('help_declined_csc_order_status');
		$data['help_declined_avs_csc_order_status'] = $this->language->get('help_declined_avs_csc_order_status');
		$data['help_declined_payment_plan_order_status'] = $this->language->get('help_declined_payment_plan_order_status');
		$data['help_approved_pending_settlement_order_status'] = $this->language->get('help_approved_pending_settlement_order_status');
		$data['help_partially_approved_order_status'] = $this->language->get('help_partially_approved_order_status');
		$data['help_unknown_order_status'] = $this->language->get('help_unknown_order_status');
		$data['help_risk_rejected_order_status'] = $this->language->get('help_risk_rejected_order_status');
		$data['help_risk_review_pending_order_status'] = $this->language->get('help_risk_review_pending_order_status');
		$data['help_risk_review_rejected_order_status'] = $this->language->get('help_risk_review_rejected_order_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_copy_clipboard'] = $this->language->get('button_copy_clipboard');
		$data['button_rehook_events'] = $this->language->get('button_rehook_events');

		$data['column_transaction_id'] = $this->language->get('column_transaction_id');
		$data['column_merchant'] = $this->language->get('column_merchant');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_risk'] = $this->language->get('column_risk');
		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_date_created'] = $this->language->get('column_date_created');
		$data['column_action'] = $this->language->get('column_action');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'], true);
		$data['rehook_events'] = $this->url->link('extension/payment/mastercard_pgs/hook_events', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		$data['url_list_transactions'] = html_entity_decode($this->url->link('extension/payment/mastercard_pgs/transactions', 'token=' . $this->session->data['token'] . '&page={PAGE}', true));

		if (isset($this->request->post['mastercard_pgs_total'])) {
			$data['mastercard_pgs_total'] = $this->request->post['mastercard_pgs_total'];
		} else {
			$data['mastercard_pgs_total'] = $this->config->get('mastercard_pgs_total');
		}

		if (isset($this->request->post['mastercard_pgs_approved_authorization_order_status_id'])) {
			$data['mastercard_pgs_approved_authorization_order_status_id'] = $this->request->post['mastercard_pgs_approved_authorization_order_status_id'];
		} else {
			$data['mastercard_pgs_approved_authorization_order_status_id'] = $this->config->get('mastercard_pgs_approved_authorization_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_approved_capture_order_status_id'])) {
			$data['mastercard_pgs_approved_capture_order_status_id'] = $this->request->post['mastercard_pgs_approved_capture_order_status_id'];
		} else {
			$data['mastercard_pgs_approved_capture_order_status_id'] = $this->config->get('mastercard_pgs_approved_capture_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_approved_payment_order_status_id'])) {
			$data['mastercard_pgs_approved_payment_order_status_id'] = $this->request->post['mastercard_pgs_approved_payment_order_status_id'];
		} else {
			$data['mastercard_pgs_approved_payment_order_status_id'] = $this->config->get('mastercard_pgs_approved_payment_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_approved_refund_order_status_id'])) {
			$data['mastercard_pgs_approved_refund_order_status_id'] = $this->request->post['mastercard_pgs_approved_refund_order_status_id'];
		} else {
			$data['mastercard_pgs_approved_refund_order_status_id'] = $this->config->get('mastercard_pgs_approved_refund_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_approved_void_order_status_id'])) {
			$data['mastercard_pgs_approved_void_order_status_id'] = $this->request->post['mastercard_pgs_approved_void_order_status_id'];
		} else {
			$data['mastercard_pgs_approved_void_order_status_id'] = $this->config->get('mastercard_pgs_approved_void_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_approved_verification_order_status_id'])) {
			$data['mastercard_pgs_approved_verification_order_status_id'] = $this->request->post['mastercard_pgs_approved_verification_order_status_id'];
		} else {
			$data['mastercard_pgs_approved_verification_order_status_id'] = $this->config->get('mastercard_pgs_approved_verification_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_unspecified_failure_order_status_id'])) {
			$data['mastercard_pgs_unspecified_failure_order_status_id'] = $this->request->post['mastercard_pgs_unspecified_failure_order_status_id'];
		} else {
			$data['mastercard_pgs_unspecified_failure_order_status_id'] = $this->config->get('mastercard_pgs_unspecified_failure_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_declined_order_status_id'])) {
			$data['mastercard_pgs_declined_order_status_id'] = $this->request->post['mastercard_pgs_declined_order_status_id'];
		} else {
			$data['mastercard_pgs_declined_order_status_id'] = $this->config->get('mastercard_pgs_declined_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_timed_out_order_status_id'])) {
			$data['mastercard_pgs_timed_out_order_status_id'] = $this->request->post['mastercard_pgs_timed_out_order_status_id'];
		} else {
			$data['mastercard_pgs_timed_out_order_status_id'] = $this->config->get('mastercard_pgs_timed_out_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_expired_card_order_status_id'])) {
			$data['mastercard_pgs_expired_card_order_status_id'] = $this->request->post['mastercard_pgs_expired_card_order_status_id'];
		} else {
			$data['mastercard_pgs_expired_card_order_status_id'] = $this->config->get('mastercard_pgs_expired_card_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_insufficient_funds_order_status_id'])) {
			$data['mastercard_pgs_insufficient_funds_order_status_id'] = $this->request->post['mastercard_pgs_insufficient_funds_order_status_id'];
		} else {
			$data['mastercard_pgs_insufficient_funds_order_status_id'] = $this->config->get('mastercard_pgs_insufficient_funds_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_acquirer_system_error_order_status_id'])) {
			$data['mastercard_pgs_acquirer_system_error_order_status_id'] = $this->request->post['mastercard_pgs_acquirer_system_error_order_status_id'];
		} else {
			$data['mastercard_pgs_acquirer_system_error_order_status_id'] = $this->config->get('mastercard_pgs_acquirer_system_error_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_system_error_order_status_id'])) {
			$data['mastercard_pgs_system_error_order_status_id'] = $this->request->post['mastercard_pgs_system_error_order_status_id'];
		} else {
			$data['mastercard_pgs_system_error_order_status_id'] = $this->config->get('mastercard_pgs_system_error_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_not_supported_order_status_id'])) {
			$data['mastercard_pgs_not_supported_order_status_id'] = $this->request->post['mastercard_pgs_not_supported_order_status_id'];
		} else {
			$data['mastercard_pgs_not_supported_order_status_id'] = $this->config->get('mastercard_pgs_not_supported_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_declined_do_not_contact_order_status_id'])) {
			$data['mastercard_pgs_declined_do_not_contact_order_status_id'] = $this->request->post['mastercard_pgs_declined_do_not_contact_order_status_id'];
		} else {
			$data['mastercard_pgs_declined_do_not_contact_order_status_id'] = $this->config->get('mastercard_pgs_declined_do_not_contact_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_aborted_order_status_id'])) {
			$data['mastercard_pgs_aborted_order_status_id'] = $this->request->post['mastercard_pgs_aborted_order_status_id'];
		} else {
			$data['mastercard_pgs_aborted_order_status_id'] = $this->config->get('mastercard_pgs_aborted_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_blocked_order_status_id'])) {
			$data['mastercard_pgs_blocked_order_status_id'] = $this->request->post['mastercard_pgs_blocked_order_status_id'];
		} else {
			$data['mastercard_pgs_blocked_order_status_id'] = $this->config->get('mastercard_pgs_blocked_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_cancelled_order_status_id'])) {
			$data['mastercard_pgs_cancelled_order_status_id'] = $this->request->post['mastercard_pgs_cancelled_order_status_id'];
		} else {
			$data['mastercard_pgs_cancelled_order_status_id'] = $this->config->get('mastercard_pgs_cancelled_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_deferred_transaction_received_order_status_id'])) {
			$data['mastercard_pgs_deferred_transaction_received_order_status_id'] = $this->request->post['mastercard_pgs_deferred_transaction_received_order_status_id'];
		} else {
			$data['mastercard_pgs_deferred_transaction_received_order_status_id'] = $this->config->get('mastercard_pgs_deferred_transaction_received_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_referred_order_status_id'])) {
			$data['mastercard_pgs_referred_order_status_id'] = $this->request->post['mastercard_pgs_referred_order_status_id'];
		} else {
			$data['mastercard_pgs_referred_order_status_id'] = $this->config->get('mastercard_pgs_referred_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_authentication_failed_order_status_id'])) {
			$data['mastercard_pgs_authentication_failed_order_status_id'] = $this->request->post['mastercard_pgs_authentication_failed_order_status_id'];
		} else {
			$data['mastercard_pgs_authentication_failed_order_status_id'] = $this->config->get('mastercard_pgs_authentication_failed_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_invalid_csc_order_status_id'])) {
			$data['mastercard_pgs_invalid_csc_order_status_id'] = $this->request->post['mastercard_pgs_invalid_csc_order_status_id'];
		} else {
			$data['mastercard_pgs_invalid_csc_order_status_id'] = $this->config->get('mastercard_pgs_invalid_csc_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_lock_failure_order_status_id'])) {
			$data['mastercard_pgs_lock_failure_order_status_id'] = $this->request->post['mastercard_pgs_lock_failure_order_status_id'];
		} else {
			$data['mastercard_pgs_lock_failure_order_status_id'] = $this->config->get('mastercard_pgs_lock_failure_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_submitted_order_status_id'])) {
			$data['mastercard_pgs_submitted_order_status_id'] = $this->request->post['mastercard_pgs_submitted_order_status_id'];
		} else {
			$data['mastercard_pgs_submitted_order_status_id'] = $this->config->get('mastercard_pgs_submitted_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_not_enrolled_3d_secure_order_status_id'])) {
			$data['mastercard_pgs_not_enrolled_3d_secure_order_status_id'] = $this->request->post['mastercard_pgs_not_enrolled_3d_secure_order_status_id'];
		} else {
			$data['mastercard_pgs_not_enrolled_3d_secure_order_status_id'] = $this->config->get('mastercard_pgs_not_enrolled_3d_secure_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_pending_order_status_id'])) {
			$data['mastercard_pgs_pending_order_status_id'] = $this->request->post['mastercard_pgs_pending_order_status_id'];
		} else {
			$data['mastercard_pgs_pending_order_status_id'] = $this->config->get('mastercard_pgs_pending_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_exceeded_retry_limit_order_status_id'])) {
			$data['mastercard_pgs_exceeded_retry_limit_order_status_id'] = $this->request->post['mastercard_pgs_exceeded_retry_limit_order_status_id'];
		} else {
			$data['mastercard_pgs_exceeded_retry_limit_order_status_id'] = $this->config->get('mastercard_pgs_exceeded_retry_limit_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_duplicate_batch_order_status_id'])) {
			$data['mastercard_pgs_duplicate_batch_order_status_id'] = $this->request->post['mastercard_pgs_duplicate_batch_order_status_id'];
		} else {
			$data['mastercard_pgs_duplicate_batch_order_status_id'] = $this->config->get('mastercard_pgs_duplicate_batch_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_declined_avs_order_status_id'])) {
			$data['mastercard_pgs_declined_avs_order_status_id'] = $this->request->post['mastercard_pgs_declined_avs_order_status_id'];
		} else {
			$data['mastercard_pgs_declined_avs_order_status_id'] = $this->config->get('mastercard_pgs_declined_avs_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_declined_csc_order_status_id'])) {
			$data['mastercard_pgs_declined_csc_order_status_id'] = $this->request->post['mastercard_pgs_declined_csc_order_status_id'];
		} else {
			$data['mastercard_pgs_declined_csc_order_status_id'] = $this->config->get('mastercard_pgs_declined_csc_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_declined_avs_csc_order_status_id'])) {
			$data['mastercard_pgs_declined_avs_csc_order_status_id'] = $this->request->post['mastercard_pgs_declined_avs_csc_order_status_id'];
		} else {
			$data['mastercard_pgs_declined_avs_csc_order_status_id'] = $this->config->get('mastercard_pgs_declined_avs_csc_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_declined_payment_plan_order_status_id'])) {
			$data['mastercard_pgs_declined_payment_plan_order_status_id'] = $this->request->post['mastercard_pgs_declined_payment_plan_order_status_id'];
		} else {
			$data['mastercard_pgs_declined_payment_plan_order_status_id'] = $this->config->get('mastercard_pgs_declined_payment_plan_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_approved_pending_settlement_order_status_id'])) {
			$data['mastercard_pgs_approved_pending_settlement_order_status_id'] = $this->request->post['mastercard_pgs_approved_pending_settlement_order_status_id'];
		} else {
			$data['mastercard_pgs_approved_pending_settlement_order_status_id'] = $this->config->get('mastercard_pgs_approved_pending_settlement_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_partially_approved_order_status_id'])) {
			$data['mastercard_pgs_partially_approved_order_status_id'] = $this->request->post['mastercard_pgs_partially_approved_order_status_id'];
		} else {
			$data['mastercard_pgs_partially_approved_order_status_id'] = $this->config->get('mastercard_pgs_partially_approved_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_unknown_order_status_id'])) {
			$data['mastercard_pgs_unknown_order_status_id'] = $this->request->post['mastercard_pgs_unknown_order_status_id'];
		} else {
			$data['mastercard_pgs_unknown_order_status_id'] = $this->config->get('mastercard_pgs_unknown_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_risk_rejected_order_status_id'])) {
			$data['mastercard_pgs_risk_rejected_order_status_id'] = $this->request->post['mastercard_pgs_risk_rejected_order_status_id'];
		} else {
			$data['mastercard_pgs_risk_rejected_order_status_id'] = $this->config->get('mastercard_pgs_risk_rejected_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_risk_review_pending_order_status_id'])) {
			$data['mastercard_pgs_risk_review_pending_order_status_id'] = $this->request->post['mastercard_pgs_risk_review_pending_order_status_id'];
		} else {
			$data['mastercard_pgs_risk_review_pending_order_status_id'] = $this->config->get('mastercard_pgs_risk_review_pending_order_status_id');
		}

		if (isset($this->request->post['mastercard_pgs_risk_review_rejected_order_status_id'])) {
			$data['mastercard_pgs_risk_review_rejected_order_status_id'] = $this->request->post['mastercard_pgs_risk_review_rejected_order_status_id'];
		} else {
			$data['mastercard_pgs_risk_review_rejected_order_status_id'] = $this->config->get('mastercard_pgs_risk_review_rejected_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['mastercard_pgs_geo_zone_id'])) {
			$data['mastercard_pgs_geo_zone_id'] = $this->request->post['mastercard_pgs_geo_zone_id'];
		} else {
			$data['mastercard_pgs_geo_zone_id'] = $this->config->get('mastercard_pgs_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['mastercard_pgs_status'])) {
			$data['mastercard_pgs_status'] = $this->request->post['mastercard_pgs_status'];
		} else {
			$data['mastercard_pgs_status'] = $this->config->get('mastercard_pgs_status');
		}

		if (isset($this->request->post['mastercard_pgs_sort_order'])) {
			$data['mastercard_pgs_sort_order'] = $this->request->post['mastercard_pgs_sort_order'];
		} else {
			$data['mastercard_pgs_sort_order'] = $this->config->get('mastercard_pgs_sort_order');
		}

		if (isset($this->request->post['mastercard_pgs_gateway'])) {
			$data['mastercard_pgs_gateway'] = $this->request->post['mastercard_pgs_gateway'];
		} else {
			$data['mastercard_pgs_gateway'] = $this->config->get('mastercard_pgs_gateway');
		}

		if (isset($this->request->post['mastercard_pgs_gateway_other'])) {
			$data['mastercard_pgs_gateway_other'] = $this->request->post['mastercard_pgs_gateway_other'];
		} else {
			$data['mastercard_pgs_gateway_other'] = $this->config->get('mastercard_pgs_gateway_other');
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

		if (isset($this->request->post['mastercard_pgs_onclick'])) {
			$data['mastercard_pgs_onclick'] = $this->request->post['mastercard_pgs_onclick'];
		} else {
			$data['mastercard_pgs_onclick'] = $this->config->get('mastercard_pgs_onclick');
		}

		if (isset($this->request->post['mastercard_pgs_checkout'])) {
			$data['mastercard_pgs_checkout'] = $this->request->post['mastercard_pgs_checkout'];
		} else {
			$data['mastercard_pgs_checkout'] = $this->config->get('mastercard_pgs_checkout');
		}

		if (isset($this->request->post['mastercard_pgs_google_analytics_property_id'])) {
			$data['mastercard_pgs_google_analytics_property_id'] = $this->request->post['mastercard_pgs_google_analytics_property_id'];
		} else {
			$data['mastercard_pgs_google_analytics_property_id'] = $this->config->get('mastercard_pgs_google_analytics_property_id');
		}

		if (isset($this->request->post['mastercard_pgs_merchant'])) {
			$data['mastercard_pgs_merchant'] = $this->request->post['mastercard_pgs_merchant'];
		} else {
			$data['mastercard_pgs_merchant'] = $this->config->get('mastercard_pgs_merchant');
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if (isset($this->request->post['mastercard_pgs_notification_secret'])) {
			$data['mastercard_pgs_notification_secret'] = $this->request->post['mastercard_pgs_notification_secret'];
		} else {
			$data['mastercard_pgs_notification_secret'] = $this->config->get('mastercard_pgs_notification_secret');
		}

		if (isset($this->error['notification_secret'])) {
			$data['error_notification_secret'] = $this->error['notification_secret'];
		} else {
			$data['error_notification_secret'] = '';
		}

		if (isset($this->request->post['mastercard_pgs_integration_password'])) {
			$data['mastercard_pgs_integration_password'] = $this->request->post['mastercard_pgs_integration_password'];
		} else {
			$data['mastercard_pgs_integration_password'] = $this->config->get('mastercard_pgs_integration_password');
		}

		if (isset($this->error['integration_password'])) {
			$data['error_integration_password'] = $this->error['integration_password'];
		} else {
			$data['error_integration_password'] = '';
		}

		if (isset($this->request->post['mastercard_pgs_debug_log'])) {
			$data['mastercard_pgs_debug_log'] = $this->request->post['mastercard_pgs_debug_log'];
		} else {
			$data['mastercard_pgs_debug_log'] = $this->config->get('mastercard_pgs_debug_log');
		}

		if (isset($this->request->post['mastercard_pgs_tokenize'])) {
			$data['mastercard_pgs_tokenize'] = $this->request->post['mastercard_pgs_tokenize'];
		} else {
			$data['mastercard_pgs_tokenize'] = $this->config->get('mastercard_pgs_tokenize');
		}

		if (isset($this->request->post['mastercard_pgs_display_name'])) {
			$data['mastercard_pgs_display_name'] = $this->request->post['mastercard_pgs_display_name'];
		} else {
			$data['mastercard_pgs_display_name'] = $this->config->get('mastercard_pgs_display_name');
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
			$this->response->redirect('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'], true);
		}

		$this->document->setTitle(sprintf($this->language->get('heading_title_transaction'), $transaction_info['transaction_id']));

		if ($this->request->server['HTTPS']) {
			$server = HTTPS_SERVER;
		} else {
			$server = HTTP_SERVER;
		}

		$this->document->addStyle($server . 'view/stylesheet/mastercard_pgs.css');

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['text_edit'] = sprintf($this->language->get('heading_title_transaction'), $transaction_info['transaction_id']);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_void'] = $this->language->get('button_void');
		$data['button_refund'] = $this->language->get('button_refund');
		$data['button_capture'] = $this->language->get('button_capture');

		$data['text_void'] = $this->language->get('text_void');
		$data['text_refund'] = $this->language->get('text_refund');
		$data['text_capture'] = $this->language->get('text_capture');
		$data['text_confirm_void'] = $this->language->get('text_confirm_void');
		$data['text_confirm_refund'] = $this->language->get('text_confirm_refund');
		$data['text_confirm_capture'] = $this->language->get('text_confirm_capture');
		$data['text_loading'] = $this->language->get('text_loading_short');

		$data['entry_transaction_id'] = $this->language->get('entry_transaction_id');
		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_partner_solution_id'] = $this->language->get('entry_partner_solution_id');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_result'] = $this->language->get('entry_result');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_risk_code'] = $this->language->get('entry_risk_code');
		$data['entry_risk_score'] = $this->language->get('entry_risk_score');
		$data['entry_api_version'] = $this->language->get('entry_api_version');
		$data['entry_browser'] = $this->language->get('entry_browser');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_date_created'] = $this->language->get('entry_date_created');

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
		
		$data['cancel'] = $this->url->link('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'] . '&tab=tab-transaction', true);

		$data['url_order'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $transaction_info['order_id'], true);
		$data['url_void'] = $this->url->link('extension/payment/mastercard_pgs/void', 'token=' . $this->session->data['token'] . '&mastercard_pgs_transaction_id=' . $transaction_info['mastercard_pgs_transaction_id'], true);
		$data['url_capture'] = $this->url->link('extension/payment/mastercard_pgs/capture', 'token=' . $this->session->data['token'] . '&mastercard_pgs_transaction_id=' . $transaction_info['mastercard_pgs_transaction_id'], true);
		$data['url_refund'] = $this->url->link('extension/payment/mastercard_pgs/refund', 'token=' . $this->session->data['token'] . '&mastercard_pgs_transaction_id=' . $transaction_info['mastercard_pgs_transaction_id'], true);

		$data['is_authorized'] = in_array($transaction_info['transaction_type'], array('AUTHORIZATION', 'AUTHORIZATION_UPDATE'));
		$data['is_captured'] = in_array($transaction_info['transaction_type'], array('CAPTURE'));
		$data['is_payment'] = in_array($transaction_info['transaction_type'], array('PAYMENT'));

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
			'href' => $this->url->link('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => sprintf($this->language->get('heading_title_transaction'), $transaction_info['transaction_id']),
			'href' => $this->url->link('extension/payment/mastercard_pgs/transaction_info', 'token=' . $this->session->data['token'] . '&mastercard_pgs_transaction_id=' . $mastercard_pgs_transaction_id, true)
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

		$transactions_total = $this->model_extension_payment_mastercard_pgs->getTotalTransactions();
		$transactions = $this->model_extension_payment_mastercard_pgs->getTransactions($filter_data);

		$currency = new \Cart\Currency($this->registry);

		foreach ($transactions as $transaction) {
			$amount = $currency->format($transaction['transaction_amount'], $transaction['transaction_currency']);

			$result['transactions'][] = array(
				'transaction_id' => $transaction['transaction_id'],
				'merchant' => $transaction['merchant'],
				'url_order' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $transaction['order_id'], true),
				'order_id' => $transaction['order_id'],
				'type' => $transaction['transaction_type'],
				'amount' => $amount,
				'risk' => $transaction['risk_response_totalScore'],
				'ip' => $transaction['device_ipAddress'],
				'date_created' => date($this->language->get('datetime_format'), strtotime($transaction['timeOfRecord'])),
				'url_info' => $this->url->link('extension/payment/mastercard_pgs/transaction_info', 'token=' . $this->session->data['token'] . '&mastercard_pgs_transaction_id=' . $transaction['mastercard_pgs_transaction_id'], true)
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

			$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'], true));
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
			$this->response->redirect('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'], true);
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

					$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'] . '&tab=tab-transaction', true));
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

		$this->response->redirect($this->url->link('extension/payment/mastercard_pgs/transaction_info', 'token=' . $this->session->data['token'] . '&mastercard_pgs_transaction_id=' . $mastercard_pgs_transaction_id, true));
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
			$this->response->redirect('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'], true);
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

					$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'] . '&tab=tab-transaction', true));
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

		$this->response->redirect($this->url->link('extension/payment/mastercard_pgs/transaction_info', 'token=' . $this->session->data['token'] . '&mastercard_pgs_transaction_id=' . $mastercard_pgs_transaction_id, true));
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
			$this->response->redirect('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'], true);
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

					$this->response->redirect($this->url->link('extension/payment/mastercard_pgs', 'token=' . $this->session->data['token'] . '&tab=tab-transaction', true));
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

		$this->response->redirect($this->url->link('extension/payment/mastercard_pgs/transaction_info', 'token=' . $this->session->data['token'] . '&mastercard_pgs_transaction_id=' . $mastercard_pgs_transaction_id, true));
	}

	public function delete_tokens($route, $args) {
		if (!empty($args[0]) && $this->user->hasPermission('modify', 'extension/payment/mastercard_pgs')) {
			$this->load->model('extension/payment/mastercard_pgs');
			$this->model_extension_payment_mastercard_pgs->deleteTokens($args[0]);
		}
	}

	public function order_history_link($route, $args, &$data) {
		if ($this->user->hasPermission('access', 'extension/payment/mastercard_pgs')) {
			$this->load->model('extension/payment/mastercard_pgs');
			
			$this->load->language('extension/payment/mastercard_pgs');
			
			foreach ($data as &$history) {
				$matches = array();

				if (!preg_match($this->language->get('regex_order_history_link'), $history['comment'], $matches)) {
					continue;
				}

				$filter_data = array(
					'order_id' => (int)$args[0],
					'transaction_id' => $matches[3]
				);

				$transactions = $this->model_extension_payment_mastercard_pgs->getTransactions($filter_data);

				if (empty($transactions[0])) {
					continue;
				}

				$new_comment_parts = array(
					$matches[1],
					'<a href="' . $this->url->link('extension/payment/mastercard_pgs/transaction_info', 'mastercard_pgs_transaction_id=' . $transactions[0]['mastercard_pgs_transaction_id'] . '&token=' . $this->session->data['token'], true) . '" target="_blank">' . $matches[2] . $matches[3] . '</a>',
					$matches[4]
				);

				$history['comment'] = implode('', $new_comment_parts);
			}
		}
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

		if (empty($this->request->post['mastercard_pgs_merchant']) || strlen($this->request->post['mastercard_pgs_merchant']) > 16) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (empty($this->request->post['mastercard_pgs_notification_secret']) || strlen($this->request->post['mastercard_pgs_notification_secret']) != 32) {
			$this->error['notification_secret'] = $this->language->get('error_notification_secret');
		}

		if (empty($this->request->post['mastercard_pgs_integration_password']) || strlen($this->request->post['mastercard_pgs_integration_password']) != 32) {
			$this->error['integration_password'] = $this->language->get('error_integration_password');
		}

		if ($this->request->post['mastercard_pgs_gateway'] == 'other' && empty($this->request->post['mastercard_pgs_gateway_other'])) {
			$this->error['gateway_other'] = $this->language->get('error_gateway_other');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}