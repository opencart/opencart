<?php
class ModelAccountCustomer extends Model {
	public funtion __construct() {

	}

	public function addWishlist($product_id) {
		$this->event->trigger('pre.wishlist.add');

		$this->db->query("INSERT INTO " . DB_PREFIX . "wishlist SET product_id = '" . (int)$product_id . "', customer_id = '" . (int)$this->customer->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "'");

		$this->event->trigger('post.wishlist.add');
	}

	public function editWishlist($customer_id) {
		$this->event->trigger('pre.wishlist.delete');

		$this->db->query("DELETE FROM " . DB_PREFIX . "wishlist WHERE customer_id = '" . (int)$customer_id . "' WHERE session_id = '" . $this->db->escape($this->session->getId()) . "'");

		$this->event->trigger('post.wishlist.delete');
	}

	public function deleteWishlist($product_id) {
		$this->event->trigger('pre.wishlist.delete');

		$this->db->query("DELETE FROM " . DB_PREFIX . "wishlist WHERE product_id = '" . (int)$product_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		$this->event->trigger('post.wishlist.delete');
	}

	public function getWishlist() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "wishlist WHERE customer_id = '0' AND HOUR(date_modified) + 1 < NOW()");

        if ($this->customer->getId()) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_wishlist WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
        } else {

        }

		return $query->rows;
	}

	public function getTotalWishlist() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "wishlist WHERE customer_id = '0' AND HOUR(date_modified) + 1 < NOW()");

        if ($this->customer->getId()) {
    	   $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "wishlist WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "wishlist WHERE customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
        }

		return $query->rows;
	}

	public funtion __destruct() {


	}
}
