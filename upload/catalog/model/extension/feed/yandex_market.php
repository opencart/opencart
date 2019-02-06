<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelExtensionFeedYandexMarket extends Model {
	public function getCategory() {
		$query = $this->db->query("SELECT cd.name, c.category_id, c.parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' AND c.sort_order <> '-1'");

		return $query->rows;
	}

	public function getProduct($allowed_categories, $allowed_manufacturers, $out_of_stock_id, $vendor_required = true, $bus_image = true, $bus_image_quantity = '9', $bus_main_category = true, $bus_quantity_status = false) {
		$sql = "SELECT p2c.category_id, p.product_id, p.model, p.sku, p.upc, p.ean, p.jan, p.isbn, p.mpn, p.location, p.quantity, p.stock_status_id, p.image, p.tax_class_id, pd.name, pd.description, pd.meta_title, pd.meta_description, pd.meta_keyword, pd.meta_h1,";

		if ((int)$bus_image_quantity > '1') {
			$sql .= " (SELECT SUBSTRING_INDEX(GROUP_CONCAT(pi.image ORDER BY pi.image SEPARATOR ','), ',', " . ((int)$bus_image_quantity <= "10" ? (int)$bus_image_quantity - 1 : "9") . ") FROM " . DB_PREFIX . "product_image pi WHERE pi.product_id = p2c.product_id) AS images,";
		}

		$sql .= " m.name AS manufacturer, IFNULL(ps.price, p.price) AS price FROM " . DB_PREFIX . "product_to_category AS p2c JOIN " . DB_PREFIX . "product AS p ON (p.product_id = p2c.product_id) " . ($vendor_required ? '' : 'LEFT ') . "JOIN " . DB_PREFIX . "manufacturer AS m ON (m.manufacturer_id = p.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_description AS pd ON (pd.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store AS p2s ON (p2s.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_special AS ps ON (ps.product_id = p2c.product_id) AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ps.date_start < NOW() AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())";

		if (!empty($allowed_categories)) {
			$sql .= " WHERE p2c.category_id IN (" . $this->db->escape($allowed_categories) . ")";
		} else {
			$sql .= " WHERE p2c.category_id";
		}

		if (!empty($bus_main_category)) {
			$sql .= " AND p2c.main_category = '1'";
		}

		if (!empty($allowed_manufacturers)) {
			$sql .= " AND m.manufacturer_id IN (" . $this->db->escape($allowed_manufacturers) . ")";
		}

        $sql .= " AND p.status = '1'";

		if (!empty($bus_image)) {
			$sql .= " AND p.image != ''";
		}

		$sql .= " AND p.date_available <= NOW() AND (p.quantity " . (!empty($bus_quantity_status) ? '>=' : '>') . " '0' OR p.stock_status_id != '" . (int)$out_of_stock_id . "') AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		$sql .= " GROUP BY p2c.product_id";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}