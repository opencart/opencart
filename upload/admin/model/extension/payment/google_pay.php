<?php
class ModelExtensionPaymentGooglePay extends Model {
	public function install() {
		$this->load->model('setting/setting');

		$default_settings = array(
			'payment_google_pay_environment' 			=> 'TEST',
			'payment_google_pay_total' 					=> 0,
			'payment_google_pay_sort_order' 			=> 0,
			'payment_google_pay_status' 				=> 0,
			'payment_google_pay_debug' 					=> 0,
			'payment_google_pay_button_color' 			=> 'default',
			'payment_google_pay_button_type' 			=> 'long',
			'payment_google_pay_accept_prepay_cards'	=> 1,
			'payment_google_pay_allow_card_networks'	=> array('AMEX', 'DISCOVER', 'JCB', 'MASTERCARD', 'VISA'),
			'payment_google_pay_allow_auth_methods'		=> array('PAN_ONLY', 'CRYPTOGRAM_3DS'),
			'payment_google_pay_bill_require_phone'		=> 0,
			'payment_google_pay_ship_require_phone'		=> 0,
			'payment_google_pay_ship_allow_countries' 	=> array(),
		);

		$this->model_setting_setting->editSetting('payment_google_pay', $default_settings);

		$this->load->model('setting/event');

		$this->model_setting_event->addEvent('extension_google_pay_checkout_js', 'catalog/controller/checkout/checkout/before', 'extension/payment/google_pay/js');
	}

	public function uninstall() {
		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('extension_google_pay_checkout_js');
	}
}