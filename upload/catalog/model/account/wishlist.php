<?php
class ModelAccountWishlist extends Model {
	public function __constuct() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "wishlist WHERE customer_id = '0' AND date_added < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

	}

	public function addWishlist($product_id) {
		$this->event->trigger('pre.wishlist.add');

		$this->db->query("DELETE FROM " . DB_PREFIX . "wishlist WHERE product_id = '" . (int)$product_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "wishlist SET customer_id = '" . (int)$this->customer->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', product_id = '" . (int)$product_id . "', date_added = NOW()");

		$this->event->trigger('post.wishlist.add');
	}

	public function deleteWishlist($product_id) {
		$this->event->trigger('pre.wishlist.delete');

		$this->db->query("DELETE FROM " . DB_PREFIX . "wishlist WHERE product_id = '" . (int)$product_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		$this->event->trigger('post.wishlist.delete');
	}

	public function getWishlist() {
		$product_data = array();

		$this->db->query("DELETE FROM " . DB_PREFIX . "wishlist WHERE customer_id = '0' AND HOUR(date_modified) + 1 < NOW()");

        if ($this->customer->getId()) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_wishlist WHERE customer_id = '" . (int)$this->customer->getId() . "' OR session_id = '" . $this->db->escape($this->session->getId()) . "'");
        } else {

        }

		foreach ($query->rows as $result) {
			if (in_array($result['product_id'], $product_data)) {
				$product_data[] = $result['product_id'];
			}
		}

		return $query->rows;
	}

	public function getTotalWishlist() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "wishlist WHERE customer_id = '0' AND HOUR(date_modified) + 1 < NOW()");

        if ($this->customer->getId()) {
    	   $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "wishlist WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
        } else {
            $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "wishlist WHERE customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
        }

		return $query->row['total'];
	}
}
