<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Module;
/**
 * Class Bestseller
 *
 * Can be called from $this->load->model('extension/opencart/module/bestseller');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Module
 */
class Bestseller extends \Opencart\Catalog\Model\Catalog\Product {
	/**
	 * Get Best Sellers
	 *
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_module_bestseller->getBestSellers($limit);
	 */
	public function getBestSellers(int $limit): array {
		// Storing some sub queries so that we are not typing them out multiple times.
		$sql = "SELECT *, `pd`.`name`, `p`.`image`, `pb`.`total`, " . $this->statement['discount'] . ", " . $this->statement['special'] . ", " . $this->statement['reward'] . ", " . $this->statement['review'] . " FROM `" . DB_PREFIX . "product_bestseller` `pb` LEFT JOIN `" . DB_PREFIX . "product_to_store` `p2s` ON (`p2s`.`product_id` = `pb`.`product_id` AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "') LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `pb`.`product_id` AND `p`.`status` = '1' AND `p`.`date_available` <= NOW()) LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`pd`.`product_id` = `p`.`product_id`) WHERE `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `pb`.`total` DESC LIMIT 0," . (int)$limit;

		$key = md5($sql);

		$product_data = $this->cache->get('product.' . $key);

		if (!$product_data) {
			$query = $this->db->query($sql);

			$product_data = $query->rows;

			$this->cache->set('product.' . $key, $product_data);
		}

		return $product_data;
	}
}
