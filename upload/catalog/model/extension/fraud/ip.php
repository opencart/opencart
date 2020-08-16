<?php
class ModelExtensionFraudIp extends Model {
	public function check($order_info) {
		$this->load->model('account/customer');

		$status = false;

		if ($order_info['customer_id']) {
			$results = $this->model_account_customer->getIps($order_info['customer_id']);

			foreach ($results as $result) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraud_ip` WHERE ip = '" . $this->db->escape($result['ip']) . "'");

				if ($query->num_rows) {
					$status = true;

					break;
				}
			}
		} else {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraud_ip` WHERE ip = '" . $this->db->escape($order_info['ip']) . "'");

			if ($query->num_rows) {
				$status = true;
			}
		}

		if ($status) {
			return $this->config->get('fraud_ip_order_status_id');
		}
	}
}
