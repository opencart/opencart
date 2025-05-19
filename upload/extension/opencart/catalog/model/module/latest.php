<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Module;
/**
 * Class Latest
 *
 * Can be called from $this->load->model('extension/opencart/module/latest');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Module
 */
class Latest extends \Opencart\Catalog\Model\Catalog\Product {
	/**
	 * Get Latest
	 *
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_module_latest->getLatest($limit);
	 */
	public function getLatest(int $limit): array {
		$sql = "SELECT DISTINCT *, `pd`.`name`, `p`.`image`, " . $this->statement['discount'] . ", " . $this->statement['special'] . ", " . $this->statement['reward'] . ", " . $this->statement['review'] . " FROM `" . DB_PREFIX . "product_to_store` `p2s` LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `p2s`.`product_id` AND `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `p`.`status` = '1' AND `p`.`date_available` <= NOW()) LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`) WHERE `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `p`.`date_added` DESC LIMIT 0," . (int)$limit;

		$key = md5($sql);

		$product_data = $this->cache->get('product.' . $key);

		if (!$product_data) {
			$query = $this->db->query($sql);

			$product_data = $query->rows;

			$this->cache->set('product.' . $key, $product_data);
		}

		return (array)$product_data;
	}
}
