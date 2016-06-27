<?php
class ModelExtensionOpenBayAmazonListing extends Model {
	public function listingSuccessful($product_id, $marketplace) {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazon_product` SET `status` = 'ok' WHERE product_id = " . (int)$product_id . " AND `marketplaces` = '" . $this->db->escape($marketplace) . "' AND `version` = 3
		");
	}

	public function listingFailed($product_id, $marketplace, $messages) {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazon_product` SET `status` = 'error', `messages` = '" . $this->db->escape(json_encode($messages)) . "' WHERE product_id = " . (int)$product_id . " AND `marketplaces` = '" . $this->db->escape($marketplace) . "' AND `version` = 3");
	}
}