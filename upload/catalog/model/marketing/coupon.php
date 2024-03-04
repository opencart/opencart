<?php
namespace Opencart\Catalog\Model\Marketing;
/**
 * Class Coupon
 *
 * @package Opencart\Catalog\Model\Marketing
 */
class Coupon extends \Opencart\System\Engine\Model {
	/**
	 * Get Coupon
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getCoupon(string $code): array {
		$status = true;

		$coupon_info = $this->model_marketing_coupon->getCouponByCode($code);

		if ($coupon_info && ($coupon_info['date_start'] == '0000-00-00' || strtotime($coupon_info['date_start']) < strtotime(date('Y-m-d H:i:s'))) && ($coupon_info['date_end'] == '0000-00-00' || strtotime($coupon_info['date_end']) > strtotime(date('Y-m-d H:i:s')))) {
			if ($coupon_info['total'] > $this->cart->getSubTotal()) {
				$status = false;
			}

			$coupon_total = $this->model_marketing_coupon->getTotalHistories($coupon_info['coupon_id']);

			if ($coupon_info['uses_total'] > 0 && ($coupon_total >= $coupon_info['uses_total'])) {
				$status = false;
			}

			if ($coupon_info['logged'] && !$this->customer->getId()) {
				$status = false;
			}

			if ($this->customer->getId()) {
				$customer_total = $this->model_marketing_coupon->getTotalHistoriesByCustomerId($coupon_info['coupon_id'], $this->customer->getId());

				if ($coupon_info['uses_customer'] > 0 && ($customer_total >= $coupon_info['uses_customer'])) {
					$status = false;
				}
			}

			// Products
			$coupon_product_data = $this->getProducts($coupon_info['coupon_id']);

			// Categories
			$coupon_category_data = $this->getCategories($coupon_info['coupon_id']);

			$product_data = [];

			if ($coupon_product_data || $coupon_category_data) {
				$this->load->model('catalog/product');

				foreach ($this->cart->getProducts() as $product) {
					if (in_array($product['product_id'], $coupon_product_data)) {
						$product_data[] = $product['product_id'];

						continue;
					}

					foreach ($coupon_category_data as $category_id) {
						$product_total = $this->model_catalog_product->getTotalCategoriesByCategoryId($product['product_id'], $category_id);

						if ($product_total) {
							$product_data[] = $product['product_id'];

							continue;
						}
					}
				}

				if (!$product_data) {
					$status = false;
				}
			}
		} else {
			$status = false;
		}

		if ($status) {
			return [
				'coupon_id'     => $coupon_info['coupon_id'],
				'code'          => $coupon_info['code'],
				'name'          => $coupon_info['name'],
				'type'          => $coupon_info['type'],
				'discount'      => $coupon_info['discount'],
				'shipping'      => $coupon_info['shipping'],
				'total'         => $coupon_info['total'],
				'product'       => $product_data,
				'date_start'    => $coupon_info['date_start'],
				'date_end'      => $coupon_info['date_end'],
				'uses_total'    => $coupon_info['uses_total'],
				'uses_customer' => $coupon_info['uses_customer'],
				'status'        => $coupon_info['status'],
				'date_added'    => $coupon_info['date_added']
			];
		} else {
			return [];
		}
	}

	/**
	 * Get Coupon By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getCouponByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE `code` = '" . $this->db->escape($code) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * Get Products
	 *
	 * @param int $coupon_id
	 *
	 * @return array<int, int>
	 */
	public function getProducts(int $coupon_id): array {
		$product_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		foreach ($query->rows as $product) {
			$product_data[] = $product['product_id'];
		}

		return $product_data;
	}

	/**
	 * Get Categories
	 *
	 * @param int $coupon_id
	 *
	 * @return array<int, int>
	 */
	public function getCategories(int $coupon_id): array {
		$category_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` `cc` LEFT JOIN `" . DB_PREFIX . "category_path` `cp` ON (`cc`.`category_id` = `cp`.`path_id`) WHERE `cc`.`coupon_id` = '" . (int)$coupon_id . "'");

		foreach ($query->rows as $category) {
			$category_data[] = $category['category_id'];
		}

		return $category_data;
	}

	/**
	 * Add History
	 *
	 * @param int   $coupon_id
	 * @param int   $order_id
	 * @param int   $customer_id
	 * @param float $amount
	 *
	 * @return void
	 */
	public function addHistory(int $coupon_id, int $order_id, int $customer_id, float $amount = 0.00): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET `coupon_id` = '" . (int)$coupon_id . "', `order_id` = '" . (int)$order_id . "', `customer_id` = '" . (int)$customer_id . "', `amount` = '" . (float)$amount . "', `date_added` = NOW()");
	}

	/**
	 * Delete Coupon Histories By Order ID
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteHistoriesByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_history` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * Get Total Histories
	 *
	 * @param string $coupon_id
	 *
	 * @return int
	 */
	public function getTotalHistories(string $coupon_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "coupon_history` `ch` LEFT JOIN `" . DB_PREFIX . "coupon` `c` ON (`ch`.`coupon_id` = `c`.`coupon_id`) WHERE `c`.`coupon_id` = '" . $this->db->escape($coupon_id) . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Histories By Customer ID
	 *
	 * @param int $coupon_id
	 * @param int $customer_id
	 *
	 * @return int
	 */
	public function getTotalHistoriesByCustomerId(int $coupon_id, int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "coupon_history` `ch` LEFT JOIN `" . DB_PREFIX . "coupon` `c` ON (`ch`.`coupon_id` = `c`.`coupon_id`) WHERE `c`.`coupon_id` = '" . (int)$coupon_id . "' AND `ch`.`customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}
}
