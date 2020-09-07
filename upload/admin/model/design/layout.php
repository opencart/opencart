<?php
namespace Opencart\Application\Model\Design;
class Layout extends \Opencart\System\Engine\Model {
	public function addLayout($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "layout SET `name` = '" . $this->db->escape((string)$data['name']) . "'");

		$layout_id = $this->db->getLastId();

		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_route` SET `layout_id` = '" . (int)$layout_id . "', `store_id` = '" . (int)$layout_route['store_id'] . "', `route` = '" . $this->db->escape($layout_route['route']) . "'");
			}
		}

		if (isset($data['layout_module'])) {
			foreach ($data['layout_module'] as $layout_module) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$layout_id . "', `code` = '" . $this->db->escape($layout_module['code']) . "', `position` = '" . $this->db->escape($layout_module['position']) . "', `sort_order` = '" . (int)$layout_module['sort_order'] . "'");
			}
		}

		return $layout_id;
	}

	public function editLayout($layout_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "layout` SET `name` = '" . $this->db->escape((string)$data['name']) . "' WHERE `layout_id` = '" . (int)$layout_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_route` WHERE `layout_id` = '" . (int)$layout_id . "'");

		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_route` SET `layout_id` = '" . (int)$layout_id . "', `store_id` = '" . (int)$layout_route['store_id'] . "', `route` = '" . $this->db->escape($layout_route['route']) . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `layout_id` = '" . (int)$layout_id . "'");

		if (isset($data['layout_module'])) {
			foreach ($data['layout_module'] as $layout_module) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$layout_id . "', `code` = '" . $this->db->escape($layout_module['code']) . "', `position` = '" . $this->db->escape($layout_module['position']) . "', `sort_order` = '" . (int)$layout_module['sort_order'] . "'");
			}
		}
	}

	public function deleteLayout($layout_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_route` WHERE `layout_id` = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `layout_id` = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "information_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	public function getLayout($layout_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return $query->row;
	}

	public function getLayouts($data = []) {
		$sql = "SELECT * FROM " . DB_PREFIX . "layout";

		$sort_data = ['name'];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getRoutes($layout_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return $query->rows;
	}

	public function getModules($layout_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_module` WHERE `layout_id` = '" . (int)$layout_id . "' ORDER BY `position` ASC, `sort_order` ASC");

		return $query->rows;
	}

	public function getTotalLayouts() {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "layout`");

		return $query->row['total'];
	}
}