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
		$option_data = [];

		if ($status) {
			$option_data['add'] = [
				'code' => 'credit_card.add',
				'name' => $this->language->get('text_card_add')
			];

			$results = $this->getCreditCards($this->customer->getId());

			foreach ($results as $result) {
				$option_data['credit_card.' . $result['credit_card_id']] = [
					'code' => 'credit_card.' . $result['credit_card_id'],
					'name' => $result['card_number']
				];
			}

			$method_data = [
				'code'       => 'credit_card',
				'title'      => $this->language->get('heading_title'),
				'option'     => $option_data,
				'sort_order' => $this->config->get('payment_credit_card_sort_order')
			];
		}
		return $method_data;
	}

	public function getCreditCard(int $customer_id, int $credit_card_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "credit_card` WHERE `customer_id` = '" . (int)$customer_id . "' AND `credit_card_id` = '" . (int)$credit_card_id . "'");

		return $query->row;
	}

	public function getCreditCards(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "credit_card` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function addCreditCard(int $customer_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "credit_card` SET `customer_id` = '" . (int)$customer_id . "', `card_name` = '" . $this->db->escape($data['card_name']) . "', `card_number` = '" . $this->db->escape($data['card_number']) . "', `card_expire_month` = '" . $this->db->escape($data['card_expire_month']) . "', `card_expire_year` = '" . $this->db->escape($data['card_expire_year']) . "', `card_cvv` = '" . $this->db->escape($data['card_cvv']) . "', `date_added` = NOW()");
	}

	public function deleteCreditCard(int $customer_id, int $credit_card_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "credit_card` WHERE `customer_id` = '" . (int)$customer_id . "' AND `credit_card_id` = '" . (int)$credit_card_id . "'");
	}
}
