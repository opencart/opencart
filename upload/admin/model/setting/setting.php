<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Setting
 *
 * @package Opencart\Admin\Model\Setting
 */
class Setting extends \Opencart\System\Engine\Model {
	/**
	 * @param int $store_id
	 *
	 * @return array
	 */
	public function getSettings(int $store_id = 0): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' OR `store_id` = '0' ORDER BY `store_id` ASC");

		return $query->rows;
	}

	/**
	 * @param string $code
	 * @param int    $store_id
	 *
	 * @return array
	 */
	public function getSetting(string $code, int $store_id = 0): array {
		$setting_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$setting_data[$result['key']] = $result['value'];
			} else {
				$setting_data[$result['key']] = json_decode($result['value'], true);
			}
		}

		return $setting_data;
	}

	/**
	 * @param string $code
	 * @param array  $data
	 * @param int    $store_id
	 *
	 * @return void
	 */
	public function editSetting(string $code, array $data, int $store_id = 0): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($data as $key => $value) {
			if (substr($key, 0, strlen($code)) == $code) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', `serialized` = '1'");
				}
			}
		}
	}

	/**
	 * @param string $code
	 * @param int    $store_id
	 *
	 * @return void
	 */
	public function deleteSetting(string $code, int $store_id = 0): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * @param string $key
	 * @param int    $store_id
	 *
	 * @return string
	 */
	public function getValue(string $key, int $store_id = 0): string {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($key) . "'");

		if ($query->num_rows) {
			return $query->row['value'];
		} else {
			return '';
		}
	}

	/**
	 * @param string       $code
	 * @param string       $key
	 * @param string|array $value
	 * @param int          $store_id
	 *
	 * @return void
	 */
	public function editValue(string $code = '', string $key = '', string|array $value = '', int $store_id = 0): void {
		if (!is_array($value)) {
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape($value) . "', `serialized` = '0'  WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "' AND `store_id` = '" . (int)$store_id . "'");
		} else {
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode($value)) . "', `serialized` = '1' WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "' AND `store_id` = '" . (int)$store_id . "'");
		}
	}
}
