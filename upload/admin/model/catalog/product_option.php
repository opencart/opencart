<?php
class ModelCatalogProductOption extends Model {
	public function addProductOption($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option` SET product_id = '" . (int)$data['product_id'] . "', option_id = '" . (int)$data['option_id'] . "', value = '" . $this->db->escape(isset($data['value']) ? $data['value'] : '') . "', required = '" . (int)$data['required'] . "'");

		$product_option_id = $this->db->getLastId();

		if (isset($data['product_option_value'])) {
			foreach ($data['product_option_value'] as $product_option_value) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option_value` SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$data['product_id'] . "', option_id = '" . (int)$data['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
			}
		}

		$this->cache->delete('product');
	}

	public function editProductOption($product_option_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "product_option` SET product_id = '" . (int)$data['product_id'] . "', option_id = '" . (int)$data['option_id'] . "', value = '" . $this->db->escape(isset($data['value']) ? $data['value'] : '') . "', required = '" . (int)$data['required'] . "' WHERE product_option_id = '" . (int)$product_option_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option_id . "'");

		if (isset($data['product_option_value'])) {
			foreach ($data['product_option_value'] as $product_option_value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$data['product_id'] . "', option_id = '" . (int)$data['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
			}
		}

		$this->cache->delete('product');
	}

	public function deleteProductOption($product_option_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = '" . (int)$product_option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option_id . "'");

		$this->cache->delete('product');
	}

	public function getProductOption($product_option_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS `product`, od.name AS `option` FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "product` p ON (po.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductOptions($data = array()) {
		$sql = "SELECT *, pd.name AS `product`, od.name AS `option`, o.type, o.sort_order FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "product` p ON (po.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product_id'])) {
			$sql .= " AND po.product_id = '" . $this->db->escape((string)$data['filter_product_id']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape((string)$data['filter_product']) . "%'";
		}

		if (!empty($data['filter_option'])) {
			$sql .= " AND od.name LIKE '" . $this->db->escape((string)$data['filter_option']) . "%'";
		}

		$sort_data = array(
			'pd.name',
			'od.name',
			'o.type',
			'o.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

	public function getTotalProductOptions($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (po.product_id = pd.product_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (po.option_id = od.option_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product_id'])) {
			$sql .= " AND po.product_id = '" . $this->db->escape((string)$data['filter_product_id']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape((string)$data['filter_product']) . "%'";
		}

		if (!empty($data['filter_option'])) {
			$sql .= " AND od.name LIKE '" . $this->db->escape((string)$data['filter_option']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProductOptionsByProductId($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON(pov.option_value_id = ov.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY ov.sort_order ASC");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id' => $product_option_value['option_value_id'],
					'quantity' => $product_option_value['quantity'],
					'subtract' => $product_option_value['subtract'],
					'price' => $product_option_value['price'],
					'price_prefix' => $product_option_value['price_prefix'],
					'points' => $product_option_value['points'],
					'points_prefix' => $product_option_value['points_prefix'],
					'weight' => $product_option_value['weight'],
					'weight_prefix' => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id' => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id' => $product_option['option_id'],
				'name' => $product_option['name'],
				'type' => $product_option['type'],
				'value' => $product_option['value'],
				'required' => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductOptionValue($product_id, $product_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductOptionValues($product_option_id) {
		$query = $this->db->query("SELECT product_option_value_id, option_value_id, quantity, subtract, price, price_prefix, points, points_prefix, weight, weight_prefix FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option_id . "'");

		return $query->rows;
	}

	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getProductsOptionValueByOptionId($option_id) {
		$query = $this->db->query("SELECT DISTINCT option_value_id FROM " . DB_PREFIX . "product_option_value WHERE option_id = '" . (int)$option_id . "'");

		return $query->rows;
	}
}