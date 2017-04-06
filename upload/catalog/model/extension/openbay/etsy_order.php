<?php
class ModelExtensionOpenBayEtsyOrder extends Model {
	public function inbound($orders) {
		$this->openbay->etsy->log("Model inbound, Orders count: " . count($orders));

		$this->load->model('checkout/order');
		$this->load->model('localisation/currency');

		$this->load->language('extension/openbay/etsy_order');

		if (!empty($orders)) {
			foreach ($orders as $order) {
				$etsy_order = $this->openbay->etsy->orderFind(null, $order->receipt_id);

				if ($etsy_order != false) {
					$this->openbay->etsy->log("Etsy order found");

					$order_id = (int)$etsy_order['order_id'];

					if (!$this->lockExists($order_id)) {
						// paid status changed?
						if ($order->paid != $etsy_order['paid']) {
							$this->openbay->etsy->log("Order paid status changed (" . $order->paid . ")");

							$this->updatePaid($order_id, $order->paid);
						}

						// shipped status changed?
						if ($order->shipped != $etsy_order['shipped']) {
							$this->openbay->etsy->log("Order shipped status changed (" . $order->shipped . ")");

							if ($order->paid == 1) {
								$this->openbay->etsy->log("Order is shipped and paid");
								$this->updateShipped($order_id, $order->shipped);
							} else {
								$this->openbay->etsy->log("Order is not paid so setting to unshipped");
								$this->updateShipped($order_id, 0);
							}
						}

						$this->lockDelete($order_id);
					} else {
						$this->openbay->etsy->log("There is an update lock on this order");
					}
				} else {
					$this->openbay->etsy->log("Etsy order not found, creating");

					$order_id = $this->create($order);

					// is paid?
					if ($order->paid == 1) {
						$this->openbay->etsy->log("Order is paid");

						$this->updatePaid($order_id, $order->paid);

						// is shipped?
						if ($order->shipped == 1) {
							$this->openbay->etsy->log("Order is shipped");
							$this->updateShipped($order_id, $order->shipped);
						}
					}

					$this->openbay->etsy->log('Created Order ID: ' . $order_id);
				}
			}
		}
	}

	public function updateOrderStatus($order_id, $status_id) {
		$this->openbay->etsy->log("Model updateOrderStatus Order ID: " . $order_id . ", Status ID: " . $status_id);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_history` (`order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES (" . (int)$order_id . ", " . (int)$status_id . ", 0, '', NOW())");

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `order_status_id` = " . (int)$status_id . " WHERE `order_id` = " . (int)$order_id);
	}

	public function updatePaid($order_id, $status) {
		$this->openbay->etsy->log("Model updatePaid Order ID: " . $order_id . ", Status: " . $status);

		if ($status == 1) {
			$this->updateOrderStatus($order_id, $this->config->get('etsy_order_status_paid'));
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "etsy_order` SET `paid` = " . (int)$status . " WHERE `order_id` = " . (int)$order_id);
	}

	public function updateShipped($order_id, $status) {
		$this->openbay->etsy->log("Model updateShipped Order ID: " . $order_id . ", Status: " . $status);

		if ($status == 1) {
			$this->updateOrderStatus($order_id, $this->config->get('etsy_order_status_shipped'));
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "etsy_order` SET `shipped` = " . (int)$status . " WHERE `order_id` = " . (int)$order_id);
	}

	public function modifyStock($product_id, $qty, $symbol = '-') {
		$this->openbay->etsy->log("Model modifyStock Product ID: " . $product_id . ", Qty: " . $qty . ", Symbol: " . $symbol);

		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` " . $this->db->escape((string)$symbol) . " " . (int)$qty . ") WHERE `product_id` = '" . (int)$product_id . "' AND `subtract` = '1'");
	}

	private function lockAdd($order_id) {
		$this->openbay->etsy->log("Model lockAdd Order ID: " . $order_id);
		$this->db->query("INSERT INTO`" . DB_PREFIX . "etsy_order_lock` SET `order_id` = '" . (int)$order_id . "'");
	}

	private function lockDelete($order_id) {
		$this->openbay->etsy->log("Model lockDelete Order ID: " . $order_id);
		$this->db->query("DELETE FROM `" . DB_PREFIX . "etsy_order_lock` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	private function lockExists($order_id) {
		$this->openbay->etsy->log("Model lockExists Order ID: " . $order_id);
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "etsy_order_lock` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($query->num_rows > 0) {
			$this->openbay->etsy->log("Yes");
			return true;
		} else {
			$this->openbay->etsy->log("No");
			$this->lockAdd($order_id);
			return false;
		}
	}

