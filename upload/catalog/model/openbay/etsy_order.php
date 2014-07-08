<?php
class ModelOpenbayEtsyOrder extends Model {
	public function inbound($orders) {
		if(!empty($orders)) {
			foreach($orders as $order) {
				$order_id = $this->findOrder($order['receipt_id']);

				if ($order_id != false) {
					if (!$this->lockExists($order_id)) {






						$this->lockDelete($order_id);
					}
				} else {
					// create order

					$order_id = $this->create($order);
				}
			}
		}
	}

	private function findOrder($receipt_id) {
		$query = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "etsy_order` WHERE `receipt_id` = '" . (int)$receipt_id . "' LIMIT 1");

		if($query->num_rows > 0) {
			return (int)$query->row['order_id'];
		}else{
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
		$this->load->model('localisation/currency');

		$total = 0.00;

		$currency_code = (string)$order->transactions[0]->currency;
		$currency = $this->model_localisation_currency->getCurrencyByCode($currency_code);

		$customer_name = $this->openbay->splitName($order->name);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET
		   `store_id`                 = '" . (int)$this->config->get('config_store_id') . "',
		   `store_name`               = '" . $this->db->escape($this->config->get('config_name') . ' / Etsy') . "',
		   `store_url`                = '" . $this->db->escape($this->config->get('config_url')) . "',
		   `invoice_prefix`           = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "',
		   `comment`                  = '" . $this->db->escape((string)$order->shipping_note) . "',
		   `firstname`				  = '" . $this->db->escape((string)$customer_name['firstname']) . "',
		   `lastname`				  = '" . $this->db->escape((string)$customer_name['surname']) . "',
		   `payment_firstname`		  = '" . $this->db->escape((string)$customer_name['firstname']) . "',
		   `payment_lastname`		  = '" . $this->db->escape((string)$customer_name['surname']) . "',
		   `payment_address_1`		  = '" . $this->db->escape((string)$order->address_1) . "',
		   `payment_address_2`		  = '" . $this->db->escape((string)$order->address_2) . "',
		   `payment_city`			  = '" . $this->db->escape((string)$order->address_city) . "',
		   `payment_postcode`		  = '" . $this->db->escape((string)$order->address_state) . "',
		   `payment_country`		  = '',
		   `payment_country_id`		  = '',
		   `payment_zone`			  = '" . $this->db->escape((string)$order->state) . "',
		   `payment_zone_id`		  = '',
		   `payment_address_format`	  = '',
		   `shipping_firstname`		  = '" . $this->db->escape((string)$customer_name['firstname']) . "',
		   `shipping_lastname`		  = '" . $this->db->escape((string)$customer_name['surname']) . "',
		   `shipping_address_1`		  = '" . $this->db->escape((string)$order->address_1) . "',
		   `shipping_address_2`		  = '" . $this->db->escape((string)$order->address_2) . "',
		   `shipping_city`			  = '" . $this->db->escape((string)$order->address_city) . "',
		   `shipping_postcode`		  = '" . $this->db->escape((string)$order->address_state) . "',
		   `shipping_country`		  = '',
		   `shipping_country_id`	  = '',
		   `shipping_zone`			  = '" . $this->db->escape((string)$order->state) . "',
		   `shipping_zone_id`		  = '',
		   `shipping_address_format`  = '',
		   `total`                    = '" . (double)$total . "',
		   `language_id`              = '" . (int)$this->config->get('config_language_id') . "',
		   `currency_id`              = '" . (int)$currency['currency_id'] . "',
		   `currency_code`            = '" . $this->db->escape($currency_code) . "',
		   `currency_value`           = '" . (double)$currency['value'] . "',
		   `date_added`               = '',
		   `date_modified`            = NOW(),
		   `customer_id`              = 0
		");

		$order_id = $this->db->getLastId();

		$this->addTransactions($order->transactions, $order_id);

		return $order_id;
	}

	private function addTransactions($transactions, $order_id) {
		foreach($transactions as $transaction) {

		}
	}
}