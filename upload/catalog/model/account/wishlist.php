<?php
namespace Opencart\Catalog\Model\Account;
class Wishlist extends \Opencart\System\Engine\Model {
	public function addWishlist(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `product_id` = '" . (int)$product_id . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_wishlist` SET `customer_id` = '" . (int)$this->customer->getId() . "', `product_id` = '" . (int)$product_id . "', `date_added` = NOW()");
	}

	public function deleteWishlist(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `product_id` = '" . (int)$product_id . "'");
	}

	public function getWishlist(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->rows;
	}

	public function getTotalWishlist(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$this->customer->getId() . "'");

		return (int)$query->row['total'];
	}
}
