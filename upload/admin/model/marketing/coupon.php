<?php
namespace Opencart\Admin\Model\Marketing;
/**
 * Class Coupon
 *
 * Can be loaded using $this->load->model('marketing/coupon');
 *
 * @package Opencart\Admin\Model\Marketing
 */
class Coupon extends \Opencart\System\Engine\Model {
	/**
	 * Add Coupon
	 *
	 * Create a new coupon record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $coupon_data = [
	 *     'name'       => 'Coupon Name',
	 *     'code'       => 'Coupon Code',
	 *     'discount'   => 0.0000,
	 *     'type'       => 'F',
	 *     'total'      => 0.0000,
	 *     'logged'     => 0,
	 *     'shipping'   => 0,
	 *     'date_start' => '2021-01-01',
	 *     'date_end'   => '2021-01-31'
	 * ];
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $coupon_id = $this->model_marketing_coupon->addCoupon($coupon_data);
	 */
	public function addCoupon(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `discount` = '" . (float)$data['discount'] . "', `type` = '" . $this->db->escape((string)$data['type']) . "', `total` = '" . (float)$data['total'] . "', `logged` = '" . (isset($data['logged']) ? (bool)$data['logged'] : 0) . "', `shipping` = '" . (isset($data['shipping']) ? (bool)$data['shipping'] : 0) . "', `date_start` = '" . $this->db->escape((string)$data['date_start']) . "', `date_end` = '" . $this->db->escape((string)$data['date_end']) . "', `uses_total` = '" . (int)$data['uses_total'] . "', `uses_customer` = '" . (int)$data['uses_customer'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_added` = NOW()");

		$coupon_id = $this->db->getLastId();

		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->addProduct($coupon_id, $product_id);
			}
		}

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->addCategory($coupon_id, $category_id);
			}
		}

		return $coupon_id;
	}

	/**
	 * Edit Coupon
	 *
	 * Edit coupon record in the database.
	 *
	 * @param int                  $coupon_id primary key of the coupon record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $coupon_data = [
	 *     'name'       => 'Coupon Name',
	 *     'code'       => 'Coupon Code',
	 *     'discount'   => 0.0000,
	 *     'type'       => 'F',
	 *     'total'      => 0.0000,
	 *     'logged'     => 0,
	 *     'shipping'   => 0,
	 *     'date_start' => '2021-01-01',
	 *     'date_end'   => '2021-01-31'
	 * ];
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $this->model_marketing_coupon->editCoupon($coupon_id, $coupon_data);
	 */
	public function editCoupon(int $coupon_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "coupon` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `discount` = '" . (float)$data['discount'] . "', `type` = '" . $this->db->escape((string)$data['type']) . "', `total` = '" . (float)$data['total'] . "', `logged` = '" . (isset($data['logged']) ? (bool)$data['logged'] : 0) . "', `shipping` = '" . (isset($data['shipping']) ? (bool)$data['shipping'] : 0) . "', `date_start` = '" . $this->db->escape((string)$data['date_start']) . "', `date_end` = '" . $this->db->escape((string)$data['date_end']) . "', `uses_total` = '" . (int)$data['uses_total'] . "', `uses_customer` = '" . (int)$data['uses_customer'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		$this->deleteProducts($coupon_id);

		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->addProduct($coupon_id, $product_id);
			}
		}

		$this->deleteCategories($coupon_id);

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->addCategory($coupon_id, $category_id);
			}
		}
	}

	/**
	 * Delete Coupon
	 *
	 * Delete coupon record in the database.
	 *
	 * @param int $coupon_id primary key of the coupon record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $this->model_marketing_coupon->deleteCoupon($coupon_id);
	 */
	public function deleteCoupon(int $coupon_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		$this->deleteProducts($coupon_id);
		$this->deleteCategories($coupon_id);
		$this->deleteHistories($coupon_id);
	}

	/**
	 * Get Coupon
	 *
	 * Get the record of the coupon record in the database.
	 *
	 * @param int $coupon_id primary key of the coupon record
	 *
	 * @return array<string, mixed> coupon record that has coupon ID
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $coupon_info = $this->model_marketing_coupon->getCoupon($coupon_id);
	 */
	public function getCoupon(int $coupon_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "coupon` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		return $query->row;
	}

	/**
	 * Get Coupon By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $coupon_info = $this->model_marketing_coupon->getCouponByCode($code);
	 */
	public function getCouponByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "coupon` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	/**
	 * Get Coupons
	 *
	 * Get the record of the coupon records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> coupon records
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
	 * $this->load->model('marketing/coupon');
	 *
	 * $results = $this->model_marketing_coupon->getCoupons($filter_data);
	 */
	public function getCoupons(array $data = []): array {
		$sql = "SELECT `coupon_id`, `name`, `code`, `discount`, `date_start`, `date_end`, `status` FROM `" . DB_PREFIX . "coupon`";

		$sort_data = [
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
		];

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
	 * Add Product
	 *
	 * Create a new coupon product record in the database.
	 *
	 * @param int $coupon_id  primary key of the coupon record
	 * @param int $product_id primary key of the product record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $this->model_marketing_coupon->addProduct($coupon_id, $product_id);
	 */
	public function addProduct(int $coupon_id, int $product_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_product` SET `coupon_id` = '" . (int)$coupon_id . "', `product_id` = '" . (int)$product_id . "'");
	}

	/**
	 * Delete Products
	 *
	 * Delete coupon product records in the database.
	 *
	 * @param int $coupon_id primary key of the coupon record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $this->model_marketing_coupon->deleteProducts($coupon_id);
	 */
	public function deleteProducts(int $coupon_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_product` WHERE `coupon_id` = '" . (int)$coupon_id . "'");
	}

	/**
	 * Delete Products By Product ID
	 *
	 * Delete coupon products by product records in the database.
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $this->model_marketing_coupon->deleteProductsByProductId($product_id);
	 */
	public function deleteProductsByProductId(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_product` WHERE `product_id` = '" . (int)$product_id . "'");
	}

	/**
	 * Get Products
	 *
	 * Get the record of the coupon product records in the database.
	 *
	 * @param int $coupon_id primary key of the coupon record
	 *
	 * @return array<int, int> product records that have coupon ID
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $products = $this->model_marketing_coupon->getProducts($coupon_id);
	 */
	public function getProducts(int $coupon_id): array {
		$coupon_product_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		foreach ($query->rows as $result) {
			$coupon_product_data[] = $result['product_id'];
		}

		return $coupon_product_data;
	}

	/**
	 * Add Category
	 *
	 * Create a new coupon category record in the database.
	 *
	 * @param int $coupon_id   primary key of the coupon record
	 * @param int $category_id primary key of the category record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $this->model_marketing_coupon->addCategory($coupon_id, $category_id);
	 */
	public function addCategory(int $coupon_id, int $category_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_category` SET `coupon_id` = '" . (int)$coupon_id . "', `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Delete Categories
	 *
	 * Delete coupon category records in the database.
	 *
	 * @param int $coupon_id primary key of the coupon record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $this->model_marketing_coupon->deleteCategories($coupon_id);
	 */
	public function deleteCategories(int $coupon_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_category` WHERE `coupon_id` = '" . (int)$coupon_id . "'");
	}

	/**
	 * Delete Categories By Category ID
	 *
	 * Delete coupon category by category records in the database.
	 *
	 * @param int $category_id primary key of the category record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $this->model_marketing_coupon->deleteCategoriesByCategoryId($category_id);
	 */
	public function deleteCategoriesByCategoryId(int $category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_category` WHERE `category_id` = '" . (int)$category_id . "'");
	}

	/**
	 * Get Categories
	 *
	 * Get the record of the coupon category records in the database.
	 *
	 * @param int $coupon_id primary key of the coupon record
	 *
	 * @return array<int, int> category records that have coupon ID
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $categories = $this->model_marketing_coupon->getCategories($coupon_id);
	 */
	public function getCategories(int $coupon_id): array {
		$coupon_category_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		foreach ($query->rows as $result) {
			$coupon_category_data[] = $result['category_id'];
		}

		return $coupon_category_data;
	}

	/**
	 * Get Total Coupons
	 *
	 * Get the total number of total coupon records in the database.
	 *
	 * @return int total number of coupon records
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $coupon_total = $this->model_marketing_coupon->getTotalCoupons();
	 */
	public function getTotalCoupons(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "coupon`");

		return (int)$query->row['total'];
	}

	/**
	 * Get Histories
	 *
	 * Get the record of the coupon history records in the database.
	 *
	 * @param int $coupon_id primary key of the coupon record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> history records that have coupon ID
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $results = $this->model_marketing_coupon->getHistories($coupon_id, $start, $limit);
	 */
	public function getHistories(int $coupon_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `ch`.`order_id`, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `ch`.`amount`, `ch`.`date_added` FROM `" . DB_PREFIX . "coupon_history` `ch` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ch`.`customer_id` = `c`.`customer_id`) WHERE `ch`.`coupon_id` = '" . (int)$coupon_id . "' ORDER BY `ch`.`date_added` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Delete Coupon Histories
	 *
	 * Delete coupon history records in the database.
	 *
	 * @param int $coupon_id primary key of the coupon record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $this->model_marketing_coupon->deleteHistories($coupon_id);
	 */
	public function deleteHistories(int $coupon_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_history` WHERE `coupon_id` = '" . (int)$coupon_id . "'");
	}

	/**
	 * Get Total Histories
	 *
	 * Get the total number of total coupon history records in the database.
	 *
	 * @param int $coupon_id primary key of the coupon record
	 *
	 * @return int total number of history records
	 *
	 * @example
	 *
	 * $this->load->model('marketing/coupon');
	 *
	 * $history_total = $this->model_marketing_coupon->getTotalHistories($coupon_id);
	 */
	public function getTotalHistories(int $coupon_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "coupon_history` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		return (int)$query->row['total'];
	}
}
