<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Module
 *
 * Can be loaded using $this->load->model('setting/module');
 *
 * @package Opencart\Admin\Model\Setting
 */
class Module extends \Opencart\System\Engine\Model {
	/**
	 * Add Module
	 *
	 * Create a new module record in the database.
	 *
	 * @param string               $code
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new module record
	 *
	 * @example
	 *
	 * $module_data = [
	 *     'name'    => 'Module Name',
	 *     'code'    => 'Module Code',
	 *     'setting' => []
	 * ];
	 *
	 * $this->load->model('setting/module');
	 *
	 * $module_id = $this->model_setting_module->addModule($code, $module_data);
	 */
	public function addModule(string $code, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "'");

		$module_id = $this->db->getLastId();

		return (int)$module_id;
	}

	/**
	 * Edit Module
	 *
	 * Edit module record in the database.
	 *
	 * @param int                  $module_id primary key of the module record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $module_data = [
	 *     'name'    => 'Module Name',
	 *     'code'    => 'Module Code',
	 *     'setting' => []
	 * ];
	 *
	 * $this->load->model('setting/module');
	 *
	 * $this->model_setting_module->editModule($module_id, $module_data);
	 */
	public function editModule(int $module_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
	}

	/**
	 * Delete Module
	 *
	 * Delete module record in the database.
	 *
	 * @param int $module_id primary key of the module record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/module');
	 *
	 * $this->model_setting_module->deleteModule($module_id);
	 */
	public function deleteModule(int $module_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");
	}

	/**
	 * Delete Modules By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/module');
	 *
	 * $this->model_setting_module->deleteModulesByCode($code);
	 */
	public function deleteModulesByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "'");

		// Layout
		$this->load->model('design/layout');

		$this->model_design_layout->deleteModulesByCode($code);
	}

	/**
	 * Get Module
	 *
	 * Get the record of the module record in the database.
	 *
	 * @param int $module_id primary key of the module record
	 *
	 * @return array<mixed> module record that has module ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/module');
	 *
	 * $module_info = $this->model_setting_module->getModule($module_id);
	 */
	public function getModule(int $module_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");

		if ($query->row) {
			return $query->row['setting'] ? json_decode($query->row['setting'], true) : [];
		} else {
			return [];
		}
	}

	/**
	 * Get Modules
	 *
	 * Get the record of the module records in the database.
	 *
	 * @return array<int, array<string, mixed>> module records
	 *
	 * @example
	 *
	 * $this->load->model('setting/module');
	 *
	 * $modules = $this->model_setting_module->getModules();
	 */
	public function getModules(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` ORDER BY `code`");

		return $query->rows;
	}

	/**
	 * Get Modules By Code
	 *
	 * @param string $code
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $this->load->model('setting/module');
	 *
	 * $modules = $this->model_setting_module->getModulesByCode($code);
	 */
	public function getModulesByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

		return $query->rows;
	}
}
