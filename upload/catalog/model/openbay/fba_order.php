<?php
class ModelOpenbayFbaOrder extends Model{
	public function addOrderHistory($order_id) {
		// does the status now match the status where a new fulfillment is triggered?

			// yes - get the order & order content

				// loop over the content to see what items are FBA and what are merchant fulfilled

			// create the payload to send over to FBA

			// prepend the order number with the config prepend setting option to odentify

		// does it now match the status where it should be cancelled?

		// how abou tnotifications? does the merchant want a notification taht here is a new fulfillment ready to be checked over?
		// alert of any missing products that were not in FBA?
		// any errors returned by FBA?



		$this->openbay->ebay->log('addOrderHistory() - Order id:' . $order_id . ' passed');
		if (!$this->openbay->ebay->getOrder($order_id)) {
			$order_products = $this->openbay->getOrderProducts($order_id);

			foreach($order_products as $order_product) {
				$product = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$order_product['product_id'] . "' LIMIT 1")->row;

				if ($this->openbay->addonLoad('openstock') && (isset($product['has_option']) && $product['has_option'] == 1)) {
					$order_product_variant = $this->openbay->getOrderProductVariant($order_id, $order_product['product_id'], $order_product['order_product_id']);

					if (isset($order_product_variant['sku']) && $order_product_variant['sku'] != '') {
						$this->openbay->ebay->ebaySaleStockReduce((int)$order_product['product_id'], (string)$order_product_variant['sku']);
					}
				} else {
					$this->openbay->ebay->ebaySaleStockReduce($order_product['product_id']);
				}
			}
		}
	}
}