<?php
namespace Opencart\Catalog\Model\Design;
/**
 * Class Banner
 *
 * @package Opencart\Catalog\Model\Design
 */
class Popup extends \Opencart\System\Engine\Model {
	/**
	 *
	 * @return array
	 */
	public function getActivePopup(): array {
		$query = $this->db->query(
			"SELECT p.*, pc.header, pc.content, pc.language_id FROM `" . DB_PREFIX . "popup` p " .
			"LEFT JOIN `" . DB_PREFIX . "popup_content` pc " .
			"ON (pc.`popup_id` = pc.`popup_id`) " .
			"WHERE p.`status` = '1'" .
			" AND p.`store_id` = '". (int)$this->config->get('config_store_id') ."'" .
			" AND pc.`language_id` = '" . (int)$this->config->get('config_language_id') . "' " .
			"LIMIT 1");
		return $query->row;
	}
}
