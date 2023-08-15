<?php
namespace Opencart\Catalog\Model\Marketing;
/**
 * Class Coupon
 *
 * @package Opencart\Catalog\Model\Marketing
 */
class Coupon extends \Opencart\System\Engine\Model {
	/**
	 * @param string $code
	 *
	 * @return array
	 */
	public function getCoupon(string $code): array {
		$status = true;

		$coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE `code` = '" . $this->db->escape($code) . "' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) AND `status` = '1'");

		if ($coupon_query->num_rows) {
			if ($coupon_query->row['total'] > $this->cart->getSubTotal()) {
				$status = false;
			}

			$coupon_total = $this->getTotalHistoriesByCoupon($code);

			if ($coupon_query->row['uses_total'] > 0 && ($coupon_total >= $coupon_query->row['uses_total'])) {
				$status = false;
			}

			if ($coupon_query->row['logged'] && !$this->customer->getId()) {
				$status = false;
			}

			if ($this->customer->getId()) {
				$customer_total = $this->getTotalHistoriesByCustomerId($code, $this->customer->getId());

				if ($coupon_query->row['uses_customer'] > 0 && ($customer_total >= $coupon_query->row['uses_customer'])) {
					$status = false;
				}
			}

			// Products
			$coupon_product_data = [];

			$coupon_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE `coupon_id` = '" . (int)$coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_product_query->rows as $product) {
				$coupon_product_data[] = $product['product_id'];
			}

			// Categories
			$coupon_category_data = [];

			$coupon_category_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` cc LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cc.`category_id` = cp.`path_id`) WHERE cc.`coupon_id` = '" . (int)$coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_category_query->rows as $category) {
				$coupon_category_data[] = $category['category_id'];
			}

			$product_data = [];

			if ($coupon_product_data || $coupon_category_data) {
				foreach ($this->cart->getProducts() as $product) {
					if (in_array($product['product_id'], $coupon_product_data)) {
						$product_data[] = $product['product_id'];

						continue;
					}

					foreach ($coupon_category_data as $category_id) {
						$coupon_category_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND `category_id` = '" . (int)$category_id . "'");

						if ($coupon_category_query->row['total']) {
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
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
				'type'          => $coupon_query->row['type'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
				'product'       => $product_data,
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added']
			];
		} else {
			return [];
		}
	}

	/**
	 * @param string $coupon
	 *
	 * @return int
	 */
	public function getTotalHistoriesByCoupon(string $coupon): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "coupon_history` ch LEFT JOIN `" . DB_PREFIX . "coupon` c ON (ch.`coupon_id` = c.`coupon_id`) WHERE c.`code` = '" . $this->db->escape($coupon) . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param string $coupon
	 * @param int    $customer_id
	 *
	 * @return int
	 */
	public function getTotalHistoriesByCustomerId(string $coupon, int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "coupon_history` ch LEFT JOIN `" . DB_PREFIX . "coupon` c ON (ch.`coupon_id` = c.`coupon_id`) WHERE c.`code` = '" . $this->db->escape($coupon) . "' AND ch.`customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}
}
