<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Fraud;
/**
 * Class Ip
 *
 * Can be called from $this->load->model('extension/opencart/fraud/ip');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Fraud
 */
class Ip extends \Opencart\System\Engine\Model {
	/**
	 * Check IP
	 *
	 * @param array<string, mixed> $order_info
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $check = $this->model_extension_opencart_fraud_ip($order_info);
	 */
	public function check(array $order_info): int {
		$status = false;

		// Customer
		if ($order_info['customer_id']) {
			$this->load->model('account/customer');

			$results = $this->model_account_customer->getIps($order_info['customer_id']);

			foreach ($results as $result) {
				$ips = $this->getIps($result['ip']);

				if ($ips) {
					$status = true;

					break;
				}
			}
		} else {
			$ips = $this->getIps($order_info['ip']);

			if ($ips) {
				$status = true;
			}
		}

		if ($status) {
			return (int)$this->config->get('fraud_ip_order_status_id');
		} else {
			return 0;
		}
	}

	/**
	 * Get IPs
	 *
	 * @param string $ip
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getIps(string $ip): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraud_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		return $query->rows;
	}
}
