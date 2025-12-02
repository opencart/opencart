<?php
namespace Opencart\System\Library\Cart;
/**
 * Class Tax
 *
 * @package Opencart\System\Library\Cart
 */
class Tax {
	/**
	 * @var object
	 */
	private object $db;
	/**
	 * @var object
	 */
	private object $config;
	/**
	 * @var array<int, array<int, array<string, mixed>>>
	 */
	private array $tax_rates = [];

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');
	}

	/**
	 * Set Shipping Address
	 *
	 * @param int $country_id primary key of the country record
	 * @param int $zone_id    primary key of the zone record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->tax->setShippingAddress($country_id, $zone_id);
	 */
	public function setShippingAddress(int $country_id, int $zone_id): void {
		$tax_query = $this->db->query("SELECT *, `tr1`.`tax_class_id`, `tr2`.`tax_rate_id`, `tr2`.`name`, `tr2`.`rate`, `tr2`.`type`, `tr1`.`priority` 
        FROM `" . DB_PREFIX . "tax_rule` `tr1` 
		LEFT JOIN `" . DB_PREFIX . "tax_rate` `tr2` ON (`tr1`.`tax_rate_id` = `tr2`.`tax_rate_id`) 
		INNER JOIN `" . DB_PREFIX . "tax_rate_to_customer_group` `tr2cg` ON (`tr2`.`tax_rate_id` = `tr2cg`.`tax_rate_id`) 
		LEFT JOIN `" . DB_PREFIX . "zone_to_geo_zone` `z2gz` ON (`tr2`.`geo_zone_id` = `z2gz`.`geo_zone_id`) 
		LEFT JOIN `" . DB_PREFIX . "geo_zone` `gz` ON (`tr2`.`geo_zone_id` = `gz`.`geo_zone_id`) 
		WHERE `tr1`.`based` = 'shipping' 
		AND `tr2cg`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' 
		AND `z2gz`.`country_id` = '" . (int)$country_id . "' 
		AND (`z2gz`.`zone_id` = '0' OR `z2gz`.`zone_id` = '" . (int)$zone_id . "') ORDER BY `tr1`.`priority` ASC");

		foreach ($tax_query->rows as $result) {
			$this->tax_rates[$result['tax_class_id']][$result['tax_rate_id']] = [
				'tax_rate_id' => $result['tax_rate_id'],
				'name'        => $result['name'],
				'rate'        => $result['rate'],
				'type'        => $result['type'],
				'priority'    => $result['priority']
			];
		}
	}

	/**
	 * Set Payment Address
	 *
	 * @param int $country_id primary key of the country record
	 * @param int $zone_id    primary key of the zone record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->tax->setPaymentAddress($country_id, $zone_id);
	 */
	public function setPaymentAddress(int $country_id, int $zone_id): void {
		$tax_query = $this->db->query("SELECT `tr1`.`tax_class_id`, `tr2`.`tax_rate_id`, `tr2`.`name`, `tr2`.`rate`, `tr2`.`type`, `tr1`.`priority` FROM `" . DB_PREFIX . "tax_rule` `tr1` LEFT JOIN `" . DB_PREFIX . "tax_rate` `tr2` ON (`tr1`.`tax_rate_id` = `tr2`.`tax_rate_id`) INNER JOIN `" . DB_PREFIX . "tax_rate_to_customer_group` `tr2cg` ON (`tr2`.`tax_rate_id` = `tr2cg`.`tax_rate_id`) LEFT JOIN `" . DB_PREFIX . "zone_to_geo_zone` `z2gz` ON (`tr2`.`geo_zone_id` = `z2gz`.`geo_zone_id`) LEFT JOIN `" . DB_PREFIX . "geo_zone` `gz` ON (`tr2`.`geo_zone_id` = `gz`.`geo_zone_id`) WHERE `tr1`.`based` = 'payment' AND `tr2cg`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `z2gz`.`country_id` = '" . (int)$country_id . "' AND (`z2gz`.`zone_id` = '0' OR `z2gz`.`zone_id` = '" . (int)$zone_id . "') ORDER BY `tr1`.`priority` ASC");

		foreach ($tax_query->rows as $result) {
			$this->tax_rates[$result['tax_class_id']][$result['tax_rate_id']] = [
				'tax_rate_id' => $result['tax_rate_id'],
				'name'        => $result['name'],
				'rate'        => $result['rate'],
				'type'        => $result['type'],
				'priority'    => $result['priority']
			];
		}
	}

	/**
	 * Set Store Address
	 *
	 * @param int $country_id primary key of the country record
	 * @param int $zone_id    primary key of the zone record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->tax->setStoreAddress($country_id, $zone_id);
	 */
	public function setStoreAddress(int $country_id, int $zone_id): void {
		$tax_query = $this->db->query("SELECT `tr1`.`tax_class_id`, `tr2`.`tax_rate_id`, `tr2`.`name`, `tr2`.`rate`, `tr2`.`type`, `tr1`.`priority` FROM `" . DB_PREFIX . "tax_rule` `tr1` LEFT JOIN `" . DB_PREFIX . "tax_rate` `tr2` ON (`tr1`.`tax_rate_id` = `tr2`.`tax_rate_id`) INNER JOIN `" . DB_PREFIX . "tax_rate_to_customer_group` `tr2cg` ON (`tr2`.`tax_rate_id` = `tr2cg`.`tax_rate_id`) LEFT JOIN `" . DB_PREFIX . "zone_to_geo_zone` `z2gz` ON (`tr2`.`geo_zone_id` = `z2gz`.`geo_zone_id`) LEFT JOIN `" . DB_PREFIX . "geo_zone` `gz` ON (`tr2`.`geo_zone_id` = `gz`.`geo_zone_id`) WHERE `tr1`.`based` = 'store' AND `tr2cg`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `z2gz`.`country_id` = '" . (int)$country_id . "' AND (`z2gz`.`zone_id` = '0' OR `z2gz`.`zone_id` = '" . (int)$zone_id . "') ORDER BY `tr1`.`priority` ASC");

		foreach ($tax_query->rows as $result) {
			$this->tax_rates[$result['tax_class_id']][$result['tax_rate_id']] = [
				'tax_rate_id' => $result['tax_rate_id'],
				'name'        => $result['name'],
				'rate'        => $result['rate'],
				'type'        => $result['type'],
				'priority'    => $result['priority']
			];
		}
	}

	/**
	 * Calculate
	 *
	 * @param float $value
	 * @param int   $tax_class_id primary key of the tax class record
	 * @param bool  $calculate
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $tax = $this->tax->calculate($value, $tax_class_id, $calculate);
	 */
	public function calculate(float $value, int $tax_class_id, bool $calculate = true): float {
		if ($tax_class_id && $calculate) {
			$amount = 0;

			$tax_rates = $this->getRates($value, $tax_class_id);

			foreach ($tax_rates as $tax_rate) {
				$amount += $tax_rate['amount'];
			}

			return $value + $amount;
		} else {
			return $value;
		}
	}

	/**
	 * Get Tax
	 *
	 * @param float $value
	 * @param int   $tax_class_id primary key of the tax class record
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $tax = $this->tax->getTax($value, $tax_class_id);
	 */
	public function getTax(float $value, int $tax_class_id): float {
		$amount = 0;

		$tax_rates = $this->getRates($value, $tax_class_id);

		foreach ($tax_rates as $tax_rate) {
			$amount += $tax_rate['amount'];
		}

		return $amount;
	}

	/**
	 * Get Rate Name
	 *
	 * @param int $tax_rate_id primary key of the tax rate record
	 *
	 * @return false|string
	 *
	 * @example
	 *
	 * $rate = $this->tax->getRateName($tax_rate_id);
	 */
	public function getRateName(int $tax_rate_id) {
		$tax_query = $this->db->query("SELECT `name` FROM `" . DB_PREFIX . "tax_rate` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");

		if ($tax_query->num_rows) {
			return $tax_query->row['name'];
		} else {
			return false;
		}
	}

	/**
	 * Get Rates
	 *
	 * @param float $value
	 * @param int   $tax_class_id primary key of the tax class record
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $rates = $this->tax->getRates($value, $tax_class_id);
	 */
	public function getRates(float $value, int $tax_class_id): array {
		$tax_rate_data = [];

		if (isset($this->tax_rates[$tax_class_id])) {
			foreach ($this->tax_rates[$tax_class_id] as $tax_rate) {


				if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
					$amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
				} else {
					$amount = 0;
				}

				if ($tax_rate['type'] == 'F') {
					$amount += $tax_rate['rate'];
				} elseif ($tax_rate['type'] == 'P') {
					$amount += ($value / 100 * $tax_rate['rate']);
				}

				$tax_rate_data[$tax_rate['tax_rate_id']] = [
					'tax_rate_id' => $tax_rate['tax_rate_id'],
					'name'        => $tax_rate['name'],
					'rate'        => $tax_rate['rate'],
					'type'        => $tax_rate['type'],
					'amount'      => $amount
				];
			}
		}

		return $tax_rate_data;
	}

	/**
	 * Clear
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->tax->clear();
	 */
	public function clear(): void {
		$this->tax_rates = [];
	}
}
