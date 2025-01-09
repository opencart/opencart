<?php
namespace Opencart\Catalog\Model\Catalog;
/**
 * Class Product
 *
 * Can be called using $this->load->model('catalog/product');
 *
 * @package Opencart\Catalog\Model\Catalog
 */
class Product extends \Opencart\System\Engine\Model {
	/**
	 * @var array<string, string>
	 */
	protected array $statement = [];

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->registry = $registry;

		// Storing some sub queries so that we are not typing them out multiple times.
		$this->statement['discount'] = "(SELECT (CASE WHEN `pd2`.`type` = 'P' THEN (`pd2`.`price` * (`p`.`price` / 100)) WHEN `pd2`.`type` = 'S' THEN (`p`.`price` - `pd2`.`price`) ELSE `pd2`.`price` END) FROM `" . DB_PREFIX . "product_discount` `pd2` WHERE `pd2`.`product_id` = `p`.`product_id` AND `pd2`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `pd2`.`quantity` = '1' AND `pd2`.`special` = '0' AND ((`pd2`.`date_start` = '0000-00-00' OR `pd2`.`date_start` < NOW()) AND (`pd2`.`date_end` = '0000-00-00' OR `pd2`.`date_end` > NOW())) ORDER BY `pd2`.`priority` ASC, `pd2`.`price` ASC LIMIT 1) AS `discount`";
		$this->statement['special'] = "(SELECT (CASE WHEN `ps`.`type` = 'P' THEN (`ps`.`price` * (`p`.`price` / 100)) WHEN `ps`.`type` = 'S' THEN (`p`.`price` - `ps`.`price`) ELSE `ps`.`price` END) FROM `" . DB_PREFIX . "product_discount` `ps` WHERE `ps`.`product_id` = `p`.`product_id` AND `ps`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `ps`.`quantity` = '1' AND `ps`.`special` = '1' AND ((`ps`.`date_start` = '0000-00-00' OR `ps`.`date_start` < NOW()) AND (`ps`.`date_end` = '0000-00-00' OR `ps`.`date_end` > NOW())) ORDER BY `ps`.`priority` ASC, `ps`.`price` ASC LIMIT 1) AS `special`";
		$this->statement['reward'] = "(SELECT `pr`.`points` FROM `" . DB_PREFIX . "product_reward` `pr` WHERE `pr`.`product_id` = `p`.`product_id` AND `pr`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "') AS `reward`";
		$this->statement['review'] = "(SELECT COUNT(*) FROM `" . DB_PREFIX . "review` `r` WHERE `r`.`product_id` = `p`.`product_id` AND `r`.`status` = '1' GROUP BY `r`.`product_id`) AS `reviews`";
	}

	/**
	 * Edit Product Quantity
	 *
	 * @param int                  $product_id
	 * @param int                  $quantity
	 * @param array<string, mixed> $data       array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $this->model_catalog_product->editQuantity($product_id, $quantity);
	 */
	public function editQuantity(int $product_id, int $quantity): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = '" . (int)$quantity . "' WHERE `product_id` = '" . (int)$product_id . "'");
	}

	/**
	 * Get Product
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return array<string, mixed> product record that has product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $product_info = $this->model_catalog_product->getProduct($product_id);
	 */
	public function getProduct(int $product_id): array {
		$query = $this->db->query("SELECT DISTINCT *, `pd`.`name`, `p`.`image`, " . $this->statement['discount'] . ", " . $this->statement['special'] . ", " . $this->statement['reward'] . ", " . $this->statement['review'] . " FROM `" . DB_PREFIX . "product_to_store` `p2s` LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `p2s`.`product_id` AND `p`.`status` = '1' AND `p`.`date_available` <= NOW()) LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`) WHERE `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `p2s`.`product_id` = '" . (int)$product_id . "' AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$product_data = $query->row;

			$product_data['variant'] = (array)json_decode($query->row['variant'], true);
			$product_data['override'] = (array)json_decode($query->row['override'], true);
			$product_data['price'] = (float)($query->row['discount'] ?: $query->row['price']);
			$product_data['rating'] = (int)$query->row['rating'];
			$product_data['reviews'] = (int)$query->row['reviews'] ? $query->row['reviews'] : 0;

			return $product_data;
		} else {
			return [];
		}
	}

	/**
	 * Get Products
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> product records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $products = $this->model_catalog_product->getProducts();
	 */
	public function getProducts(array $data = []): array {
		$sql = "SELECT DISTINCT *, `pd`.`name`, `p`.`image`, " . $this->statement['discount'] . ", " . $this->statement['special'] . ", " . $this->statement['reward'] . ", " . $this->statement['review'];

		if (!empty($data['filter_category_id'])) {
			$sql .= " FROM `" . DB_PREFIX . "category_to_store` `c2s`";

			if (!empty($data['filter_sub_category'])) {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "category_path` `cp` ON (`cp`.`category_id` = `c2s`.`category_id` AND `c2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "') LEFT JOIN `" . DB_PREFIX . "product_to_category` `p2c` ON (`p2c`.`category_id` = `cp`.`category_id`)";
			} else {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product_to_category` `p2c` ON (`p2c`.`category_id` = `c2s`.`category_id` AND `c2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "')";
			}

			$sql .= " LEFT JOIN `" . DB_PREFIX . "product_to_store` `p2s` ON (`p2s`.`product_id` = `p2c`.`product_id` AND `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "')";

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product_filter` `pf` ON (`pf`.`product_id` = `p2s`.`product_id`) LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `pf`.`product_id` AND `p`.`status` = '1' AND `p`.`date_available` <= NOW())";
			} else {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `p2s`.`product_id` AND `p`.`status` = '1' AND `p`.`date_available` <= NOW())";
			}
		} else {
			$sql .= " FROM `" . DB_PREFIX . "product_to_store` `p2s` LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `p2s`.`product_id` AND `p`.`status` = '1' AND `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `p`.`date_available` <= NOW())";
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`) WHERE `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND `cp`.`path_id` = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND `p2c`.`category_id` = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = [];

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND `pf`.`filter_id` IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_search']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_search'])) {
				$implode = [];

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_search'])));
				$words = array_filter($words);

				foreach ($words as $word) {
					$implode[] = "`pd`.`name` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
				}

				if ($implode) {
					$sql .= " (" . implode(" OR ", $implode) . ")";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR `pd`.`description` LIKE '" . $this->db->escape('%' . (string)$data['filter_search'] . '%') . "'";
				}
			}

			if (!empty($data['filter_search']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = [];

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));
				$words = array_filter($words);

				foreach ($words as $word) {
					$implode[] = "`pd`.`tag` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
				}

				if ($implode) {
					$sql .= " (" . implode(" OR ", $implode) . ")";
				}
			}

			if (!empty($data['filter_search'])) {
				$sql .= " OR LCASE(`p`.`model`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`sku`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`upc`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`ean`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`jan`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`isbn`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`mpn`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND `p`.`manufacturer_id` = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY `p`.`product_id`";

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
				$sql .= " ORDER BY (CASE WHEN `special` IS NOT NULL THEN `special` CASE WHEN `discount` IS NOT NULL THEN `discount` ELSE `p`.`price` END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY `p`.`sort_order`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(`pd`.`name`) DESC";
		} else {
			$sql .= " ASC, LCASE(`pd`.`name`) ASC";
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

		$key = md5($sql);

		$product_data = $this->cache->get('product.' . $key);

		if (!$product_data) {
			$query = $this->db->query($sql);

			$product_data = $query->rows;

			$this->cache->set('product.' . $key, $product_data);
		}

		return $product_data;
	}

	/**
	 * Get Total Products
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of product records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $product_total = $this->model_catalog_product->getTotalProducts();
	 */
	public function getTotalProducts(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT `p`.`product_id`) AS `total`";

		if (!empty($data['filter_category_id'])) {
			$sql .= " FROM `" . DB_PREFIX . "category_to_store` `c2s`";

			if (!empty($data['filter_sub_category'])) {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "category_path` `cp` ON (`cp`.`category_id` = `c2s`.`category_id` AND `c2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "') LEFT JOIN `" . DB_PREFIX . "product_to_category` `p2c` ON (`p2c`.`category_id` = `cp`.`category_id`)";
			} else {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product_to_category` `p2c` ON (`p2c`.`category_id` = `c2s`.`category_id`)";
			}

			$sql .= " LEFT JOIN `" . DB_PREFIX . "product_to_store` `p2s` ON (`p2s`.`product_id` = `p2c`.`product_id`)";

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product_filter` `pf` ON (`pf`.`product_id` = `p2s`.`product_id`) LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `pf`.`product_id` AND `p`.`status` = '1' AND `p`.`date_available` <= NOW())";
			} else {
				$sql .= " LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `p2s`.`product_id` AND `p`.`status` = '1' AND `p`.`date_available` <= NOW() AND `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "')";
			}
		} else {
			$sql .= " FROM `" . DB_PREFIX . "product_to_store` `p2s` LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `p2s`.`product_id` AND `p`.`status` = '1' AND `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `p`.`date_available` <= NOW())";
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`) WHERE `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND `cp`.`path_id` = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND `p2c`.`category_id` = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = [];

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND `pf`.`filter_id` IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_search']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_search'])) {
				$implode = [];

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_search'])));
				$words = array_filter($words);

				foreach ($words as $word) {
					$implode[] = "`pd`.`name` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
				}

				if ($implode) {
					$sql .= " (" . implode(" OR ", $implode) . ")";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR `pd`.`description` LIKE '" . $this->db->escape('%' . (string)$data['filter_search'] . '%') . "'";
				}
			}

			if (!empty($data['filter_search']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = [];

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));
				$words = array_filter($words);

				foreach ($words as $word) {
					$implode[] = "`pd`.`tag` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
				}

				if ($implode) {
					$sql .= " (" . implode(" OR ", $implode) . ")";
				}
			}

			if (!empty($data['filter_search'])) {
				$sql .= " OR LCASE(`p`.`model`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`sku`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`upc`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`ean`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`jan`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`isbn`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
				$sql .= " OR LCASE(`p`.`mpn`) = '" . $this->db->escape(oc_strtolower($data['filter_search'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND `p`.`manufacturer_id` = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Categories
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return array<int, array<string, mixed>> category records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $categories = $this->model_catalog_product->getCategories($product_id);
	 */
	public function getCategories(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Categories By Category ID
	 *
	 * @param int $product_id  primary key of the product record
	 * @param int $category_id primary key of the category record
	 *
	 * @return array<string, mixed> category record that has product ID, category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $categories = $this->model_catalog_product->getCategoriesByCategoryId($product_id, $category_id);
	 */
	public function getCategoriesByCategoryId(int $product_id, int $category_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product_id . "' AND `category_id` = '" . (int)$category_id . "'");

		return $query->row;
	}

	/**
	 * Get Total Categories By Category ID
	 *
	 * @param int $product_id  primary key of the product record
	 * @param int $category_id primary key of the category record
	 *
	 * @return int total number of product category records that have product ID, category ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $category_total = $this->model_catalog_product->getTotalCategoriesByCategoryId($product_id, $category_id);
	 */
	public function getTotalCategoriesByCategoryId(int $product_id, int $category_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_to_category` WHERE `category_id` = '" . (int)$category_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Attributes
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return array<int, array<string, mixed>> attribute records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $attribute_groups = $this->model_catalog_product->getAttributes($product_id);
	 */
	public function getAttributes(int $product_id): array {
		$product_attribute_group_data = [];

		$product_attribute_group_query = $this->db->query("SELECT `ag`.`attribute_group_id`, `agd`.`name` FROM `" . DB_PREFIX . "product_attribute` `pa` LEFT JOIN `" . DB_PREFIX . "attribute` `a` ON (`pa`.`attribute_id` = `a`.`attribute_id`) LEFT JOIN `" . DB_PREFIX . "attribute_group` `ag` ON (`a`.`attribute_group_id` = `ag`.`attribute_group_id`) LEFT JOIN `" . DB_PREFIX . "attribute_group_description` `agd` ON (`ag`.`attribute_group_id` = `agd`.`attribute_group_id`) WHERE `pa`.`product_id` = '" . (int)$product_id . "' AND `agd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' GROUP BY `ag`.`attribute_group_id` ORDER BY `ag`.`sort_order`, `agd`.`name`");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_query = $this->db->query("SELECT `a`.`attribute_id`, `ad`.`name`, `pa`.`text` FROM `" . DB_PREFIX . "product_attribute` `pa` LEFT JOIN `" . DB_PREFIX . "attribute` `a` ON (`pa`.`attribute_id` = `a`.`attribute_id`) LEFT JOIN `" . DB_PREFIX . "attribute_description` `ad` ON (`a`.`attribute_id` = `ad`.`attribute_id`) WHERE `pa`.`product_id` = '" . (int)$product_id . "' AND `a`.`attribute_group_id` = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `pa`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `a`.`sort_order`, `ad`.`name`");

			$product_attribute_group_data[] = ['attribute' => $product_attribute_query->rows] + $product_attribute_group;
		}

		return $product_attribute_group_data;
	}

	/**
	 * Edit Option Quantity
	 *
	 * @param int $product_id              primary key of the product record
	 * @param int $product_option_id       primary key of the product option record
	 * @param int $product_option_value_id primary key of the product option value record
	 * @param int $quantity
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $this->model_catalog_product->editOptionQuantity($product_id, $product_option_id, $product_option_value_id, $quantity);
	 */
	public function editOptionQuantity(int $product_id, int $product_option_id, int $product_option_value_id, int $quantity): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `quantity` = '" . (int)$quantity . "' WHERE `product_id` = '" . (int)$product_id . "' AND `product_option_id` = '" . (int)$product_option_id . "' AND `product_option_value_id` = '" . (int)$product_option_value_id . "'");
	}

	/**
	 * Get Option
	 *
	 * @param int $product_id        primary key of the product record
	 * @param int $product_option_id primary key of the product option record
	 *
	 * @return array<string, mixed> option record that has product ID, product option ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $product_option = $this->model_catalog_product->getOption($product_id, $product_option_id);
	 */
	public function getOption(int $product_id, int $product_option_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` `po` LEFT JOIN `" . DB_PREFIX . "option` `o` ON (`po`.`option_id` = `o`.`option_id`) LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`o`.`option_id` = `od`.`option_id`) WHERE `po`.`product_id` = '" . (int)$product_id . "' AND `po`.`product_option_id` = '" . (int)$product_option_id . "' AND `od`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Options
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return array<int, array<string, mixed>> option records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $product_options = $this->model_catalog_product->getOptions($product_id);
	 */
	public function getOptions(int $product_id): array {
		$product_option_data = [];

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` `po` LEFT JOIN `" . DB_PREFIX . "option` `o` ON (`po`.`option_id` = `o`.`option_id`) LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`o`.`option_id` = `od`.`option_id`) WHERE `po`.`product_id` = '" . (int)$product_id . "' AND `od`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `o`.`sort_order`");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_data[] = $product_option + ['product_option_value' => $this->getOptionValues($product_id, $product_option['product_option_id'])];
		}

		return $product_option_data;
	}

	/**
	 * Get Option Value
	 *
	 * @param int $product_id              primary key of the product record
	 * @param int $product_option_value_id primary key of the product option value record
	 *
	 * @return array<string, mixed> option value record that has product ID, product option value ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $product_option_value_info = $this->model_catalog_product->getOptionValue($product_id, $product_option_value_id);
	 */
	public function getOptionValue(int $product_id, int $product_option_value_id): array {
		$query = $this->db->query("SELECT `pov`.`option_value_id`, `ovd`.`name`, `pov`.`quantity`, `pov`.`subtract`, `pov`.`price`, `pov`.`price_prefix`, `pov`.`points`, `pov`.`points_prefix`, `pov`.`weight`, `pov`.`weight_prefix` FROM `" . DB_PREFIX . "product_option_value` `pov` LEFT JOIN `" . DB_PREFIX . "option_value` `ov` ON (`pov`.`option_value_id` = `ov`.`option_value_id`) LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ov`.`option_value_id` = `ovd`.`option_value_id`) WHERE `pov`.`product_id` = '" . (int)$product_id . "' AND `pov`.`product_option_value_id` = '" . (int)$product_option_value_id . "' AND `ovd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Option Values
	 *
	 * @param int $product_id        primary key of the product record
	 * @param int $product_option_id primary key of the product option record
	 *
	 * @return array<string, mixed> option value records that have product ID, product option ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $product_option_values = $this->model_catalog_product->getOptionValues($product_id, $product_option_id);
	 */
	public function getOptionValues(int $product_id, int $product_option_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_value` `pov` LEFT JOIN `" . DB_PREFIX . "option_value` `ov` ON (`pov`.`option_value_id` = `ov`.`option_value_id`) LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ov`.`option_value_id` = `ovd`.`option_value_id`) WHERE `pov`.`product_id` = '" . (int)$product_id . "' AND `pov`.`product_option_id` = '" . (int)$product_option_id . "' AND `ovd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `ov`.`sort_order`");

		return $query->rows;
	}

	/**
	 * Get Discounts
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return array<int, array<string, mixed>> discount records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $discounts = $this->model_catalog_product->getDiscounts($product_id);
	 */
	public function getDiscounts(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_discount` WHERE `product_id` = '" . (int)$product_id . "' AND `customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `quantity` > '1' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) ORDER BY `quantity` ASC, `priority` ASC, `price` ASC");

		return $query->rows;
	}

	/**
	 * Get Images
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return array<int, array<string, mixed>> image records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $results = $this->model_catalog_product->getImages($product_id);
	 */
	public function getImages(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_image` WHERE `product_id` = '" . (int)$product_id . "' ORDER BY `sort_order` ASC");

		return $query->rows;
	}

	/**
	 * Get Subscription
	 *
	 * @param int $product_id           primary key of the product record
	 * @param int $subscription_plan_id primary key of the subscription plan record
	 *
	 * @return array<string, mixed> subscription record that has product ID, subscription plan ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $product_subscription_info = $this->model_catalog_product->getSubscription($product_id, $subscription_plan_id);
	 */
	public function getSubscription(int $product_id, int $subscription_plan_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_subscription` `ps` LEFT JOIN `" . DB_PREFIX . "subscription_plan` `sp` ON (`ps`.`subscription_plan_id` = `sp`.`subscription_plan_id`) WHERE `ps`.`product_id` = '" . (int)$product_id . "' AND `ps`.`subscription_plan_id` = '" . (int)$subscription_plan_id . "' AND `ps`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `sp`.`status` = '1'");

		return $query->row;
	}

	/**
	 * Get Subscriptions
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return array<int, array<string, mixed>> subscription records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $subscriptions = $this->model_catalog_product->getSubscriptions($product_id);
	 */
	public function getSubscriptions(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_subscription` `ps` LEFT JOIN `" . DB_PREFIX . "subscription_plan` `sp` ON (`ps`.`subscription_plan_id` = `sp`.`subscription_plan_id`) LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` `spd` ON (`sp`.`subscription_plan_id` = `spd`.`subscription_plan_id`) WHERE `ps`.`product_id` = '" . (int)$product_id . "' AND `ps`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `spd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `sp`.`status` = '1' ORDER BY `sp`.`sort_order` ASC");

		return $query->rows;
	}

	/**
	 * Get Layout ID
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return int layout record that has product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $layout_id = $this->model_catalog_product->getLayoutId($product_id);
	 */
	public function getLayoutId(int $product_id): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_layout` WHERE `product_id` = '" . (int)$product_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}

	/**
	 * Get Related
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return array<int, array<string, mixed>> related records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $results = $this->model_catalog_product->getRelated($product_id);
	 */
	public function getRelated(int $product_id): array {
		$sql = "SELECT DISTINCT *, `pd`.`name` AS `name`, `p`.`image`, " . $this->statement['discount'] . ", " . $this->statement['special'] . ", " . $this->statement['reward'] . ", " . $this->statement['review'] . " FROM `" . DB_PREFIX . "product_related` `pr` LEFT JOIN `" . DB_PREFIX . "product_to_store` `p2s` ON (`p2s`.`product_id` = `pr`.`product_id` AND `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "') LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `pr`.`related_id` AND `p`.`status` = '1' AND `p`.`date_available` <= NOW()) LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`) WHERE `pr`.`product_id` = '" . (int)$product_id . "' AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$key = md5($sql);

		$product_data = $this->cache->get('product.' . $key);

		if (!$product_data) {
			$query = $this->db->query($sql);

			$product_data = $query->rows;

			$this->cache->set('product.' . $key, $product_data);
		}

		return (array)$product_data;
	}

	/**
	 * Get Specials
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> special records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $results = $this->model_catalog_product->getSpecials();
	 */
	public function getSpecials(array $data = []): array {
		$sql = "SELECT *, `p`.`price`, `ps`.`price` as `special`, " . $this->statement['discount'] . ", " . $this->statement['reward'] . ", " . $this->statement['review'] . " FROM (SELECT DISTINCT * FROM `" . DB_PREFIX . "product_discount` WHERE `customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `quantity` = '1' AND `special` = '1' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) GROUP BY `product_id` ORDER BY `priority` ASC) `ps` LEFT JOIN `" . DB_PREFIX . "product_to_store` `p2s` ON (`ps`.`product_id` = `p2s`.`product_id`) LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p2s`.`product_id` = `p`.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`) WHERE `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `p`.`status` = '1' AND `p`.`date_available` <= NOW()";

		$sort_data = [
			'pd.name',
			'p.model',
			'p.price',
			'rating',
			'p.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN `special` IS NOT NULL THEN `special` WHEN `discount` IS NOT NULL THEN `discount` ELSE `p`.`price` END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY `p`.`sort_order`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(`pd`.`name`) DESC";
		} else {
			$sql .= " ASC, LCASE(`pd`.`name`) ASC";
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

		$key = md5($sql);

		$product_data = $this->cache->get('product.' . $key);

		if (!$product_data) {
			$query = $this->db->query($sql);

			$product_data = $query->rows;

			$this->cache->set('product.' . $key, $product_data);
		}

		return (array)$product_data;
	}

	/**
	 * Get Total Specials
	 *
	 * @return int total number of special records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $special_total = $this->model_catalog_product->getTotalSpecials();
	 */
	public function getTotalSpecials(): int {
		$query = $this->db->query("SELECT COUNT(`ps`.`product_id`) AS `total` FROM (SELECT DISTINCT * FROM `" . DB_PREFIX . "product_discount` WHERE `customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `quantity` = '1' AND `special` = '1' AND ((`date_end` = '0000-00-00' OR `date_end` > NOW()) AND (`date_start` = '0000-00-00' OR `date_start` < NOW())) GROUP BY `product_id`) `ps` LEFT JOIN `" . DB_PREFIX . "product_to_store` `p2s` ON (`ps`.`product_id` = `p2s`.`product_id`) LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p2s`.`product_id` = `p`.`product_id`) WHERE `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `p`.`status` = '1' AND `p`.`date_available` <= NOW()");

		if (isset($query->row['total'])) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * Add Report
	 *
	 * @param int    $product_id primary key of the product record
	 * @param string $ip
	 * @param string $country
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/product');
	 *
	 * $this->model_catalog_product->addReport($product_id, $ip, $country);
	 */
	public function addReport(int $product_id, string $ip, string $country = ''): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product_report` SET `product_id` = '" . (int)$product_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `ip` = '" . $this->db->escape($ip) . "', `country` = '" . $this->db->escape($country) . "', `date_added` = NOW()");
	}
}
