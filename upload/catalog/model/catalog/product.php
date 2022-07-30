<?php
namespace Opencart\Catalog\Model\Catalog;
use \Opencart\System\Helper as Helper;
class Product extends \Opencart\System\Engine\Model {
	public function getProduct(int $product_id): array {
		$query = $this->db->query("SELECT DISTINCT *, pd.`name` AS name, p.`image`, m.`name` AS manufacturer, (SELECT `price` FROM `" . DB_PREFIX . "product_discount` pd2 WHERE pd2.`product_id` = p.`product_id` AND pd2.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.`quantity` = '1' AND ((pd2.`date_start` = '0000-00-00' OR pd2.`date_start` < NOW()) AND (pd2.`date_end` = '0000-00-00' OR pd2.`date_end` > NOW())) ORDER BY pd2.`priority` ASC, pd2.`price` ASC LIMIT 1) AS `discount`, (SELECT `price` FROM `" . DB_PREFIX . "product_special` ps WHERE ps.`product_id` = p.`product_id` AND ps.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.`date_start` = '0000-00-00' OR ps.`date_start` < NOW()) AND (ps.`date_end` = '0000-00-00' OR ps.`date_end` > NOW())) ORDER BY ps.`priority` ASC, ps.`price` ASC LIMIT 1) AS `special`, (SELECT `points` FROM `" . DB_PREFIX . "product_reward` pr WHERE pr.`product_id` = p.`product_id` AND pr.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "') AS `reward`, (SELECT ss.`name` FROM `" . DB_PREFIX . "stock_status` ss WHERE ss.`stock_status_id` = p.`stock_status_id` AND ss.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `stock_status`, (SELECT wcd.`unit` FROM `" . DB_PREFIX . "weight_class_description` wcd WHERE p.`weight_class_id` = wcd.`weight_class_id` AND wcd.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `weight_class`, (SELECT lcd.`unit` FROM `" . DB_PREFIX . "length_class_description` lcd WHERE p.`length_class_id` = lcd.`length_class_id` AND lcd.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(`rating`) AS `total` FROM `" . DB_PREFIX . "review` r1 WHERE r1.`product_id` = p.`product_id` AND r1.`status` = '1' GROUP BY r1.`product_id`) AS `rating`, (SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "review` r2 WHERE r2.`product_id` = p.`product_id` AND r2.`status` = '1' GROUP BY r2.`product_id`) AS `reviews`, p.`sort_order` FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.`product_id` = p2s.`product_id`) LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON (p.`manufacturer_id` = m.`manufacturer_id`) WHERE p.`product_id` = '" . (int)$product_id . "' AND pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND p.`status` = '1' AND p.`date_available` <= NOW() AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			$product_data = $query->row;

			$product_data['variant'] = (array)json_decode($query->row['variant'], true);
			$product_data['override'] = (array)json_decode($query->row['override'], true);
			$product_data['price'] = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
			$product_data['rating'] = (int)$query->row['rating'];
			$product_data['reviews'] = $query->row['reviews'] ? $query->row['reviews'] : 0;

			return $product_data;
		} else {
			return [];
		}
	}

