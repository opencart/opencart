<?php
namespace Opencart\Admin\Model\Sale;
/**
 * Class Voucher Theme
 *
 * @package Opencart\Admin\Model\Sale
 */
class VoucherTheme extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addVoucherTheme(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_theme` SET `image` = '" . $this->db->escape((string)$data['image']) . "'");

		$voucher_theme_id = $this->db->getLastId();

		foreach ($data['voucher_theme_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_theme_description` SET `voucher_theme_id` = '" . (int)$voucher_theme_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('voucher_theme');

		return $voucher_theme_id;
	}

	/**
	 * @param int   $voucher_theme_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editVoucherTheme(int $voucher_theme_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "voucher_theme` SET `image` = '" . $this->db->escape((string)$data['image']) . "' WHERE `voucher_theme_id` = '" . (int)$voucher_theme_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher_theme_description` WHERE `voucher_theme_id` = '" . (int)$voucher_theme_id . "'");

		foreach ($data['voucher_theme_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_theme_description` SET `voucher_theme_id` = '" . (int)$voucher_theme_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('voucher_theme');
	}

	/**
	 * @param int $voucher_theme_id
	 *
	 * @return void
	 */
	public function deleteVoucherTheme(int $voucher_theme_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher_theme` WHERE `voucher_theme_id` = '" . (int)$voucher_theme_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher_theme_description` WHERE `voucher_theme_id` = '" . (int)$voucher_theme_id . "'");

		$this->cache->delete('voucher_theme');
	}

	/**
	 * @param int $voucher_theme_id
	 *
	 * @return array
	 */
	public function getVoucherTheme(int $voucher_theme_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "voucher_theme` vt LEFT JOIN `" . DB_PREFIX . "voucher_theme_description` vtd ON (vt.`voucher_theme_id` = vtd.`voucher_theme_id`) WHERE vt.`voucher_theme_id` = '" . (int)$voucher_theme_id . "' AND vtd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getVoucherThemes(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "voucher_theme` vt LEFT JOIN `" . DB_PREFIX . "voucher_theme_description` `vtd` ON (`vt`.`voucher_theme_id` = `vtd`.`voucher_theme_id`) WHERE `vtd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `vtd`.`name`";

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$voucher_theme_data = $this->cache->get('voucher_theme.' . md5($sql));

		if (!$voucher_theme_data) {
			$query = $this->db->query($sql);

			$voucher_theme_data = $query->rows;

			$this->cache->set('voucher_theme.' . md5($sql), $voucher_theme_data);
		}

		return $voucher_theme_data;
	}

	/**
	 * @param int $voucher_theme_id
	 *
	 * @return array
	 */
	public function getDescriptions(int $voucher_theme_id): array {
		$voucher_theme_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "voucher_theme_description` WHERE `voucher_theme_id` = '" . (int)$voucher_theme_id . "'");

		foreach ($query->rows as $result) {
			$voucher_theme_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $voucher_theme_data;
	}

	/**
	 * @return int
	 */
	public function getTotalVoucherThemes(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "voucher_theme`");

		return (int)$query->row['total'];
	}
}