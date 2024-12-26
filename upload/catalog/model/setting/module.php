<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Module
 *
 * @example $module_model = $this->model_setting_module;
 *
 * Can be called from $this->load->model('setting/module');
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Module extends \Opencart\System\Engine\Model {
	/**
	 * Get Module
	 *
	 * @param int $module_id primary key of the Module record
	 *
	 * @return array<mixed> module record that has module ID
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
