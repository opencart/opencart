<?php
namespace Opencart\Catalog\Model\Catalog;
/**
 * Class Information
 *
 * @package Opencart\Catalog\Model\Catalog
 */
class Information extends \Opencart\System\Engine\Model {
	/**
	 * @param int $information_id
	 *
	 * @return array
	 */
	public function getInformation(int $information_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "information` i LEFT JOIN `" . DB_PREFIX . "information_description` id ON (i.`information_id` = id.`information_id`) LEFT JOIN `" . DB_PREFIX . "information_to_store` i2s ON (i.`information_id` = i2s.`information_id`) WHERE i.`information_id` = '" . (int)$information_id . "' AND id.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND i2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND i.`status` = '1'");

		return $query->row;
	}

	/**
	 * @return array
	 */
	public function getInformations(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "information` i LEFT JOIN `" . DB_PREFIX . "information_description` id ON (i.`information_id` = id.`information_id`) LEFT JOIN `" . DB_PREFIX . "information_to_store` i2s ON (i.`information_id` = i2s.`information_id`) WHERE id.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND i2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND i.`status` = '1' ORDER BY i.`sort_order`, LCASE(id.`title`) ASC");

		return $query->rows;
	}

	/**
	 * @param int $information_id
	 *
	 * @return int
	 */
	public function getLayoutId(int $information_id): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "information_to_layout` WHERE `information_id` = '" . (int)$information_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}
