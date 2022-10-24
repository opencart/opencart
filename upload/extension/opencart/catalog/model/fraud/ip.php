<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Fraud;
class Ip extends \Opencart\System\Engine\Model {
	public function check(array $order_info): int {
		$status = false;

		if ($order_info['customer_id']) {
			$this->load->model('account/customer');

			$results = $this->model_account_customer->getIps($order_info['customer_id']);

			foreach ($results as $result) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraud_ip` WHERE `ip` = '" . $this->db->escape($result['ip']) . "'");

				if ($query->num_rows) {
					$status = true;

					break;
				}
			}
		} else {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraud_ip` WHERE `ip` = '" . $this->db->escape($order_info['ip']) . "'");

			if ($query->num_rows) {
				$status = true;
			}
		}

		if ($status) {
			return (int)$this->config->get('fraud_ip_order_status_id');
		} else {
			return 0;
		}
	}
}
