<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Setting
 *
 * Can be loaded using $this->load->model('setting/setting');
 *
 * @package Opencart\Admin\Model\Setting
 */
class Setting extends \Opencart\System\Engine\Model {
	/**
	 * Get Settings
	 *
	 * Get the record of the setting records in the database.
	 *
	 * @param int $store_id
	 *
	 * @return array<int, array<string, mixed>> setting records that have store ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/setting');
	 *
	 * $results = $this->model_setting_setting->getSettings($store_id);
	 */
	public function getSettings(int $store_id = 0): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' OR `store_id` = '0' ORDER BY `store_id` ASC");

		return $query->rows;
	}

	/**
	 * Get Setting
	 *
	 * @param string $code
	 * @param int    $store_id
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('setting/setting');
	 *
	 * $setting_info = $this->model_setting_setting->getSetting($code, $store_id);
	 */
	public function getSetting(string $code, int $store_id = 0): array {
		$setting_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$setting_data[$result['key']] = $result['value'];
			} else {
				$setting_data[$result['key']] = $result['value'] ? json_decode($result['value'], true) : [];
			}
		}

		return $setting_data;
	}

	/**
	 * Edit Setting
	 *
	 * @param string               $code
	 * @param array<string, mixed> $data     array of data
	 * @param int                  $store_id
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/setting');
	 *
	 * $this->model_setting_setting->editSetting($code, $data, $store_id);
	 */
	public function editSetting(string $code, array $data, int $store_id = 0): void {
		$this->deleteSetting($code, $store_id);

		foreach ($data as $key => $value) {
			if (substr($key, 0, strlen($code)) == $code) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(!is_array($value) ? $value : json_encode($value)) . "', `serialized` = '" . (bool)is_array($value) . "'");
			}
		}
	}

	/**
	 * Delete Setting
	 *
	 * @param string $code
	 * @param int    $store_id
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/setting');
	 *
	 * $this->model_setting_setting->deleteSetting($code, $store_id);
	 */
	public function deleteSetting(string $code, int $store_id = 0): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Delete Settings By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/setting');
	 *
	 * $this->model_setting_setting->deleteSettingsByCode($code);
	 */
	public function deleteSettingsByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Delete Settings By Store ID
	 *
	 * @param int $store_id
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/setting');
	 *
	 * $this->model_setting_setting->deleteSettingsByStoreId($store_id);
	 */
	public function deleteSettingsByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Value
	 *
	 * Get the record of the setting value record in the database.
	 *
	 * @param string $key
	 * @param int    $store_id
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $this->load->model('setting/setting');
	 *
	 * $value = $this->model_setting_setting->getValue($key, $store_id);
	 */
	public function getValue(string $key, int $store_id = 0) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($key) . "'");

		if (!$query->num_rows) {
			return '';
		}

		if (!$query->row['serialized']) {
			return $query->row['value'];
		} else {
			return $query->row['value'] ? json_decode($query->row['value'], true) : [];
		}
	}

	/**
	 * Edit Value
	 *
	 * Edit setting value record in the database.
	 *
	 * @param string              $code
	 * @param string              $key
	 * @param array<mixed>|string $value
	 * @param int                 $store_id
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/setting');
	 *
	 * $this->model_setting_setting->editValue($code, $key, $value, $store_id);
	 */
	public function editValue(string $code = '', string $key = '', $value = '', int $store_id = 0): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(!is_array($value) ? $value : json_encode($value)) . "', `serialized` = '" . (bool)is_array($value) . "' WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "' AND `store_id` = '" . (int)$store_id . "'");
	}
}