	public function getProducts(array $data = []): array {
		$sql = "SELECT p.`product_id`, (SELECT AVG(`rating`) AS `total` FROM `" . DB_PREFIX . "review` r1 WHERE r1.`product_id` = p.`product_id` AND r1.`status` = '1' GROUP BY r1.`product_id`) AS `rating`, (SELECT `price` FROM `" . DB_PREFIX . "product_discount` pd2 WHERE pd2.`product_id` = p.`product_id` AND pd2.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.`quantity` = '1' AND ((pd2.`date_start` = '0000-00-00' OR pd2.`date_start` < NOW()) AND (pd2.`date_end` = '0000-00-00' OR pd2.`date_end` > NOW())) ORDER BY pd2.`priority` ASC, pd2.`price` ASC LIMIT 1) AS `discount`, (SELECT `price` FROM `" . DB_PREFIX . "product_special` ps WHERE ps.`product_id` = p.`product_id` AND ps.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.`date_start` = '0000-00-00' OR ps.`date_start` < NOW()) AND (ps.`date_end` = '0000-00-00' OR ps.`date_end` > NOW())) ORDER BY ps.`priority` ASC, ps.`price` ASC LIMIT 1) AS `special`";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM `" . DB_PREFIX . "category_path` cp LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (cp.`category_id` = p2c.`category_id`)";
			} else {
				$sql .= " FROM `" . DB_PREFIX . "product_to_category` p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product_filter` pf ON (p2c.`product_id` = pf.`product_id`) LEFT JOIN `" . DB_PREFIX . "product` p ON (pf.`product_id` = p.`product_id`)";
			} else {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.`product_id` = p.`product_id`)";
			}
		} else {
			$sql .= " FROM `" . DB_PREFIX . "product` p";
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.`product_id` = p2s.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND p.`status` = '1' AND p.`date_available` <= NOW() AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.`path_id` = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.`category_id` = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = [];

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.`filter_id` IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = [];

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.`name` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.`description` LIKE '" . $this->db->escape('%' . (string)$data['filter_name'] . '%') . "'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = [];

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.`tag` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.`model`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`sku`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`upc`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`ean`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`jan`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`isbn`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`mpn`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.`manufacturer_id` = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = [
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.`price` END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.`sort_order`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.`name`) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.`name`) ASC";
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

		$product_data = [];

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			// for never get one more time with same product id
			if (!isset($product_data[$result['product_id']])) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}
		}

		return $product_data;
	}

	public function getSpecials(array $data = []): array {
		$sql = "SELECT DISTINCT ps.`product_id`, (SELECT AVG(`rating`) FROM `" . DB_PREFIX . "review` r1 WHERE r1.`product_id` = ps.`product_id` AND r1.`status` = '1' GROUP BY r1.`product_id`) AS rating FROM `" . DB_PREFIX . "product_special` ps LEFT JOIN `" . DB_PREFIX . "product` p ON (ps.`product_id` = p.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.`product_id` = p2s.`product_id`) WHERE p.`status` = '1' AND p.`date_available` <= NOW() AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND ps.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.`date_start` = '0000-00-00' OR ps.`date_start` < NOW()) AND (ps.`date_end` = '0000-00-00' OR ps.`date_end` > NOW())) GROUP BY ps.`product_id`";

		$sort_data = [
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.`sort_order`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.`name`) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.`name`) ASC";
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

		$product_data = [];

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getTotalSpecials(): int {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.`product_id`) AS `total` FROM `" . DB_PREFIX . "product_special` ps LEFT JOIN `" . DB_PREFIX . "product` p ON (ps.`product_id` = p.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.`product_id` = p2s.`product_id`) WHERE p.`status` = '1' AND p.`date_available` <= NOW() AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND ps.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.`date_start` = '0000-00-00' OR ps.`date_start` < NOW()) AND (ps.`date_end` = '0000-00-00' OR ps.`date_end` > NOW()))");

		if (isset($query->row['total'])) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	public function getLatest(int $limit): array {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.`product_id` FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.`product_id` = p2s.`product_id`) WHERE p.`status` = '1' AND p.`date_available` <= NOW() AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.`product_id` DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return (array)$product_data;
	}

	public function getAttributes(int $product_id): array {
		$product_attribute_group_data = [];

		$product_attribute_group_query = $this->db->query("SELECT ag.`attribute_group_id`, agd.`name` FROM `" . DB_PREFIX . "product_attribute` pa LEFT JOIN `" . DB_PREFIX . "attribute` a ON (pa.`attribute_id` = a.`attribute_id`) LEFT JOIN `" . DB_PREFIX . "attribute_group` ag ON (a.`attribute_group_id` = ag.`attribute_group_id`) LEFT JOIN `" . DB_PREFIX . "attribute_group_description` agd ON (ag.`attribute_group_id` = agd.`attribute_group_id`) WHERE pa.`product_id` = '" . (int)$product_id . "' AND agd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.`attribute_group_id` ORDER BY ag.`sort_order`, agd.`name`");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = [];

			$product_attribute_query = $this->db->query("SELECT a.`attribute_id`, ad.`name`, pa.`text` FROM `" . DB_PREFIX . "product_attribute` pa LEFT JOIN `" . DB_PREFIX . "attribute` a ON (pa.`attribute_id` = a.`attribute_id`) LEFT JOIN `" . DB_PREFIX . "attribute_description` ad ON (a.`attribute_id` = ad.`attribute_id`) WHERE pa.`product_id` = '" . (int)$product_id . "' AND a.`attribute_group_id` = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND pa.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.`sort_order`, ad.`name`");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = [
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				];
			}

			$product_attribute_group_data[] = [
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			];
		}

		return $product_attribute_group_data;
	}

	public function getOptions(int $product_id): array {
		$product_option_data = [];

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.`option_id` = o.`option_id`) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.`option_id` = od.`option_id`) WHERE po.`product_id` = '" . (int)$product_id . "' AND od.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.`sort_order`");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = [];

			$product_option_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.`option_value_id` = ov.`option_value_id`) LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (ov.`option_value_id` = ovd.`option_value_id`) WHERE pov.`product_id` = '" . (int)$product_id . "' AND pov.`product_option_id` = '" . (int)$product_option['product_option_id'] . "' AND ovd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.`sort_order`");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = [
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				];
			}

			$product_option_data[] = [
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			];
		}

		return $product_option_data;
	}

	public function getDiscounts(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_discount` WHERE `product_id` = '" . (int)$product_id . "' AND `customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `quantity` > 1 AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) ORDER BY `quantity` ASC, `priority` ASC, `price` ASC");

		return $query->rows;
	}

	public function getImages(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_image` WHERE `product_id` = '" . (int)$product_id . "' ORDER BY `sort_order` ASC");

		return $query->rows;
	}

	public function getRelated(int $product_id): array {
		$product_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_related` pr LEFT JOIN `" . DB_PREFIX . "product` p ON (pr.`related_id` = p.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.`product_id` = p2s.`product_id`) WHERE pr.`product_id` = '" . (int)$product_id . "' AND p.`status` = '1' AND p.`date_available` <= NOW() AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->model_catalog_product->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getLayoutId(int $product_id): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_layout` WHERE `product_id` = '" . (int)$product_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT p.`product_id`) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM `" . DB_PREFIX . "category_path` cp LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (cp.`category_id` = p2c.`category_id`)";
			} else {
				$sql .= " FROM `" . DB_PREFIX . "product_to_category` p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product_filter` pf ON (p2c.`product_id` = pf.`product_id`) LEFT JOIN `" . DB_PREFIX . "product` p ON (pf.`product_id` = p.`product_id`)";
			} else {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.`product_id` = p.`product_id`)";
			}
		} else {
			$sql .= " FROM `" . DB_PREFIX . "product` p";
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.`product_id` = p2s.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND p.`status` = '1' AND p.`date_available` <= NOW() AND p2s.`store_id` = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.`path_id` = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.`category_id` = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = [];

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.`filter_id` IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = [];

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.`name` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.`description` LIKE '" . $this->db->escape('%' . (string)$data['filter_name'] . '%') . "'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = [];

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.`tag` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.`model`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`sku`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`upc`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`ean`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`jan`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`isbn`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.`mpn`) = '" . $this->db->escape(Helper\Utf8\strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.`manufacturer_id` = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	public function getSubscription(int $product_id, int $subscription_plan_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_subscription` ps LEFT JOIN `" . DB_PREFIX . "subscription_plan` sp ON (ps.`subscription_plan_id` = sp.`subscription_plan_id`) WHERE ps.`product_id` = '" . (int)$product_id . "' AND ps.`subscription_plan_id` = '" . (int)$subscription_plan_id . "' AND ps.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND sp.`status` = '1'");

		return $query->row;
	}

	public function getSubscriptions(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_subscription` ps LEFT JOIN `" . DB_PREFIX . "subscription_plan` sp ON (ps.`subscription_plan_id` = sp.`subscription_plan_id`) LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` spd ON (sp.`subscription_plan_id` = spd.`subscription_plan_id`) WHERE ps.`product_id` = '" . (int)$product_id . "' AND ps.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND spd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND sp.`status` = '1' ORDER BY sp.`sort_order` ASC");

		return $query->rows;
	}

	public function addReport(int $product_id, string $ip, string $country = ''): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product_report` SET `product_id` = '" . (int)$product_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `ip` = '" . $this->db->escape($ip) . "', `country` = '" . $this->db->escape($country) . "', `date_added` = NOW()");
	}
}
