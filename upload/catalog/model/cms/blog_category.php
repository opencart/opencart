<?php
namespace Opencart\Catalog\Model\Cms;
/**
 * Class Blog Category
 *
 * @package Opencart\Catalog\Model\Cms
 */
class BlogCategory extends \Opencart\System\Engine\Model {
	/**
	 * @param int $blog_category_id
	 *
	 * @return array
	 */
	public function getBlogCategory(int $blog_category_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "blog_category` `bc` LEFT JOIN `" . DB_PREFIX . "blog_category_description` `bcd` ON (`bc`.`blog_category_id` = `bcd`.`blog_category_id`) LEFT JOIN `" . DB_PREFIX . "blog_category_to_store` `bc2s` ON (`bc`.`blog_category_id` = `bc2s`.`blog_category_id`) WHERE `bc`.`blog_category_id` = '" . (int)$blog_category_id . "' AND `bcd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `bc2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row;
	}

	/**
	 * @return array
	 */
	public function getBlogCategories(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category` `bc` LEFT JOIN `" . DB_PREFIX . "blog_category_description` `bcd` ON (`bc`.`blog_category_id` = `bcd`.`blog_category_id`) LEFT JOIN `" . DB_PREFIX . "blog_category_to_store` `bc2s` ON (`bc`.`blog_category_id` = `bc2s`.`blog_category_id`) WHERE `bcd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `bc2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' ORDER BY `bc`.`sort_order` DESC");

		return $query->rows;
	}
}
