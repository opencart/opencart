<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Module;
class Bestseller extends \Opencart\Catalog\Model\Catalog\Product {
	public function getBestSeller(int $limit): array {
		// Storing some sub queries so that we are not typing them out multiple times.
		$discount = "(SELECT `pd2`.`price` FROM `" . DB_PREFIX . "product_discount` `pd2` WHERE `pd2`.`product_id` = `p`.`product_id` AND `pd2`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "'AND `pd2`.`quantity` = '1' AND ((`pd2`.`date_start` = '0000-00-00' OR `pd2`.`date_start` < NOW()) AND (`pd2`.`date_end` = '0000-00-00' OR `pd2`.`date_end` > NOW())) ORDER BY `pd2`.`priority` ASC, `pd2`.`price` ASC LIMIT 1) AS `discount`";
		$special = "(SELECT `ps`.`price` FROM `" . DB_PREFIX . "product_special` `ps` WHERE `ps`.`product_id` = `p`.`product_id` AND `ps`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((`ps`.`date_start` = '0000-00-00' OR `ps`.`date_start` < NOW()) AND (`ps`.`date_end` = '0000-00-00' OR `ps`.`date_end` > NOW())) ORDER BY `ps`.`priority` ASC, `ps`.`price` ASC LIMIT 1) AS `special`";
		$reward = "(SELECT `pr`.`points` FROM `" . DB_PREFIX . "product_reward` `pr` WHERE `pr`.`product_id` = `p`.`product_id` AND `pr`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "') AS `reward`";
		$review = "(SELECT COUNT(*) FROM `" . DB_PREFIX . "review` `r` WHERE `r`.`product_id` = `p`.`product_id` AND `r`.`status` = '1' GROUP BY `r`.`product_id`) AS `reviews`";

		$sql = "SELECT op.`product_id`, SUM(op.`quantity`) AS `total`, " . $this->statement['discount'] . ", " . $this->statement['special'] . ", " . $this->statement['reward'] . ", " . $this->statement['review'] . "
		FROM `" . DB_PREFIX . "order_product` op
		LEFT JOIN `" . DB_PREFIX . "order` o ON (op.`order_id` = o.`order_id`)
		LEFT JOIN `" . DB_PREFIX . "product` p ON (op.`product_id` = p.`product_id` AND p.`status` = '1' AND p.`date_available` <= NOW())
		LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.`product_id` = p2s.`product_id` AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "')
		WHERE o.`order_status_id` > '0'
		GROUP BY op.`product_id`
		ORDER BY `total` DESC LIMIT 0," . (int)$limit;

		$product_data = (array)$this->cache->get('product.' . md5($sql));

		//if (!$product_data) {
			$query = $this->db->query($sql);

			$product_data = $query->rows;

			$this->cache->set('product.' . md5($sql), $product_data);
		//}

		return $product_data;
	}
}
