<?php
use Klarna\Rest\Transport\Connector as KCConnector;
use Klarna\Rest\Transport\ConnectorInterface as KCConnectorInterface;
use Klarna\Rest\Checkout\Order as KCOrder;

class ModelExtensionPaymentKlarnaCheckout extends Model {
	public function orderCreate(KCConnector $connector, $order_data) {
		try {
			$checkout = new KCOrder($connector);
			$checkout->create($order_data);

			return $checkout->fetch();
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 1);

			return false;
		}
	}

	public function orderRetrieve(KCConnector $connector, $order_id) {
		try {
			$checkout = new KCOrder($connector, $order_id);

			return $checkout->fetch();
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 1);

			return false;
		}
	}

	public function orderUpdate(KCConnector $connector, $order_id, $order_data) {
		try {
			$checkout = new KCOrder($connector, $order_id);
			$checkout->update($order_data);

			return $checkout->fetch();
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 1);

			return false;
		}
	}

	public function omOrderRetrieve(KCConnector $connector, $order_id) {
		try {
			$order = new \Klarna\Rest\OrderManagement\Order($connector, $order_id);

			return $order->fetch();
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 1);

			return false;
		}
	}

	public function getMethod($address, $total) {
		// Not shown in the payment method list
		return array();
	}

	public function getConnector($accounts, $currency) {
		$klarna_account = false;
		$connector = false;

		if ($accounts && $currency) {
			foreach ($accounts as $account) {
				if ($account['currency'] == $currency) {
					if ($account['environment'] == 'test') {
						if ($account['api'] == 'NA') {
							$base_url = KCConnectorInterface::NA_TEST_BASE_URL;
						} elseif ($account['api'] == 'EU')  {
							$base_url = KCConnectorInterface::EU_TEST_BASE_URL;
						}
					} elseif ($account['environment'] == 'live') {
						if ($account['api'] == 'NA') {
							$base_url = KCConnectorInterface::NA_BASE_URL;
						} elseif ($account['api'] == 'EU')  {
							$base_url = KCConnectorInterface::EU_BASE_URL;
						}
					}

					$klarna_account = $account;
					$connector = $this->connector(
						$account['merchant_id'],
						$account['secret'],
						$base_url
					);

					break;
				}
			}
		}

		return array($klarna_account, $connector);
	}

	public function getOrder($order_ref) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "klarna_checkout_order` WHERE `order_ref` = '" . $this->db->escape($order_ref) . "' LIMIT 1")->row;
	}

	public function getOrderByOrderId($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "klarna_checkout_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1")->row;
	}

	public function addOrder($order_id, $order_ref, $data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "klarna_checkout_order` SET `order_id` = '" . (int)$order_id . "', `order_ref` = '" . $this->db->escape($order_ref) . "', `data` = '" . $this->db->escape($data) . "'");
	}

	public function updateOrder($order_id, $order_ref, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "klarna_checkout_order` SET `order_id` = '" . (int)$order_id . "', `data` = '" . $this->db->escape($data) . "' WHERE `order_ref` = '" . $this->db->escape($order_ref) . "'");
	}

	public function updateOcOrder($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `telephone` = '" . $this->db->escape($data['telephone']) . "', `payment_firstname` = '" . $this->db->escape($data['payment_firstname']) . "', `payment_lastname` = '" . $this->db->escape($data['payment_lastname']) . "', `payment_address_1` = '" . $this->db->escape($data['payment_address_1']) . "', `payment_address_2` = '" . $this->db->escape($data['payment_address_2']) . "', `payment_city` = '" . $this->db->escape($data['payment_city']) . "', `payment_postcode` = '" . $this->db->escape($data['payment_postcode']) . "', `payment_zone` = '" . $this->db->escape($data['payment_zone']) . "', `payment_zone_id` = '" . (int)$data['payment_zone_id'] . "', `payment_country` = '" . $this->db->escape($data['payment_country']) . "', `payment_country_id` = '" . (int)$data['payment_country_id'] . "', `payment_address_format` = '" . $this->db->escape($data['payment_address_format']) . "', `shipping_firstname` = '" . $this->db->escape($data['shipping_firstname']) . "', `shipping_lastname` = '" . $this->db->escape($data['shipping_lastname']) . "', `shipping_address_1` = '" . $this->db->escape($data['shipping_address_1']) . "', `shipping_address_2` = '" . $this->db->escape($data['shipping_address_2']) . "', `shipping_city` = '" . $this->db->escape($data['shipping_city']) . "', `shipping_postcode` = '" . $this->db->escape($data['shipping_postcode']) . "', `shipping_zone` = '" . $this->db->escape($data['shipping_zone']) . "', `shipping_zone_id` = '" . (int)$data['shipping_zone_id'] . "', `shipping_country` = '" . $this->db->escape($data['shipping_country']) . "', `shipping_country_id` = '" . (int)$data['shipping_country_id'] . "', `shipping_address_format` = '" . $this->db->escape($data['shipping_address_format']) . "' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function updateOcOrderEmail($order_id, $email) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `email` = '" . $this->db->escape($email) . "' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function getCountryByIsoCode2($iso_code_2) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE `iso_code_2` = '" . $this->db->escape($iso_code_2) . "' AND `status` = '1'");

		return $query->row;
	}

	public function getCountryByIsoCode3($iso_code_3) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE `iso_code_3` = '" . $this->db->escape($iso_code_3) . "' AND `status` = '1'");

		return $query->row;
	}

	public function getZoneByCode($code, $country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE (`code` = '" . $this->db->escape($code) . "' OR `name` = '" . $this->db->escape($code) . "') AND `country_id` = '" . (int)$country_id . "' AND `status` = '1'");

		return $query->row;
	}

	public function getCountriesByGeoZone($geo_zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "' GROUP BY `country_id` ORDER BY `country_id` ASC");

		return $query->rows;
	}

	public function checkForPaymentTaxes($products = array()) {
		foreach ($products as $product) {
			$query = $this->db->query("SELECT COUNT(*) AS `total` FROM " . DB_PREFIX . "tax_rule WHERE `based` = 'payment' AND `tax_class_id` = '" . (int)$product['tax_class_id'] . "'");

			if ($query->row['total']) {
				return true;
			}
		}

		return false;
	}

	public function getDefaultShippingMethod($shipping_methods) {
		$first_shipping_method = reset($shipping_methods);

		if ($first_shipping_method && isset($first_shipping_method['quote']) && !empty($first_shipping_method['quote'])) {
			$first_shipping_method_quote = reset($first_shipping_method['quote']);

			if ($first_shipping_method_quote) {
				$shipping = explode('.', $first_shipping_method_quote['code']);

				return $shipping_methods[$shipping[0]]['quote'][$shipping[1]];
			}
		}

		return array();
	}

	public function log($data, $step = 6) {
		if ($this->config->get('klarna_checkout_debug')) {
			$backtrace = debug_backtrace();
			$log = new Log('klarna_checkout.log');
			$log->write('(' . $backtrace[$step]['class'] . '::' . $backtrace[$step]['function'] . ') - ' . print_r($data, true));
		}
	}

	public function subscribeNewsletter($customer_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '1' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function getTotals() {
		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Because __call can not keep var references so we put them into an array.
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);

		$this->load->model('extension/extension');

		$sort_order = array();

		$results = $this->model_extension_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('extension/total/' . $result['code']);

				// We have to put the totals in an array so that they pass by reference.
				$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
			}
		}

		$sort_order = array();

		foreach ($totals as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $totals);

		return array($totals, $taxes, $total);
	}

	private function connector($merchant_id, $secret, $url) {
		try {
			$connector = KCConnector::create(
				$merchant_id,
				$secret,
				$url
			);

			return $connector;
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 1);

			return false;
		}
	}
}