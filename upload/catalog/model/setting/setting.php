<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Setting
 *
 * Can be called using $this->load->model('setting/setting');
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Setting extends \Opencart\System\Engine\Model {
	/**
	 * Get Settings
	 *
	 * Get the record of the setting records in the database.
	 *
	 * @param int $store_id
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $this->load->model('setting/setting');
	 *
	 * $settings = $this->model_setting_setting->getSettings();
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
	 * Get Value
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
	public function getValue(string $key, int $store_id = 0): string {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($key) . "'");

		if ($query->num_rows) {
			return $query->row['value'];
		} else {
			return '';
		}
	}
}
