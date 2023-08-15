<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Activity
 *
 * @package Opencart\Catalog\Model\Account
 */
class Activity extends \Opencart\System\Engine\Model {
	/**
	 * @param string $key
	 * @param array  $data
	 *
	 * @return void
	 */
	public function addActivity(string $key, array $data): void {
		if (isset($data['customer_id'])) {
			$customer_id = $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_activity` SET `customer_id` = '" . (int)$customer_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}
}