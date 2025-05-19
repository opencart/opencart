<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Wishlist
 *
 * Can be called using $this->load->model('account/wishlist');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Wishlist extends \Opencart\System\Engine\Model {
	/**
	 * Add Wishlist
	 *
	 * Create a new customer wishlist record in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $product_id  primary key of the product record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/wishlist');
	 *
	 * $this->model_account_customer->addWishlist($customer_id, $product_id);
	 */
	public function addWishlist(int $customer_id, int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `product_id` = '" . (int)$product_id . "'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_wishlist` SET `customer_id` = '" . (int)$customer_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `product_id` = '" . (int)$product_id . "', `date_added` = NOW()");
	}

	/**
	 * Delete Wishlist
	 *
	 * Delete customer wishlist record in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $product_id  primary key of the product record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/wishlist');
	 *
	 * $this->model_account_wishlist->deleteWishlist($customer_id, $product_id);
	 */
	public function deleteWishlist(int $customer_id, int $product_id = 0): void {
		$sql = "DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'";

		if ($product_id) {
			$sql .= " AND `product_id` = '" . (int)$product_id . "'";
		}

		$this->db->query($sql);
	}

	/**
	 * Get Wishlist
	 *
	 * Get the record of the customer wishlist record in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return array<int, array<string, mixed>> wishlist records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('account/wishlist');
	 *
	 * $wishlist_info = $this->model_account_wishlist->getWishlist($customer_id);
	 */
	public function getWishlist(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->rows;
	}

	/**
	 * Get Total Wishlist
	 *
	 * Get the total number of total customer wishlist records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of wishlist records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('account/wishlist');
	 *
	 * $wishlist_total = $this->model_account_wishlist->getTotalWishlist($customer_id);
	 */
	public function getTotalWishlist(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		return (int)$query->row['total'];
	}
}
