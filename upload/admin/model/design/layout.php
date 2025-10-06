<?php
namespace Opencart\Admin\Model\Design;
/**
 * Class Layout
 *
 * Can be loaded using $this->load->model('design/layout');
 *
 * @package Opencart\Admin\Model\Design
 */
class Layout extends \Opencart\System\Engine\Model {
	/**
	 * Add Layout
	 *
	 * Create a new layout record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new layout record
	 *
	 * @example
	 *
	 * $layout_data = [
	 *     'name' => 'Layout Name'
	 * ];
	 *
	 * $this->load->model('design/layout');
	 *
	 * $layout_id = $this->model_design_layout->addLayout($layout_data);
	 */
	public function addLayout(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "layout` SET `name` = '" . $this->db->escape((string)$data['name']) . "'");

		$layout_id = $this->db->getLastId();

		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->addRoute($layout_id, $layout_route);
			}
		}

		if (isset($data['layout_module'])) {
			foreach ($data['layout_module'] as $layout_module) {
				$this->addModule($layout_id, $layout_module);
			}
		}

		return $layout_id;
	}

	/**
	 * Edit Layout
	 *
	 * Edit layout record in the database.
	 *
	 * @param int                  $layout_id primary key of the layout record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $layout_data = [
	 *     'name' => 'Layout Name'
	 * ];
	 *
	 * $this->load->model('design/layout');
	 *
	 * $this->model_design_layout->editLayout($layout_id, $layout_data);
	 */
	public function editLayout(int $layout_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "layout` SET `name` = '" . $this->db->escape((string)$data['name']) . "' WHERE `layout_id` = '" . (int)$layout_id . "'");

		$this->deleteRoutes($layout_id);

		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->addRoute($layout_id, $layout_route);
			}
		}

		$this->deleteModules($layout_id);

		if (isset($data['layout_module'])) {
			foreach ($data['layout_module'] as $layout_module) {
				$this->addModule($layout_id, $layout_module);
			}
		}
	}

	/**
	 * Delete Layout
	 *
	 * Delete layout record in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $this->model_design_layout->deleteLayout($layout_id);
	 */
	public function deleteLayout(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		$this->deleteRoutes($layout_id);
		$this->deleteModules($layout_id);

		// Category
		$this->load->model('catalog/category');

		$this->model_catalog_category->deleteLayoutsByLayoutId($layout_id);

		// Product
		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteLayoutsByLayoutId($layout_id);

		// Information
		$this->load->model('catalog/information');

		$this->model_catalog_information->deleteLayoutsByLayoutId($layout_id);

		// Article
		$this->load->model('cms/article');

		$this->model_cms_article->deleteLayoutsByLayoutId($layout_id);

		// CMS Topic
		$this->load->model('cms/topic');

		$this->model_cms_topic->deleteLayoutsByLayoutId($layout_id);
	}

	/**
	 * Get Layout
	 *
	 * Get the record of the layout record in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return array<string, mixed> layout record that has layout ID
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $layout_info = $this->model_design_layout->getLayout($layout_id);
	 */
	public function getLayout(int $layout_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return $query->row;
	}

	/**
	 * Get Layouts
	 *
	 * Get the record of the layout records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> layout records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('design/layout');
	 *
	 * $results = $this->model_design_layout->getLayouts($filter_data);
	 */
	public function getLayouts(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "layout`";

		$sort_data = ['name'];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `name`";
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
	 * Get Total Layouts
	 *
	 * Get the total number of layout records in the database.
	 *
	 * @return int total number of layout records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('design/layout');
	 *
	 * $layout_total = $this->model_design_layout->getTotalLayouts($filter_data);
	 */
	public function getTotalLayouts(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "layout`");

		return (int)$query->row['total'];
	}

	/**
	 * Add Route
	 *
	 * Create a new layout route record in the database.
	 *
	 * @param int                  $layout_id primary key of the layout record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $layout_route_data = [
	 *     'store_id' => 1,
	 *     'route'    => ''
	 * ];
	 *
	 * $this->load->model('design/layout');
	 *
	 * $this->model_design_layout->addRoute($layout_id, $layout_route_data);
	 */
	public function addRoute(int $layout_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_route` SET `layout_id` = '" . (int)$layout_id . "', `store_id` = '" . (int)$data['store_id'] . "', `route` = '" . $this->db->escape($data['route']) . "'");
	}

	/**
	 * Delete Routes
	 *
	 * Delete layout route records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $this->model_design_layout->deleteRoutes($layout_id);
	 */
	public function deleteRoutes(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_route` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Routes By Layout ID
	 *
	 * Delete layout routes by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $this->model_design_layout->deleteRoutesByLayoutId($layout_id);
	 */
	public function deleteRoutesByLayoutId(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_route` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Routes By Store ID
	 *
	 * Delete layout routes by store records in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $this->model_design_layout->deleteRoutesByStoreId($store_id);
	 */
	public function deleteRoutesByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_route` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Routes
	 *
	 * Get the record of the layout route records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return array<int, array<string, mixed>> route records that have layout ID
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $layout_routes = $this->model_design_layout->getRoutes($layout_id);
	 */
	public function getRoutes(int $layout_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return $query->rows;
	}

	/**
	 * Get Routes By Store ID
	 *
	 * Get the record of the layout routes by store records in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return array<int, array<string, mixed>> route records that have store ID
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $results = $this->model_design_layout->getRoutesByStoreId($store_id);
	 */
	public function getRoutesByStoreId(int $store_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE `store_id` = '" . (int)$store_id . "'");

		return $query->rows;
	}

	/**
	 * Add Module
	 *
	 * Create a new layout module record in the database.
	 *
	 * @param int                  $layout_id primary key of the layout record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $layout_module_data = [
	 *     'code'       => '',
	 *     'position'   => 'top',
	 *     'sort_order' => 0
	 * ];
	 *
	 * $this->load->model('design/layout');
	 *
	 * $this->model_design_layout->addModule($layout_id, $layout_module_data);
	 */
	public function addModule(int $layout_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$layout_id . "', `code` = '" . $this->db->escape($data['code']) . "', `position` = '" . $this->db->escape($data['position']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");
	}

	/**
	 * Delete Modules
	 *
	 * Delete layout module records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $this->model_design_layout->deleteModules($layout_id);
	 */
	public function deleteModules(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Modules By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $this->model_design_layout->deleteModulesByCode($code);
	 */
	public function deleteModulesByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `code` = '" . $this->db->escape($code) . "' OR `code` LIKE '" . $this->db->escape($code . '.%') . "'");
	}

	/**
	 * Get Modules
	 *
	 * Get the record of the layout module records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return array<int, array<string, mixed>> module records that have layout ID
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $layout_modules = $this->model_design_layout->getModules($layout_id);
	 */
	public function getModules(int $layout_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_module` WHERE `layout_id` = '" . (int)$layout_id . "' ORDER BY `position` ASC, `sort_order` ASC");

		return $query->rows;
	}
}