	private function create($order) {
		$currency_code = (string)$order->transactions[0]->currency;
		$currency = $this->model_localisation_currency->getCurrencyByCode($currency_code);

		$customer_name = $this->openbay->splitName($order->name);

		if (!empty($order->country->iso)){
			$country_qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($order->country->iso) . "'");
		}

		if (!empty($country_qry->num_rows)){
			$country_name = $country_qry->row['name'];
			$country_id = $country_qry->row['country_id'];
			$zone_id = $this->openbay->getZoneId($order->address_state, $country_id);
			$country_address_format = $country_qry->row['address_format'];
		} else {
			$country_name = (string)$order->country->name;
			$country_id = '';
			$zone_id = '';
			$country_address_format = $this->config->get('etsy_address_format');
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET
		   `invoice_prefix`           = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "',
		   `store_id`                 = '" . (int)$this->config->get('config_store_id') . "',
		   `store_name`               = '" . $this->db->escape($this->config->get('config_name') . ' / Etsy') . "',
		   `store_url`                = '" . $this->db->escape($this->config->get('config_url')) . "',
		   `customer_id`              = 0,
		   `customer_group_id`        = '" . (int)$this->config->get('config_customer_group_id') . "',
		   `firstname`				  = '" . $this->db->escape((string)$customer_name['firstname']) . "',
		   `lastname`				  = '" . $this->db->escape((string)$customer_name['surname']) . "',
		   `email`				  	  = '" . $this->db->escape((string)$order->buyer_email) . "',
		   `telephone`				  = '',
		   `fax`				  	  = '',
		   `payment_firstname`		  = '" . $this->db->escape((string)$customer_name['firstname']) . "',
		   `payment_lastname`		  = '" . $this->db->escape((string)$customer_name['surname']) . "',
		   `payment_company`		  = '',
		   `payment_address_1`		  = '" . $this->db->escape((string)$order->address_1) . "',
		   `payment_address_2`		  = '" . $this->db->escape((string)$order->address_2) . "',
		   `payment_city`			  = '" . $this->db->escape((string)$order->address_city) . "',
		   `payment_postcode`		  = '" . $this->db->escape((string)$order->address_zip) . "',
		   `payment_country`		  = '" . $this->db->escape((string)$country_name) . "',
		   `payment_country_id`		  = '" . $this->db->escape((string)$country_id) . "',
		   `payment_zone`			  = '" . $this->db->escape((string)$order->address_state) . "',
		   `payment_zone_id`		  = '" . (int)$zone_id . "',
		   `payment_address_format`	  = '" . $this->db->escape((string)$country_address_format) . "',
		   `payment_method`	  		  = '" . $this->db->escape((string)$order->payment_method_name) . "',
		   `payment_code`	  		  = '',
		   `shipping_firstname`		  = '" . $this->db->escape((string)$customer_name['firstname']) . "',
		   `shipping_lastname`		  = '" . $this->db->escape((string)$customer_name['surname']) . "',
		   `shipping_address_1`		  = '" . $this->db->escape((string)$order->address_1) . "',
		   `shipping_address_2`		  = '" . $this->db->escape((string)$order->address_2) . "',
		   `shipping_city`			  = '" . $this->db->escape((string)$order->address_city) . "',
		   `shipping_postcode`		  = '" . $this->db->escape((string)$order->address_zip) . "',
		   `shipping_country`		  = '" . $this->db->escape((string)$country_name) . "',
		   `shipping_country_id`	  = '" . $this->db->escape((string)$country_id) . "',
		   `shipping_zone`			  = '" . $this->db->escape((string)$order->address_state) . "',
		   `shipping_zone_id`		  = '" . (int)$zone_id . "',
		   `shipping_address_format`  = '" . $this->db->escape((string)$country_address_format) . "',
		   `shipping_method`  		  = '',
		   `shipping_code`  		  = '',
		   `comment`                  = '" . $this->db->escape((string)$order->buyer_note) . "',
		   `total`                    = '" . (double)$order->amount_total . "',
		   `order_status_id`          = '',
		   `affiliate_id`          	  = '',
		   `commission`          	  = '',
		   `marketing_id`          	  = '',
		   `tracking`          	  	  = '',
		   `language_id`              = '" . (int)$this->config->get('config_language_id') . "',
		   `currency_id`              = '" . (int)$currency['currency_id'] . "',
		   `currency_code`            = '" . $this->db->escape($currency_code) . "',
		   `currency_value`           = 1,
		   `ip`           			  = '',
		   `forwarded_ip`             = '',
		   `user_agent`               = '',
		   `accept_language`          = '',
		   `date_added`               = NOW(),
		   `date_modified`            = NOW()
		");

		$order_id = $this->db->getLastId();

		$this->openbay->etsy->log("Model create(order), New Order ID: " . $order_id);

		foreach ($order->transactions as $transaction) {
			$this->openbay->etsy->log("Listing ID: " . $transaction->etsy_listing_id);

			$product = $this->openbay->etsy->getLinkedProduct($transaction->etsy_listing_id);

			if ($product != false) {
				$this->openbay->etsy->log("Linked to product ID: " . $product['product_id']);
				$product_id = $product['product_id'];
				$product_model = $product['model'];
			} else {
				$this->openbay->etsy->log("Item not linked to product");
				$product_id = 0;
				$product_model = '';
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET
			   `order_id`		= '" . (int)$order_id . "',
			   `product_id`		= '" . (int)$product_id . "',
			   `name`			= '" . $this->db->escape((string)$transaction->title) . "',
			   `model`			= '" . $this->db->escape($product_model) . "',
			   `quantity`		= '" . (int)$transaction->quantity . "',
			   `price`			= '" . (int)$transaction->price . "',
			   `total`			= '" . (int)$transaction->price * (int)$transaction->quantity . "',
			   `tax`			= '',
			   `reward`			= ''
		   ");

			if ($product_id != 0) {
				$this->modifyStock($product_id, (int)$transaction->quantity);
			}
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "etsy_order` SET `order_id` = '" . (int)$order_id . "', `receipt_id` = '" . (int)$order->receipt_id . "'");

		$totals = array();

		$totals[0] = array(
			'code'          => 'sub_total',
			'title'         => $this->language->get('text_total_sub'),
			'value'         => number_format($order->price_total, 4, '.', ''),
			'sort_order'    => '1'
		);

		$totals[1] = array(
			'code'          => 'shipping',
			'title'         => $this->language->get('text_total_shipping'),
			'value'         => number_format($order->price_shipping, 4, '.', ''),
			'sort_order'    => '3'
		);

		if ($order->amount_discount != 0.00) {
			$totals[2] = array(
				'code'          => 'coupon',
				'title'         => $this->language->get('text_total_discount'),
				'value'         => number_format($order->amount_discount, 4, '.', ''),
				'sort_order'    => '4'
			);
		}

		$totals[3] = array(
			'code'          => 'tax',
			'title'         => $this->language->get('text_total_tax'),
			'value'         => number_format($order->price_tax, 3, '.', ''),
			'sort_order'    => '5'
		);

		$totals[4] = array(
			'code'          => 'total',
			'title'         => $this->language->get('text_total'),
			'value'         => $order->amount_total,
			'sort_order'    => '6'
		);

		foreach ($totals as $total) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_total` SET `order_id` = '" . (int)$order_id . "', `code` = '" . $this->db->escape($total['code']) . "', `title` = '" . $this->db->escape($total['title']) . "', `value` = '" . (double)$total['value'] . "', `sort_order` = '" . (int)$total['sort_order'] . "'");
		}

		$this->openbay->etsy->log("Setting order to new order status ID: " . $this->config->get('etsy_order_status_new'));
		
		$this->updateOrderStatus($order_id, $this->config->get('etsy_order_status_new'));

		$this->event->trigger('model/checkout/order/addOrderHistory/after', array('model/checkout/order/addOrderHistory/after', array($order_id, $this->config->get('etsy_order_status_new'))));

		return $order_id;
	}

	public function addOrderHistory($order_id) {
		$this->openbay->etsy->log("Model addOrderHistory, Order ID: " . $order_id);

		if(!$this->openbay->etsy->orderFind($order_id)) {
			$order_products = $this->openbay->getOrderProducts($order_id);

			foreach ($order_products as $order_product) {
				$this->openbay->etsy->log("Model addOrderHistory - Product ID: " . $order_product['product_id']);

				$this->openbay->etsy->productUpdateListen($order_product['product_id']);
			}
		} else {
			$this->openbay->etsy->log("Model addOrderHistory - Etsy order");
		}
	}
}