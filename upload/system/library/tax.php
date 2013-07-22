<?php
final class Tax {
	private $shipping_address;
	private $payment_address;
	private $store_address;
	
	/**
	 * cached tax rate definitions for tax_class_id / (shipping|payment|store) /
	 * customer_group_id / country_id / zone_id combinations
	*/
	private $defined_tax_rates;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->db = $registry->get('db');	
		$this->session = $registry->get('session');

		if (isset($this->session->data['shipping_address'])) {
			$this->setShippingAddress($this->session->data['shipping_address']['country_id'], $this->session->data['shipping_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'shipping') {
			$this->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		if (isset($this->session->data['payment_address'])) {
			$this->setPaymentAddress($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'payment') {
			$this->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		$this->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
	}

	public function setShippingAddress($country_id, $zone_id) {
		$this->shipping_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}

	public function setPaymentAddress($country_id, $zone_id) {
		$this->payment_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}

	public function setStoreAddress($country_id, $zone_id) {
		$this->store_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}

	public function calculate($value, $tax_class_id, $calculate = true) {
		if ($tax_class_id && $calculate) {
			$amount = 0;

			$tax_rates = $this->getRates($value, $tax_class_id);
	
			foreach ($tax_rates as $tax_rate) {
				if ($calculate != 'P' && $calculate != 'F') {
					$amount += $tax_rate['amount'];
				} elseif ($tax_rate['type'] == $calculate) {
					$amount += $tax_rate['amount'];	
				}
			}

			return $value + $amount;
		} else {
			return $value;
		}
	}
	
	public function getTax($value, $tax_class_id) {
		$amount = 0;

		$tax_rates = $this->getRates($value, $tax_class_id);

		foreach ($tax_rates as $tax_rate) {
			$amount += $tax_rate['amount'];
		}
	
		return $amount;
	}

	public function getRateName($tax_rate_id) {
		$tax_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

		if ($tax_query->num_rows) {
			return $tax_query->row['name'];
		} else {
			return false;
		}
	}

	public function getRates($value, $tax_class_id) {
		$tax_rates = array();

		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		if ($this->shipping_address) {
			$defined_tax_rates = $this->getDefinedTaxRates($tax_class_id, 'shipping', $customer_group_id, $this->shipping_address);
			foreach ($defined_tax_rates as $tax_rate_id => $tax_rate) {
				$tax_rates[$tax_rate_id] = $tax_rate;
			}
		}

		if ($this->payment_address) {
			$defined_tax_rates = $this->getDefinedTaxRates($tax_class_id, 'payment', $customer_group_id, $this->payment_address);
			foreach ($defined_tax_rates as $tax_rate_id => $tax_rate) {
				$tax_rates[$tax_rate_id] = $tax_rate;
			}
		}

		if ($this->store_address) {
			$defined_tax_rates = $this->getDefinedTaxRates($tax_class_id, 'store', $customer_group_id, $this->store_address);
			foreach ($defined_tax_rates as $tax_rate_id => $tax_rate) {
				$tax_rates[$tax_rate_id] = $tax_rate;
			}
		}

		$tax_rate_data = array();

		foreach ($tax_rates as $tax_rate) {
			if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
				$amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
			} else {
				$amount = 0;
			}

			if ($tax_rate['type'] == 'F') {
				$amount += $tax_rate['rate'];
			} elseif ($tax_rate['type'] == 'P') {
				$amount += ($value / 100 * $tax_rate['rate']);
			}

			$tax_rate_data[$tax_rate['tax_rate_id']] = array(
				'tax_rate_id' => $tax_rate['tax_rate_id'],
				'name'        => $tax_rate['name'],
				'rate'        => $tax_rate['rate'],
				'type'        => $tax_rate['type'],
				'amount'      => $amount
			);
		}

		return $tax_rate_data;
	}
	
	public function getDefinedTaxRates($tax_class_id, $based, $customer_group_id, $address) {
		$based = strtolower(trim($based));
		
		$country_id = isset($address['country_id']) ? (int)$address['country_id'] : 0;
		$zone_id    = isset($address['zone_id']) ? (int)$address['zone_id'] : 0;
		
		$cache_key = "{$tax_class_id}.{$based}.{$customer_group_id}.{$country_id}.{$zone_id}";
		
		if (!isset($this->defined_tax_rates[$cache_key])) {
			$query = $this->db->query("
				SELECT 
				tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority 
				FROM " . DB_PREFIX . "tax_rule tr1 
				LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) 
				INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) 
				LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) 
				LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) 
				WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' 
				AND tr1.based = '$based' 
				AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' 
				AND z2gz.country_id = '" . $country_id . "' 
				AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . $zone_id . "') 
				ORDER BY tr1.priority ASC
			");
			
			$rates = array();
			foreach ($query->rows as $result) {
				$rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
			
			$this->defined_tax_rates[$cache_key] = $rates;
		}
		
		return $this->defined_tax_rates[$cache_key];
	}

	public function has($tax_class_id) {
		return isset($this->taxes[$tax_class_id]);
	}
}
?>
