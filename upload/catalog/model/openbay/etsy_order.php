<?php
class ModelOpenbayEtsyOrder extends Model {
	public function inbound($orders) {
		$this->load->model('checkout/order');
		$this->load->model('localisation/currency');

		$this->language->load('openbay/etsy_order');


		/**
		 * debuig code to remove
		 */
		$this->db->query("TRUNCATE `" . DB_PREFIX . "etsy_order`");
		$this->db->query("TRUNCATE `" . DB_PREFIX . "order`");
		$this->db->query("TRUNCATE `" . DB_PREFIX . "order_history`");
		$this->db->query("TRUNCATE `" . DB_PREFIX . "order_option`");
		$this->db->query("TRUNCATE `" . DB_PREFIX . "order_product`");
		$this->db->query("TRUNCATE `" . DB_PREFIX . "order_total`");

		if(!empty($orders)) {
			foreach($orders as $order) {
				$this->openbay->etsy->log($order->receipt_id);

				$order_id = $this->findOrder($order->receipt_id);

				if ($order_id != false) {
					if (!$this->lockExists($order_id)) {
						$order_info = $this->model_checkout_order->getOrder($order_id);

						$this->openbay->etsy->log('Loaded existing order');

						// paid status changed?

						// shipped status changed?



						$this->lockDelete($order_id);
					}
				} else {
					$order_id = $this->create($order);

					// is paid?
					if ($order->paid == 1) {

					} else {

					}

					// is shipped?
					if ($order->shipped == 1) {

					} else {

					}

					// confirm order


					$this->openbay->etsy->log('Created new order: ' . $order_id);
				}
			}
		}
	}

	private function findOrder($receipt_id) {
		$this->openbay->etsy->log('Find '.$receipt_id);

		$query = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "etsy_order` WHERE `receipt_id` = '" . (int)$receipt_id . "' LIMIT 1");

		if($query->num_rows > 0) {
			$this->openbay->etsy->log($query->row['order_id']);
			return (int)$query->row['order_id'];
		}else{
			$this->openbay->etsy->log('no');
			return false;
		}
	}

	private function lockAdd($order_id) {
		$this->db->query("INSERT INTO`" . DB_PREFIX . "etsy_order_lock` SET `order_id` = '" . (int)$order_id . "'");
	}

	private function lockDelete($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "etsy_order_lock` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	private function lockExists($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "etsy_order_lock` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if($query->num_rows > 0) {
			return true;
		}else{
			$this->lockAdd($order_id);
			return false;
		}
	}

	private function create($order) {
		$currency_code = (string)$order->transactions[0]->currency;
		$currency = $this->model_localisation_currency->getCurrencyByCode($currency_code);

		$customer_name = $this->openbay->splitName($order->name);

		if(!empty($order->country->iso)){
			$country_qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($order->country->iso) . "'");
		}

		if(!empty($country_qry->num_rows)){
			$country_name = $country_qry->row['name'];
			$country_id = $country_qry->row['country_id'];
			$zone_id = $this->openbay->getZoneId($order->address_state, $country_id);
			$country_address_format = $country_qry->row['address_format'];
		}else{
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
		   `customer_group_id`        = 0,
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
		   `payment_method`	  		  = '',
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
		   `shipping_method`  		  = '" . $this->db->escape((string)$order->shipping_carrier) . "',
		   `shipping_code`  		  = '',
		   `comment`                  = '" . $this->db->escape((string)$order->shipping_note) . "',
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

		foreach($order->transactions as $transaction) {
			$product = $this->openbay->etsy->getLinkedProduct($transaction->etsy_listing_id);

			if ($product != false) {
				$product_id = $product['product_id'];
				$product_model = $product['model'];
			} else {
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
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "etsy_order` SET `order_id` = '" . (int)$order_id . "', `receipt_id` = '" . (int)$order->receipt_id . "'");

		$totals = array();

		$totals[0] = array(
			'code'          => 'sub_total',
			'title'         => $this->language->get('lang_subtotal'),
			'value'         => number_format((double)$order->amount_subtotal, 4,'.',''),
			'sort_order'    => '1'
		);

		$totals[1] = array(
			'code'          => 'shipping',
			'title'         => $this->language->get('lang_shipping'),
			'value'         => number_format((double)$order->price_shipping, 4,'.',''),
			'sort_order'    => '3'
		);

		if ($order->amount_discount != 0.00) {
			$totals[2] = array(
				'code'          => 'coupon',
				'title'         => $this->language->get('lang_discount'),
				'value'         => number_format((double)$order->amount_discount, 4,'.',''),
				'sort_order'    => '4'
			);
		}

		$totals[3] = array(
			'code'          => 'tax',
			'title'         => $this->language->get('lang_tax'),
			'value'         => number_format((double)$order->price_tax, 3,'.',''),
			'sort_order'    => '5'
		);

		$totals[4] = array(
			'code'          => 'total',
			'title'         => $this->language->get('lang_total'),
			'value'         => $order->amount_total,
			'sort_order'    => '6'
		);

		foreach ($totals as $total) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_total` SET `order_id` = '" . (int)$order_id . "', `code` = '" . $this->db->escape($total['code']) . "', `title` = '" . $this->db->escape($total['title']) . "', `value` = '" . (double)$total['value'] . "', `sort_order` = '" . (int)$total['sort_order'] . "'");
		}

		$this->openbay->etsy->log($order_id);

		return $order_id;
	}
}