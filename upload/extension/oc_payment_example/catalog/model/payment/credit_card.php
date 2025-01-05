<?php
namespace Opencart\Catalog\Model\Extension\OcPaymentExample\Payment;
/**
 * Credit Card
 *
 * Can be called from $this->load->model('extension/oc_payment_example/payment/credit_card');
 *
 * @package Opencart\Catalog\Model\Extension\OcPaymentExample\Payment
 */
class CreditCard extends \Opencart\System\Engine\Model {
	/**
	 * Get the payment methods
	 *
	 * @param array<string, mixed> $address array of filters
	 *
	 * @return array<string, mixed>
	 */
	public function getMethods(array $address): array {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		if (!$this->config->get('config_checkout_payment_address')) {
			$status = true;
		} elseif (!$this->config->get('payment_credit_card_geo_zone_id')) {
			$status = true;
		} else {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('payment_credit_card_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

			if ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}
		}

		$method_data = [];

		if ($status) {
			$option_data = [];

			$results = $this->getCreditCards($this->customer->getId());

			$option_data['credit_card'] = [
				'code' => 'credit_card.credit_card',
				'name' => !$results ? $this->language->get('text_card_use') : $this->language->get('text_card_new')
			];

			foreach ($results as $result) {
				$option_data[$result['credit_card_id']] = [
					'code' => 'credit_card.' . $result['credit_card_id'],
					'name' => $this->language->get('text_card_use') . ' ' . $this->language->get('text_' . $result['type']) . ' ' . $result['card_number']
				];
			}

			$method_data = [
				'code'       => 'credit_card',
				'name'       => $this->language->get('heading_title'),
				'option'     => $option_data,
				'sort_order' => $this->config->get('payment_credit_card_sort_order')
			];
		}

		return $method_data;
	}

	/**
	 * Add a credit card to the customer's account
	 *
	 * @param int                  $customer_id primary key of the customer record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return int
	 */
	public function addCreditCard(int $customer_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "credit_card` SET `customer_id` = '" . (int)$customer_id . "', `card_name` = '" . $this->db->escape($data['card_name']) . "', `type` = '" . $this->db->escape($data['type']) . "', `card_number` = '" . $this->db->escape($data['card_number']) . "', `card_expire_month` = '" . $this->db->escape($data['card_expire_month']) . "', `card_expire_year` = '" . $this->db->escape($data['card_expire_year']) . "', `card_cvv` = '" . $this->db->escape($data['card_cvv']) . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Delete a credit card from the customer's account
	 *
	 * @param int $customer_id    primary key of the customer record
	 * @param int $credit_card_id
	 *
	 * @return void
	 */
	public function deleteCreditCard(int $customer_id, int $credit_card_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "credit_card` WHERE `customer_id` = '" . (int)$customer_id . "' AND `credit_card_id` = '" . (int)$credit_card_id . "'");
	}

	/**
	 * Get a credit card from the customer's account
	 *
	 * @param int $customer_id    primary key of the customer record
	 * @param int $credit_card_id
	 *
	 * @return array<string, mixed>
	 */
	public function getCreditCard(int $customer_id, int $credit_card_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "credit_card` WHERE `customer_id` = '" . (int)$customer_id . "' AND `credit_card_id` = '" . (int)$credit_card_id . "'");

		return $query->row;
	}

	/**
	 * Get all credit cards from the customer's account
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getCreditCards(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "credit_card` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	/**
	 * Add Report
	 *
	 * @param int                  $customer_id primary key of the customer record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 */
	public function addReport(int $customer_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "credit_card_report` SET `customer_id` = '" . (int)$customer_id . "', `credit_card_id` = '" . (int)$data['credit_card_id'] . "', `order_id` = '" . (int)$data['order_id'] . "', `card_number` = '" . $this->db->escape($data['card_number']) . "', `type` = '" . $this->db->escape($data['type']) . "', `amount` = '" . $this->db->escape($data['amount']) . "', `response` = '" . (bool)$data['response'] . "', `date_added` = NOW()");
	}
}
