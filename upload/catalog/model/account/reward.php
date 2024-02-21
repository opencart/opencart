<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Reward
 *
 * @package Opencart\Catalog\Model\Account
 */
class Reward extends \Opencart\System\Engine\Model {
	/**
	 * Add Reward
	 *
	 * @param int    $customer_id
	 * @param int    $order_id
	 * @param string $description
	 * @param int    $points
	 *
	 * @return void
	 */
	public function addReward(int $customer_id, int $order_id, string $description, int $points): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_reward` SET `customer_id` = '" . (int)$this->customer->getId() . "', `order_id` = '" . (int)$order_id . "', `description` = '" . $this->db->escape($description) . "', `points` = '" . (int)$points . "', `date_added` = NOW()");
	}
	
	/**
	 * delete Reward
	 *
	 * @param int    $customer_id
	 *
	 * @return void
	 */
	public function deleteReward(int $customer_id, int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Get Rewards
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getRewards(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$this->customer->getId() . "'";

		$sort_data = [
			'points',
			'description',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `date_added`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Rewards
	 *
	 * @return int
	 */
	public function getTotalRewards(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$this->customer->getId() . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Reward Total
	 *
	 * @param int $customer_id
	 *
	 * @return int
	 */
	public function getRewardTotal(int $customer_id): int {
		$query = $this->db->query("SELECT SUM(`points`) AS `total` FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$customer_id . "' GROUP BY `customer_id`");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}
}
