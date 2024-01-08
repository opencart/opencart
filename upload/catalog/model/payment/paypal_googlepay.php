<?php
namespace Opencart\Catalog\Model\Extension\PayPal\Payment;
class PayPalGooglePay extends \Opencart\System\Engine\Model {
	public function getMethod(array $address): array {
		$method_data = [];

		$this->load->model('extension/paypal/payment/paypal');

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

		if ($this->config->get('payment_paypal_status') && $this->config->get('payment_paypal_client_id') && $this->config->get('payment_paypal_secret') && $agree_status) {
			$this->load->language('extension/paypal/payment/paypal');

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('payment_paypal_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

			if ($this->cart->hasSubscription()) {
				$status = false;
			} elseif (!$this->config->get('payment_paypal_geo_zone_id')) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}

			if ($status) {
				$method_data = [
					'code'       => 'paypal_googlepay',
					'title'      => $this->language->get('text_paypal_googlepay_title'),
					'sort_order' => $this->config->get('payment_paypal_sort_order')
				];
			}
		}

		return $method_data;
	}
}
