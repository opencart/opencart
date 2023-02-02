<?php
namespace Opencart\Catalog\Model\Extension\OcPaymentExample\Payment;
class CreditCard extends \Opencart\System\Engine\Model {
	public function getMethod(array $address): array {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('payment_credit_card_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

		if (!$this->config->get('payment_credit_card_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = [];

		if ($status) {
			$method_data = [
				'code'       => 'credit_card',
				'title'      => $this->language->get('heading_title'),
				'sort_order' => $this->config->get('payment_credit_card_sort_order')
			];
		}

		return $method_data;
	}

	public function charge(int $customer_id, int $customer_payment_id, float $amount): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "download` SET `filename` = '" . $this->db->escape((string)$data['filename']) . "', `mask` = '" . $this->db->escape((string)$data['mask']) . "',");

		return $this->config->get('config_subscription_active_status_id');
	}
}
