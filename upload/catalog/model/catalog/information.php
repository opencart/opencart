<?php
namespace Opencart\Catalog\Model\Catalog;
/**
 * Class Information
 *
 * Can be called using $this->load->model('catalog/information');
 *
 * @package Opencart\Catalog\Model\Catalog
 */
class Information extends \Opencart\System\Engine\Model {
	/**
	 * Get Information
	 *
	 * @param int $information_id primary key of the information record
	 *
	 * @return array<string, mixed> information record that have information ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/information');
	 *
	 * $information_info = $this->model_catalog_information->getInformation($information_id);
	 */
	public function getInformation(int $information_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "information` `i` LEFT JOIN `" . DB_PREFIX . "information_description` `id` ON (`i`.`information_id` = `id`.`information_id`) LEFT JOIN `" . DB_PREFIX . "information_to_store` `i2s` ON (`i`.`information_id` = `i2s`.`information_id`) WHERE `i`.`information_id` = '" . (int)$information_id . "' AND `id`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `i2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `i`.`status` = '1'");

		return $query->row;
	}

	/**
	 * Get Information(s)
	 *
	 * @return array<int, array<string, mixed>> information records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/information');
	 *
	 * $results = $this->model_catalog_information->getInformations();
	 */
	public function getInformations(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "information` `i` LEFT JOIN `" . DB_PREFIX . "information_description` `id` ON (`i`.`information_id` = `id`.`information_id`) LEFT JOIN `" . DB_PREFIX . "information_to_store` `i2s` ON (`i`.`information_id` = `i2s`.`information_id`) WHERE `id`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `i2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `i`.`status` = '1' ORDER BY `i`.`sort_order`, LCASE(`id`.`title`) ASC");

		return $query->rows;
	}

	/**
	 * Get Layout ID
	 *
	 * @param int $information_id primary key of the information record
	 *
	 * @return int layout record that has information ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/information');
	 *
	 * $layout_id = $this->model_catalog_information->getLayoutId($information_id);
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
