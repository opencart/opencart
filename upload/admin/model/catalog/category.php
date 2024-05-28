<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Category
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Category extends \Opencart\System\Engine\Model {
	/**
	 * Add Category
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addCategory(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `image` = '" . $this->db->escape((string)$data['image']) . "', `parent_id` = '" . (int)$data['parent_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_modified` = NOW(), `date_added` = NOW()");

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
	 * @param int                  $category_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editCategory(int $category_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "category` SET `image` = '" . $this->db->escape((string)$data['image']) . "', `parent_id` = '" . (int)$data['parent_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_modified` = NOW() WHERE `category_id` = '" . (int)$category_id . "'");

		$this->model_catalog_category->deleteDescriptions($category_id);

		foreach ($data['category_description'] as $language_id => $category_description) {
			$this->model_catalog_category->addDescription($category_id, $language_id, $category_description);
		}

		// Old path
		$path_old = $this->model_catalog_category->getPath($category_id);

		// Delete the category paths
		$this->model_catalog_category->deletePaths($category_id);

		// Delete paths
		$results = $this->model_catalog_category->getPathsByPathId($category_id);

		foreach ($results as $result) {
			// Delete old paths
			$this->model_catalog_category->deletePathsByLevel($result['category_id'], $result['level']);
		}

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

		// Seo urls on categories need to be done differently to they include the full keyword path
		$seo_urls = [];

		$value = '';

		$this->load->model('design/seo_url');

		foreach ($paths as $path_id) {
			// Get all sub paths
			if (!$value) {
				$value = $path_id;
			} else {
				$value = $value . '_' . $path_id;
			}

			$results = $this->model_design_seo_url->getSeoUrlsByKeyValue('path', $value);

			$this->model_design_seo_url->deleteSeoUrlsByKeyValue('path', $value);

			foreach ($results as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$pos = strrpos($keyword, '/');

					if ($pos !== false) {
						$keyword = substr($keyword, $pos + 1);
					}

					$seo_urls[$store_id][$language_id][$path_id] = $keyword;
				}
			}
		}

		// Delete the old path
		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('path', $path_old);

		// Current SEO URL
		foreach ($data['category_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$seo_urls[$store_id][$language_id][$category_id] = $keyword;
			}
		}

		// All sub paths
		$filter_data = [
			'filter_key'   => 'path',
			'filter_value' => $path_old . '\_%'
		];

		$results = $this->model_design_seo_url->getSeoUrls($filter_data);

		// Delete the old SEO URL paths
		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('path', $path_old . '\_%');

		foreach ($results as $result) {
			$keyword = $result['keyword'];

			$pos = strrpos($keyword, '/');

			if ($pos !== false) {
				$keyword = substr($keyword, $pos + 1);
			}

			$seo_urls[$result['store_id']][$result['language_id']][substr($result['value'], strrpos($result['value'], '_') + 1)] = $keyword;
		}

		// Get all sub paths
		foreach ($seo_urls as $store_id => $language) {
			foreach ($language as $language_id => $path) {
				$value = '';

				$string = '';

				foreach ($path as $path_id => $keyword) {
					// Get all sub paths
					if (!$value) {
						$value = $path_id;
					} else {
						$value = $value . '_' . $path_id;
					}

					if (!$string) {
						$string = $keyword;
					} else {
						$string = $string . '/' . $keyword;
					}

					$this->model_design_seo_url->addSeoUrl('path', $value, $string, $store_id, $language_id);
				}
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
	 * @param int $category_id
	 *
	 * @return void
	 */
	public function deleteCategory(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$category_id . "'");

		$this->model_catalog_category->deleteDescriptions($category_id);
		$this->model_catalog_category->deleteFilters($category_id);
		$this->model_catalog_category->deleteStores($category_id);
		$this->model_catalog_category->deleteLayouts($category_id);

		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteCategoriesByCategoryId($category_id);

		$this->load->model('marketing/coupon');

		$this->model_marketing_coupon->deleteCategoriesByCategoryId($category_id);

		$this->load->model('design/seo_url');

		$path = $this->model_catalog_category->getPath($category_id);

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('path', $path);
		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('path', $path . '_%');

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
	 * @param int $parent_id
	 *
	 * @return void
	 */
	public function repairCategories(int $parent_id = 0): void {
		$categories = $this->model_catalog_category->getCategories(['filter_parent_id' => $parent_id]);

		// Delete the path below the current one
		foreach ($categories as $category) {
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
	 * @param int $category_id
	 *
	 * @return array<string, mixed>
	 */
	public function getCategory(int $category_id): array {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(`cd1`.`name` ORDER BY `level` SEPARATOR ' > ') FROM `" . DB_PREFIX . "category_path` `cp` LEFT JOIN `" . DB_PREFIX . "category_description` `cd1` ON (`cp`.`path_id` = cd1.`category_id` AND `cp`.`category_id` != `cp`.`path_id`) WHERE `cp`.`category_id` = `c`.`category_id` AND `cd1`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' GROUP BY `cp`.`category_id`) AS `path` FROM `" . DB_PREFIX . "category` `c` LEFT JOIN `" . DB_PREFIX . "category_description` `cd2` ON (`c`.`category_id` = `cd2`.`category_id`) WHERE `c`.`category_id` = '" . (int)$category_id . "' AND `cd2`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Categories
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getCategories(array $data = []): array {
		$sql = "SELECT `cp`.`category_id` AS `category_id`, GROUP_CONCAT(`cd1`.`name` ORDER BY `cp`.`level` SEPARATOR ' > ') AS `name`, `c1`.`parent_id`, `c1`.`sort_order`, `c1`.`status` FROM `" . DB_PREFIX . "category_path` `cp` LEFT JOIN `" . DB_PREFIX . "category` `c1` ON (`cp`.`category_id` = `c1`.`category_id`) LEFT JOIN `" . DB_PREFIX . "category` `c2` ON (`cp`.`path_id` = `c2`.`category_id`) LEFT JOIN `" . DB_PREFIX . "category_description` `cd1` ON (`cp`.`path_id` = `cd1`.`category_id`) LEFT JOIN `" . DB_PREFIX . "category_description` `cd2` ON (`cp`.`category_id` = `cd2`.`category_id`) WHERE `cd1`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `cd2`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`cd2`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		if (isset($data['filter_parent_id'])) {
			$sql .= " AND `c1`.`parent_id` = '" . (int)$data['filter_parent_id'] . "'";
		}

		$sql .= " GROUP BY `cp`.`category_id`";

		$sort_data = [
			'name',
			'sort_order'
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
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function getTotalCategories(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "category` `c` LEFT JOIN `" . DB_PREFIX . "category_description` `cd` ON (`c`.`category_id` = `cd`.`category_id`) WHERE `cd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`cd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		if (isset($data['filter_parent_id'])) {
			$sql .= " AND `c`.`parent_id` = '" . (int)$data['filter_parent_id'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * @param int                  $category_id primary key of the attribute record to be fetched
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addDescription(int $category_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `category_id` = '" . (int)$category_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "', `description` = '" . $this->db->escape($data['description']) . "', `meta_title` = '" . $this->db->escape($data['meta_title']) . "', `meta_description` = '" . $this->db->escape($data['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($data['meta_keyword']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * @param int $category_id
	 *
	 * @return void
	 */
	public function deleteDescriptions(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_description` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $category_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptions(int $category_id): array {
		$category_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_description` WHERE `category_id` = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = [
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			];
		}

		return $category_description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Add Path
	 *
	 * @param int $category_id
	 * @param int $path_id
	 * @param int $level
	 *
	 * @return void
	 */
	public function addPath(int $category_id, int $path_id, int $level): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$path_id . "', `level` = '" . (int)$level . "'");
	}

	/**
	 * Delete Paths
	 *
	 * @param int $category_id
	 *
	 * @return void
	 */
	public function deletePaths(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Paths By Level
	 *
	 * @param int $category_id
	 * @param int $level
	 *
	 * @return void
	 */
	public function deletePathsByLevel(int $category_id, int $level = 0): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category_id . "' AND `level` < '" . (int)$level . "'");
	}

	/**
	 * Get Path
	 *
	 * @param int $category_id
	 *
	 * @return string
	 */
	public function getPath(int $category_id): string {
		return implode('_', array_column($this->model_catalog_category->getPaths($category_id), 'path_id'));
	}

	/**
	 * Get Paths
	 *
	 * @param int $category_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getPaths(int $category_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category_id . "' ORDER BY `level` ASC");

		return $query->rows;
	}

	/**
	 * Get Paths By Path ID
	 *
	 * @param int $path_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getPathsByPathId(int $path_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE `path_id` = '" . (int)$path_id . "' ORDER BY `level` ASC");

		return $query->rows;
	}

	/**
	 * Add Filter
	 *
	 * @param int $category_id
	 * @param int $filter_id
	 *
	 * @return void
	 */
	public function addFilter(int $category_id, int $filter_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_filter` SET `category_id` = '" . (int)$category_id . "', `filter_id` = '" . (int)$filter_id . "'");
	}

	/**
	 * Delete Filters
	 *
	 * @param int $category_id
	 *
	 * @return void
	 */
	public function deleteFilters(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_filter` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Filters By Filter ID
	 *
	 * @param int $filter_id
	 *
	 * @return void
	 */
	public function deleteFiltersByFilterId(int $filter_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_filter` WHERE `filter_id` = '" . (int)$filter_id . "'");
	}

	/**
	 * Get Filters
	 *
	 * @param int $category_id
	 *
	 * @return array<int, int>
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
	 * @param int $category_id
	 * @param int $store_id
	 *
	 * @return void
	 */
	public function addStore(int $category_id, int $store_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '" . (int)$category_id . "', `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Delete Stores
	 *
	 * @param int $category_id
	 *
	 * @return void
	 */
	public function deleteStores(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_store` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Stores By Store ID
	 *
	 * @param int $store_id
	 *
	 * @return void
	 */
	public function deleteStoresByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_store` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Stores
	 *
	 * @param int $category_id
	 *
	 * @return array<int, int>
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
	 * @param int $category_id
	 * @param int $store_id
	 * @param int $layout_id
	 *
	 * @return void
	 */
	public function addLayout(int $category_id, int $store_id, int $layout_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_layout` SET `category_id` = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Layouts
	 *
	 * @param int $category_id
	 *
	 * @return void
	 */
	public function deleteLayouts(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_layout` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Layouts By Layout ID
	 *
	 * @param int $layout_id
	 *
	 * @return void
	 */
	public function deleteLayoutsByLayoutId(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Layouts By Store ID
	 *
	 * @param int $store_id
	 *
	 * @return void
	 */
	public function deleteLayoutsByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_layout` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Layouts
	 *
	 * @param int $category_id
	 *
	 * @return array<int, int>
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
	 * @param int $layout_id
	 *
	 * @return int
	 */
	public function getTotalLayoutsByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "category_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}
}
