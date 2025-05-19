<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Category
 *
 * Can be loaded using $this->load->model('catalog/category');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Category extends \Opencart\System\Engine\Model {
	/**
	 * Add Category
	 *
	 * Create a new category record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new category record
	 *
	 * @example
	 *
	 * $category_data = [
	 *     'category_description' => [],
	 *     'image'                => 'category_image',
	 *     'parent_id'            => 0,
	 *     'sort_order'           => 0,
	 *     'status'               => 0,
	 * ];
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $category_id = $this->model_catalog_category->addCategory($category_data);
	 */
	public function addCategory(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `image` = '" . $this->db->escape((string)$data['image']) . "', `parent_id` = '" . (int)$data['parent_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$category_id = $this->db->getLastId();

		foreach ($data['category_description'] as $language_id => $category_description) {
			$this->model_catalog_category->addDescription($category_id, $language_id, $category_description);
		}

		$level = 0;

		// MySQL Hierarchical Data Closure Table Pattern
		$results = $this->model_catalog_category->getPaths($data['parent_id']);

		foreach ($results as $result) {
			$this->model_catalog_category->addPath($category_id, $result['path_id'], $level);

			$level++;
		}

		$this->model_catalog_category->addPath($category_id, $category_id, $level);

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->model_catalog_category->addFilter($category_id, $filter_id);
			}
		}

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->model_catalog_category->addStore($category_id, $store_id);
			}
		}

		// Seo urls on categories need to be done differently to they include the full keyword path
		$parent_path = $this->model_catalog_category->getPath($data['parent_id']);

		if (!$parent_path) {
			$path = $category_id;
		} else {
			$path = $parent_path . '_' . $category_id;
		}

		// SEO
		$this->load->model('design/seo_url');

		foreach ($data['category_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyValue('path', $parent_path, $store_id, $language_id);

				if ($seo_url_info) {
					$keyword = $seo_url_info['keyword'] . '/' . $keyword;
				}

				$this->model_design_seo_url->addSeoUrl('path', $path, $keyword, $store_id, $language_id);
			}
		}

		// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->model_catalog_category->addLayout($category_id, $store_id, $layout_id);
				}
			}
		}

		return $category_id;
	}

	/**
	 * Edit Category
	 *
	 * Edit category record in the database.
	 *
	 * @param int                  $category_id primary key of the category record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $category_data = [
	 *     'category_description' => [],
	 *     'image'                => 'category_image',
	 *     'parent_id'            => 0,
	 *     'sort_order'           => 0,
	 *     'status'               => 1,
	 * ];
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->editCategory($category_id, $category_data);
	 */
	public function editCategory(int $category_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "category` SET `image` = '" . $this->db->escape((string)$data['image']) . "', `parent_id` = '" . (int)$data['parent_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `category_id` = '" . (int)$category_id . "'");

		$this->model_catalog_category->deleteDescriptions($category_id);

		foreach ($data['category_description'] as $language_id => $category_description) {
			$this->model_catalog_category->addDescription($category_id, $language_id, $category_description);
		}

		// Path
		$path_old = $this->model_catalog_category->getPath($category_id);

		$path_parent = '';

		if (!empty($data['parent_id'])) {
			$path_parent = $this->model_catalog_category->getPath($data['parent_id']);
		}

		$path_new = $path_parent ? implode('_', [$path_parent, $category_id]) : $category_id;

		// Delete the category paths
		$this->model_catalog_category->deletePaths($category_id);

		// Delete paths
		$results = $this->model_catalog_category->getPathsByPathId($category_id);

		$paths = [];

		// Build new path
		$results = $this->model_catalog_category->getPaths($data['parent_id']);

		foreach ($results as $result) {
			$paths[] = $result['path_id'];
		}

		// Get what's left of the nodes current path
		$results = $this->model_catalog_category->getPaths($category_id);

		foreach ($results as $result) {
			$paths[] = $result['path_id'];
		}

		// Combine the paths with a new level
		$level = 0;

		foreach ($paths as $path_id) {
			$this->model_catalog_category->addPath($category_id, $path_id, $level);

			$level++;
		}

		$this->model_catalog_category->addPath($category_id, $category_id, $level);

		// Clean an build new path for childs
		$this->model_catalog_category->repairCategories($category_id);

		// Seo urls on categories need to be done differently to they include the full keyword path
		$seo_urls = [];

		$this->load->model('design/seo_url');

		// Get parent category path and keywords
		$keywords_parent = [];

		if (!empty($data['parent_id'])) {
			$keywords_parent = $this->model_design_seo_url->getSeoUrlsByKeyValue('path', $path_parent);
		}

		// Build new category path and keywords based on parent
		foreach ($data['category_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				if ($path_parent) {
					$keyword = implode('/', [$keywords_parent[$store_id][$language_id], $keyword]);
				}

				$seo_urls[$store_id][$language_id][$path_new] = $keyword;
			}
		}

		// Build new child paths and keywords based on new category path and seo_url
		$keywords_old = $this->model_design_seo_url->getSeoUrlsByKeyValue('path', $path_old);

		$filter_data = [
			'filter_key'   => 'path',
			'filter_value' => $path_old . '\_%'
		];

		$results = $this->model_design_seo_url->getSeoUrls($filter_data);

		foreach ($results as $result) {
			// Replace path with new parents
			$path = implode('_', [$path_new, substr($result['value'], strlen($path_old) + 1)]);

			// Replace keyword with new parents
			$keyword = implode('/', [
				$seo_urls[$result['store_id']][$result['language_id']][$path_new], oc_substr(
					$result['keyword'],
					oc_strlen($keywords_old[$result['store_id']][$result['language_id']]) + 1
				)
			]);

			$seo_urls[$result['store_id']][$result['language_id']][$path] = $keyword;

			// Delete old childs keywords from oc_seo_url table
			$this->model_design_seo_url->deleteSeoUrlsByKeyValue('path', str_replace('_', '\_', $result['value']));
		}

		// Delete old category keywords from oc_seo_url table
		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('path', str_replace('_', '\_', $path_old));

		// Insert new keywords tree into oc_seo_url table
		foreach ($seo_urls as $store_id => $language) {
			foreach ($language as $language_id => $paths) {
				foreach ($paths as $value => $keyword) {
					$this->model_design_seo_url->addSeoUrl('path', $value, $keyword, $store_id, $language_id);
				}
			}
		}

		// Filters
		$this->model_catalog_category->deleteFilters($category_id);

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->model_catalog_category->addFilter($category_id, $filter_id);
			}
		}

		// Stores
		$this->model_catalog_category->deleteStores($category_id);

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->model_catalog_category->addStore($category_id, $store_id);
			}
		}

		// Layouts
		$this->model_catalog_category->deleteLayouts($category_id);

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->model_catalog_category->addLayout($category_id, $store_id, $layout_id);
				}
			}
		}
	}

	/**
	 * Delete Category
	 *
	 * Delete category record in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteCategory($category_id);
	 */
	public function deleteCategory(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$category_id . "'");

		$this->model_catalog_category->deleteDescriptions($category_id);
		$this->model_catalog_category->deleteFilters($category_id);
		$this->model_catalog_category->deleteStores($category_id);
		$this->model_catalog_category->deleteLayouts($category_id);

		// Product
		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteCategoriesByCategoryId($category_id);

		// Coupon
		$this->load->model('marketing/coupon');

		$this->model_marketing_coupon->deleteCategoriesByCategoryId($category_id);

		// SEO
		$this->load->model('design/seo_url');

		$path = $this->model_catalog_category->getPath($category_id);

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('path', str_replace('_', '\_', $path));
		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('path', str_replace('_', '\_', $path . '_%'));

		// Delete connected paths
		$results = $this->model_catalog_category->getPathsByPathId($category_id);

		foreach ($results as $result) {
			if ($result['category_id'] != $category_id) {
				$this->model_catalog_category->deleteCategory($result['category_id']);
			}
		}

		$this->model_catalog_category->deletePaths($category_id);

		$this->cache->delete('category');
	}

	/**
	 * Repair Categories
	 *
	 * Repair any erroneous categories that are not in the category path table.
	 *
	 * @param int $parent_id primary key of the parent category record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->repairCategories();
	 */
	public function repairCategories(int $parent_id = 0): void {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` WHERE `parent_id` = '" . (int)$parent_id . "'");

		// Delete the path below the current one
		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->model_catalog_category->deletePaths($category['category_id']);

			// Fix for records with no paths
			$level = 0;

			$paths = $this->model_catalog_category->getPaths($parent_id);

			foreach ($paths as $path) {
				$this->model_catalog_category->addPath($category['category_id'], $path['path_id'], $level);

				$level++;
			}

			$this->model_catalog_category->addPath($category['category_id'], $category['category_id'], $level);
			$this->model_catalog_category->repairCategories($category['category_id']);
		}
	}

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
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(`cd1`.`name` ORDER BY `level` SEPARATOR ' > ') FROM `" . DB_PREFIX . "category_path` `cp` LEFT JOIN `" . DB_PREFIX . "category_description` `cd1` ON (`cp`.`path_id` = cd1.`category_id` AND `cp`.`category_id` != `cp`.`path_id`) WHERE `cp`.`category_id` = `c`.`category_id` AND `cd1`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' GROUP BY `cp`.`category_id`) AS `path` FROM `" . DB_PREFIX . "category` `c` LEFT JOIN `" . DB_PREFIX . "category_description` `cd2` ON (`c`.`category_id` = `cd2`.`category_id`) WHERE `c`.`category_id` = '" . (int)$category_id . "' AND `cd2`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Categories
	 *
	 * Get the record of the category records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> category records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $results = $this->model_catalog_category->getCategories();
	 */
	public function getCategories(array $data = []): array {
		$sql = "SELECT `cp`.`category_id` AS `category_id`, `c1`.`image`, GROUP_CONCAT(`cd1`.`name` ORDER BY `cp`.`level` SEPARATOR ' > ') AS `name`, `c1`.`parent_id`, `c1`.`sort_order`, `c1`.`status` FROM `" . DB_PREFIX . "category_path` `cp` LEFT JOIN `" . DB_PREFIX . "category` `c1` ON (`cp`.`category_id` = `c1`.`category_id`) LEFT JOIN `" . DB_PREFIX . "category` `c2` ON (`cp`.`path_id` = `c2`.`category_id`) LEFT JOIN `" . DB_PREFIX . "category_description` `cd1` ON (`cp`.`path_id` = `cd1`.`category_id`) LEFT JOIN `" . DB_PREFIX . "category_description` `cd2` ON (`cp`.`category_id` = `cd2`.`category_id`) WHERE `cd1`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `cd2`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`cd2`.`name`) LIKE '" . $this->db->escape(oc_strtolower((string)$data['filter_name'])) . "'";
		}

		if (isset($data['filter_parent_id'])) {
			$sql .= " AND `c1`.`parent_id` = '" . (int)$data['filter_parent_id'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND `c1`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY `cp`.`category_id`";

		// path name filter in category list "Components > Monitors > test 1" or "Components > Monitors" or "Monitors" or "test 1"
		if (!empty($data['filter_name'])) {
			$implode = [];

			// split category path, clear > symbols and extra spaces
			$words = explode(' ', trim(preg_replace('/\s+/', ' ', str_ireplace([' &gt; ', ' > '], ' ', (string)$data['filter_name']))));

			foreach ($words as $word) {
				$implode[] = "LCASE(`name`) LIKE '" . $this->db->escape('%' . oc_strtolower($word) . '%') . "'";
			}

			if ($implode) {
				$sql .= " HAVING ((" . implode(" AND ", $implode) . ") OR LCASE(`name`) LIKE '" . $this->db->escape(oc_strtolower((string)$data['filter_name'])) . "')";
			}
		}

		$sort_data = [
			'name',
			'sort_order',
			'c1.status'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `sort_order`";
		}

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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Categories
	 *
	 * Get the total number of category records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of category records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'   => 'Filter Name',
	 *     'filter_status' => 1,
	 *     'sort'          => 'name',
	 *     'order'         => 'DESC',
	 *     'start'         => 0,
	 *     'limit'         => 10
	 * ];
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $category_total = $this->model_catalog_category->getTotalCategories($filter_data);
	 */
	public function getTotalCategories(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "category` `c` LEFT JOIN `" . DB_PREFIX . "category_description` `cd` ON (`c`.`category_id` = `cd`.`category_id`) WHERE `cd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`cd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		if (isset($data['filter_parent_id'])) {
			$sql .= " AND `c`.`parent_id` = '" . (int)$data['filter_parent_id'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND `c`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Edit Status
	 *
	 * Edit category status record in the database.
	 *
	 * @param int  $category_id primary key of the category record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->editStatus($category_id, $status);
	 */
	public function editStatus(int $category_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "category` SET `status` = '" . (bool)$status . "' WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Add Description
	 *
	 * Create a new category description record in the database.
	 *
	 * @param int                  $category_id primary key of the category record
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $category_data['category_description'] = [
	 *     'name'             => 'Category Name',
	 *     'description'      => 'Category Description',
	 *     'meta_title'       => 'Meta Title',
	 *     'meta_description' => 'Meta Description',
	 *     'meta_keyword'     => 'Meta Keyword'
	 * ];
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->addDescription($category_id, $language_id, $category_data);
	 */
	public function addDescription(int $category_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `category_id` = '" . (int)$category_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "', `description` = '" . $this->db->escape($data['description']) . "', `meta_title` = '" . $this->db->escape($data['meta_title']) . "', `meta_description` = '" . $this->db->escape($data['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($data['meta_keyword']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete category description records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteDescriptions($category_id);
	 */
	public function deleteDescriptions(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_description` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete category descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the category description records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return array<int, array<string, string>> description records that have category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $category_description = $this->model_catalog_category->getDescriptions($category_id);
	 */
	public function getDescriptions(int $category_id): array {
		$category_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_description` WHERE `category_id` = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = $result;
		}

		return $category_description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the category descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $results = $this->model_catalog_category->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Add Path
	 *
	 * Create a new category path record in the database.
	 *
	 * @param int $category_id primary key of the category record
	 * @param int $path_id     primary key of the category path record
	 * @param int $level
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->addPath($category_id, $path_id, $level);
	 */
	public function addPath(int $category_id, int $path_id, int $level): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$path_id . "', `level` = '" . (int)$level . "'");
	}

	/**
	 * Delete Paths
	 *
	 * Delete category path records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deletePaths($category_id);
	 */
	public function deletePaths(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Paths By Level
	 *
	 * Delete category path record by levels in the database.
	 *
	 * @param int $category_id primary key of the category record
	 * @param int $level
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deletePathsByLevel($category_id, $level);
	 */
	public function deletePathsByLevel(int $category_id, int $level = 0): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category_id . "' AND `level` < '" . (int)$level . "'");
	}

	/**
	 * Get Path
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $path = $this->model_catalog_category->getPath($category_id);
	 */
	public function getPath(int $category_id): string {
		return implode('_', array_column($this->model_catalog_category->getPaths($category_id), 'path_id'));
	}

	/**
	 * Get Paths
	 *
	 * Get the record of the category path records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return array<int, array<string, mixed>> path records that have category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $results = $this->model_catalog_category->getPaths($parent_id);
	 */
	public function getPaths(int $category_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category_id . "' ORDER BY `level` ASC");

		return $query->rows;
	}

	/**
	 * Get Paths By Path ID
	 *
	 * Get the record of the category paths by path records in the database.
	 *
	 * @param int $path_id primary key of the category path record
	 *
	 * @return array<int, array<string, mixed>> path records that have path ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $results = $this->model_catalog_category->getPathsByPathId($category_id);
	 */
	public function getPathsByPathId(int $path_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE `path_id` = '" . (int)$path_id . "' ORDER BY `level` ASC");

		return $query->rows;
	}

	/**
	 * Add Filter
	 *
	 * Create a new category filter record in the database.
	 *
	 * @param int $category_id primary key of the category record
	 * @param int $filter_id   primary key of the filter record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->addFilter($category_id, $filter_id);
	 */
	public function addFilter(int $category_id, int $filter_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_filter` SET `category_id` = '" . (int)$category_id . "', `filter_id` = '" . (int)$filter_id . "'");
	}

	/**
	 * Delete Filters
	 *
	 * Delete filter records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteFilters($category_id);
	 */
	public function deleteFilters(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_filter` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Filters By Filter ID
	 *
	 * Delete filters by filter records in the database.
	 *
	 * @param int $filter_id primary key of the filter record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteFiltersByFilterId($filter_id);
	 */
	public function deleteFiltersByFilterId(int $filter_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_filter` WHERE `filter_id` = '" . (int)$filter_id . "'");
	}

	/**
	 * Get Filters
	 *
	 * Get the record of the category filter records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return array<int, int> filter records that have category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $filters = $this->model_catalog_category->getFilters($category_id);
	 */
	public function getFilters(int $category_id): array {
		$category_filter_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_filter` WHERE `category_id` = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_filter_data[] = $result['filter_id'];
		}

		return $category_filter_data;
	}

	/**
	 * Add Store
	 *
	 * Create a new category store record in the database.
	 *
	 * @param int $category_id primary key of the category record
	 * @param int $store_id    primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->addStore($category_id, $store_id);
	 */
	public function addStore(int $category_id, int $store_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '" . (int)$category_id . "', `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Delete Stores
	 *
	 * Delete category store records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteStores($category_id);
	 */
	public function deleteStores(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_store` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Stores By Store ID
	 *
	 * Delete category stores by store records in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteStoresByStoreId($store_id);
	 */
	public function deleteStoresByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_store` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Stores
	 *
	 * Get the record of the category store records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return array<int, int> store records that have category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $category_store = $this->model_catalog_category->getStores($category_id);
	 */
	public function getStores(int $category_id): array {
		$category_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_to_store` WHERE `category_id` = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	/**
	 * Add Layout
	 *
	 * Create a new category layout record in the database.
	 *
	 * @param int $category_id primary key of the category record
	 * @param int $store_id    primary key of the store record
	 * @param int $layout_id   primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->addLayout($category_id, $store_id, $layout_id);
	 */
	public function addLayout(int $category_id, int $store_id, int $layout_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_layout` SET `category_id` = '" . (int)$category_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Layouts
	 *
	 * Delete category layout records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteLayouts($category_id);
	 */
	public function deleteLayouts(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_layout` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Layouts By Layout ID
	 *
	 * Delete category layouts by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteLayoutsByLayoutId($layout_id);
	 */
	public function deleteLayoutsByLayoutId(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Layouts By Store ID
	 *
	 * Delete category layouts by store records in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->deleteLayoutsByStoreId($store_id);
	 */
	public function deleteLayoutsByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_layout` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Layouts
	 *
	 * Get the record of the category layout records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return array<int, int> layout records that have category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $category_layout = $this->model_catalog_category->getLayouts($category_id);
	 */
	public function getLayouts(int $category_id): array {
		$category_layout_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_to_layout` WHERE `category_id` = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	/**
	 * Get Total Layouts By Layout ID
	 *
	 * Get the total number of category layout by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return int total number of layout records that have layout ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $category_total = $this->model_catalog_category->getTotalLayoutsByLayoutId($layout_id);
	 */
	public function getTotalLayoutsByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "category_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}
}
