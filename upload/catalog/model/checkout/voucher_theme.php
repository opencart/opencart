<?php
namespace Opencart\Catalog\Model\Checkout;
/**
 * Class VoucherTheme
 *
 * @package Opencart\Catalog\Model\Checkout
 */
class VoucherTheme extends \Opencart\System\Engine\Model {
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
		$sql = "SELECT * FROM `" . DB_PREFIX . "voucher_theme` vt LEFT JOIN `" . DB_PREFIX . "voucher_theme_description` vtd ON (vt.`voucher_theme_id` = vtd.`voucher_theme_id`) WHERE vtd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY vtd.`name`";

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
}
