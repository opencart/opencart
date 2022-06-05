<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Module;
class Bestseller extends \Opencart\System\Engine\Model {
	public function getBestSeller(int $limit): array {
		$product_data = (array)$this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT op.`product_id`, SUM(op.`quantity`) AS `total` FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.`order_id` = o.`order_id`) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.`product_id` = p.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.`product_id` = p2s.`product_id`) WHERE o.`order_status_id` > '0' AND p.`status` = '1' AND p.`date_available` <= NOW() AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.`product_id` ORDER BY `total` DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}
}
