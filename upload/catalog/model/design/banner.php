<?php
namespace Opencart\Catalog\Model\Design;
/**
 * Class Banner
 *
 * @package Opencart\Catalog\Model\Design
 */
class Banner extends \Opencart\System\Engine\Model {
	/**
	 * @param int $banner_id
	 *
	 * @return array
	 */
	public function getBanner(int $banner_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner` b LEFT JOIN `" . DB_PREFIX . "banner_image` bi ON (b.`banner_id` = bi.`banner_id`) WHERE b.`banner_id` = '" . (int)$banner_id . "' AND b.`status` = '1' AND bi.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bi.`sort_order` ASC");

		return $query->rows;
	}
}
