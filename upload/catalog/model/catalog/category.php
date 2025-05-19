<?php
namespace Opencart\Catalog\Model\Catalog;
/**
 * Class Category
 *
 * Can be called using $this->load->model('catalog/category');
 *
 * @package Opencart\Catalog\Model\Catalog
 */
class Category extends \Opencart\System\Engine\Model {
	/**
	 * Get Category
	 *
	 * Get the record of the category record in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return array<string, mixed> category record that has category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $category_info = $this->model_catalog_category->getCategory($category_id);
	 */
	public function getCategory(int $category_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "category` `c` LEFT JOIN `" . DB_PREFIX . "category_description` `cd` ON (`c`.`category_id` = `cd`.`category_id`) LEFT JOIN `" . DB_PREFIX . "category_to_store` `c2s` ON (`c`.`category_id` = `c2s`.`category_id`) WHERE `c`.`category_id` = '" . (int)$category_id . "' AND `cd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `c2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `c`.`status` = '1'");

		return $query->row;
	}

	/**
	 * Get Categories
	 *
	 * Get the record of the category records in the database.
	 *
	 * @param int $parent_id primary key of the parent category record
	 *
	 * @return array<int, array<string, mixed>> category records that have parent ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $categories = $this->model_catalog_category->getCategories();
	 */
	public function getCategories(int $parent_id = 0): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` `c` LEFT JOIN `" . DB_PREFIX . "category_description` `cd` ON (`c`.`category_id` = `cd`.`category_id`) LEFT JOIN `" . DB_PREFIX . "category_to_store` `c2s` ON (`c`.`category_id` = `c2s`.`category_id`) WHERE `c`.`parent_id` = '" . (int)$parent_id . "' AND `cd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `c2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `c`.`status` = '1' ORDER BY `c`.`sort_order`, LCASE(`cd`.`name`)");

		return $query->rows;
	}

	/**
	 * Get Filters
	 *
	 * Get the record of the category filter records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return array<int, array<string, mixed>> filter records that have category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $results = $this->model_catalog_category->getFilters($category_id);
	 */
	public function getFilters(int $category_id): array {
		$implode = [];

		$query = $this->db->query("SELECT `filter_id` FROM `" . DB_PREFIX . "category_filter` WHERE `category_id` = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$implode[] = (int)$result['filter_id'];
		}

		$filter_group_data = [];

		if ($implode) {
			$filter_group_query = $this->db->query("SELECT DISTINCT `f`.`filter_group_id`, `fgd`.`name`, `fg`.`sort_order` FROM `" . DB_PREFIX . "filter` `f` LEFT JOIN `" . DB_PREFIX . "filter_group` `fg` ON (`f`.`filter_group_id` = `fg`.`filter_group_id`) LEFT JOIN `" . DB_PREFIX . "filter_group_description` `fgd` ON (`fg`.`filter_group_id` = `fgd`.`filter_group_id`) WHERE `f`.`filter_id` IN (" . implode(',', $implode) . ") AND `fgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' GROUP BY `f`.`filter_group_id` ORDER BY `fg`.`sort_order`, LCASE(`fgd`.`name`)");

			foreach ($filter_group_query->rows as $filter_group) {
				$filter_query = $this->db->query("SELECT DISTINCT `f`.`filter_id`, `fd`.`name` FROM `" . DB_PREFIX . "filter` `f` LEFT JOIN `" . DB_PREFIX . "filter_description` `fd` ON (`f`.`filter_id` = `fd`.`filter_id`) WHERE `f`.`filter_id` IN (" . implode(',', $implode) . ") AND `f`.`filter_group_id` = '" . (int)$filter_group['filter_group_id'] . "' AND `fd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `f`.`sort_order`, LCASE(`fd`.`name`)");

				if ($filter_query->num_rows) {
					$filter_group_data[] = ['filter' => $filter_query->rows] + $filter_group;
				}
			}
		}

		return $filter_group_data;
	}

	/**
	 * Get Layout ID
	 *
	 * Get the record of the category layout record in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return int layout record that has category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $layout_id = $this->model_catalog_category->getLayoutId($category_id);
	 */
	public function getLayoutId(int $category_id): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_to_layout` WHERE `category_id` = '" . (int)$category_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}
