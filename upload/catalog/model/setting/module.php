<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Module
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Module extends \Opencart\System\Engine\Model {
	/**
	 * @param int $module_id
	 *
	 * @return array
	 */
	public function getModule(int $module_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");
		
		if ($query->row) {
			return json_decode($query->row['setting'], true);
		} else {
			return [];
		}
	}		
}
