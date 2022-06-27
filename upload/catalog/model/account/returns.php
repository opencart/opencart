<?php
namespace Opencart\Catalog\Model\Account;
class Returns extends \Opencart\System\Engine\Model {
	public function addReturn(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return` SET `order_id` = '" . (int)$data['order_id'] . "', `product_id` = '" . (int)$data['product_id'] . "', `customer_id` = '" . (int)$this->customer->getId() . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `product` = '" . $this->db->escape((string)$data['product']) . "', `model` = '" . $this->db->escape((string)$data['model']) . "', `quantity` = '" . (int)$data['quantity'] . "', `opened` = '" . (int)$data['opened'] . "', `return_reason_id` = '" . (int)$data['return_reason_id'] . "', `return_status_id` = '" . (int)$this->config->get('config_return_status_id') . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "', `date_ordered` = '" . $this->db->escape((string)$data['date_ordered']) . "', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	public function getReturn(int $return_id): array {
		$query = $this->db->query("SELECT r.`return_id`, r.`order_id`, r.`firstname`, r.`lastname`, r.`email`, r.`telephone`, r.`product`, r.`model`, r.`quantity`, r.`opened`, (SELECT rr.`name` FROM `" . DB_PREFIX . "return_reason` rr WHERE rr.`return_reason_id` = r.`return_reason_id` AND rr.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS reason, (SELECT ra.`name` FROM `" . DB_PREFIX . "return_action` ra WHERE ra.`return_action_id` = r.`return_action_id` AND ra.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS action, (SELECT rs.`name` FROM `" . DB_PREFIX . "return_status` rs WHERE rs.`return_status_id` = r.`return_status_id` AND rs.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS status, r.`comment`, r.`date_ordered`, r.`date_added`, r.`date_modified` FROM `" . DB_PREFIX . "return` r WHERE r.`return_id` = '" . (int)$return_id . "' AND r.`customer_id` = '" . $this->customer->getId() . "'");

		return $query->row;
	}

	public function getReturns(int $start = 0, int $limit = 20): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.`return_id`, r.`order_id`, r.`firstname`, r.`lastname`, rs.`name` AS status, r.`date_added` FROM `" . DB_PREFIX . "return` r LEFT JOIN `" . DB_PREFIX . "return_status` rs ON (r.`return_status_id` = rs.`return_status_id`) WHERE r.`customer_id` = '" . (int)$this->customer->getId() . "' AND rs.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.`return_id` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReturns(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return` WHERE `customer_id` = '" . $this->customer->getId() . "'");

		return (int)$query->row['total'];
	}

	public function getHistories(int $return_id): array {
		$query = $this->db->query("SELECT rh.`date_added`, rs.`name` AS status, rh.`comment` FROM `" . DB_PREFIX . "return_history` rh LEFT JOIN `" . DB_PREFIX . "return_status` rs ON (rh.`return_status_id` = rs.`return_status_id`) WHERE rh.`return_id` = '" . (int)$return_id . "' AND rs.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rh.`date_added` ASC");

		return $query->rows;
	}
}
