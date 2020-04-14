<?php
namespace Cart;
class Cart {
	private $data = array();

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');
		$this->load = $registry->get('load');

		// Remove all the expired carts with no customer ID
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE (api_id > '0' OR customer_id = '0') AND date_added < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

		if ($this->customer->getId()) {
			// We want to change the session ID on all the old items in the customers cart
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE api_id = '0' AND customer_id = '" . (int)$this->customer->getId() . "'");

			// Once the customer is logged in we want to update the customers cart
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '0' AND customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

			foreach ($cart_query->rows as $cart) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

				// The advantage of using $this->add is that it will check if the products already exist and increase the quantity if necessary.
				$this->add($cart['product_id'], $cart['quantity'], json_decode($cart['option'], true), $cart['recurring_id']);
			}
		}
	}

	public function getProducts() {
		if (!$this->data) {
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
			
			$this->load->model('catalog/product');

			foreach ($cart_query->rows as $cart) {
				$stock = true;
				
				$product_query = $this->model_catalog_product->getProduct($cart['product_id']);

				if ($product_query && ($cart['quantity'] > 0)) {
					$option_price = 0;
					$option_points = 0;
					$option_weight = 0;

					$option_data = array();

					$product_options = (array)json_decode($cart['option'], true);

					// Merge variant code with options
					foreach ((array)json_decode($product_query['variant'], true) as $key => $value) {
						$product_options[$key] = $value;
					}

					foreach ($product_options as $product_option_id => $value) {
						if (!$product_query['master_id']) {
							$product_id = $cart['product_id'];
						} else {
							$product_id = $product_query['master_id'];
						}

						$option_query = $this->model_catalog_product->getOption($product_option_id, $product_id);

						if ($option_query) {
							if ($option_query['type'] == 'select' || $option_query['type'] == 'radio') {
								$option_value_query = $this->model_catalog_product->getOptionValue($value, $product_option_id);

								if ($option_value_query) {
									if ($option_value_query['price_prefix'] == '+') {
										$option_price += $option_value_query['price'];
									} elseif ($option_value_query['price_prefix'] == '-') {
										$option_price -= $option_value_query['price'];
									}

									if ($option_value_query['points_prefix'] == '+') {
										$option_points += $option_value_query['points'];
									} elseif ($option_value_query['points_prefix'] == '-') {
										$option_points -= $option_value_query['points'];
									}

									if ($option_value_query['weight_prefix'] == '+') {
										$option_weight += $option_value_query['weight'];
									} elseif ($option_value_query['weight_prefix'] == '-') {
										$option_weight -= $option_value_query['weight'];
									}

									if ($option_value_query['subtract'] && (!$option_value_query['quantity'] || ($option_value_query['quantity'] < $cart['quantity']))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $value,
										'option_id'               => $option_query['option_id'],
										'option_value_id'         => $option_value_query['option_value_id'],
										'name'                    => $option_query['name'],
										'value'                   => $option_value_query['name'],
										'type'                    => $option_query['type'],
										'quantity'                => $option_value_query['quantity'],
										'subtract'                => $option_value_query['subtract'],
										'price'                   => $option_value_query['price'],
										'price_prefix'            => $option_value_query['price_prefix'],
										'points'                  => $option_value_query['points'],
										'points_prefix'           => $option_value_query['points_prefix'],
										'weight'                  => $option_value_query['weight'],
										'weight_prefix'           => $option_value_query['weight_prefix']
									);
								}
							} elseif ($option_query['type'] == 'checkbox' && is_array($value)) {
								foreach ($value as $product_option_value_id) {
									$option_value_query = $this->model_catalog_product->getOptionValue($product_option_value_id, $product_option_id);

									if ($option_value_query) {
										if ($option_value_query['price_prefix'] == '+') {
											$option_price += $option_value_query['price'];
										} elseif ($option_value_query['price_prefix'] == '-') {
											$option_price -= $option_value_query['price'];
										}

										if ($option_value_query['points_prefix'] == '+') {
											$option_points += $option_value_query['points'];
										} elseif ($option_value_query['points_prefix'] == '-') {
											$option_points -= $option_value_query['points'];
										}

										if ($option_value_query['weight_prefix'] == '+') {
											$option_weight += $option_value_query['weight'];
										} elseif ($option_value_query['weight_prefix'] == '-') {
											$option_weight -= $option_value_query['weight'];
										}

										if ($option_value_query['subtract'] && (!$option_value_query['quantity'] || ($option_value_query['quantity'] < $cart['quantity']))) {
											$stock = false;
										}

										$option_data[] = array(
											'product_option_id'       => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'option_id'               => $option_query['option_id'],
											'option_value_id'         => $option_value_query['option_value_id'],
											'name'                    => $option_query['name'],
											'value'                   => $option_value_query['name'],
											'type'                    => $option_query['type'],
											'quantity'                => $option_value_query['quantity'],
											'subtract'                => $option_value_query['subtract'],
											'price'                   => $option_value_query['price'],
											'price_prefix'            => $option_value_query['price_prefix'],
											'points'                  => $option_value_query['points'],
											'points_prefix'           => $option_value_query['points_prefix'],
											'weight'                  => $option_value_query['weight'],
											'weight_prefix'           => $option_value_query['weight_prefix']
										);
									}
								}
							} elseif ($option_query['type'] == 'text' || $option_query['type'] == 'textarea' || $option_query['type'] == 'file' || $option_query['type'] == 'date' || $option_query['type'] == 'datetime' || $option_query['type'] == 'time') {
								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => '',
									'option_id'               => $option_query['option_id'],
									'option_value_id'         => '',
									'name'                    => $option_query['name'],
									'value'                   => $value,
									'type'                    => $option_query['type'],
									'quantity'                => '',
									'subtract'                => '',
									'price'                   => '',
									'price_prefix'            => '',
									'points'                  => '',
									'points_prefix'           => '',
									'weight'                  => '',
									'weight_prefix'           => ''
								);
							}
						}
					}

					$price = $product_query['price'];

					// Reward Points
					if ($product_query['reward']) {
						$reward = $product_query['points'];
					} else {
						$reward = 0;
					}

					// Downloads
					$download_data = array();

					$download_query = $this->model_catalog_product->getDownloads($cart['product_id']);

					foreach ($download_query as $download) {
						$download_data[] = array(
							'download_id' => $download['download_id'],
							'name'        => $download['name'],
							'filename'    => $download['filename'],
							'mask'        => $download['mask']
						);
					}

					// Stock
					if (!$product_query['quantity'] || ($product_query['quantity'] < $cart['quantity'])) {
						$stock = false;
					}

					$recurring_query = $this->model_catalog_product->getProfile($cart['product_id'], $cart['recurring_id']);

					if ($recurring_query) {
						$recurring = array(
							'recurring_id'    => $cart['recurring_id'],
							'name'            => $recurring_query['name'],
							'frequency'       => $recurring_query['frequency'],
							'price'           => $recurring_query['price'],
							'cycle'           => $recurring_query['cycle'],
							'duration'        => $recurring_query['duration'],
							'trial'           => $recurring_query['trial_status'],
							'trial_frequency' => $recurring_query['trial_frequency'],
							'trial_price'     => $recurring_query['trial_price'],
							'trial_cycle'     => $recurring_query['trial_cycle'],
							'trial_duration'  => $recurring_query['trial_duration']
						);
					} else {
						$recurring = false;
					}

					$this->data[] = array(
						'cart_id'         => $cart['cart_id'],
						'product_id'      => $product_query['product_id'],
						'master_id'       => $product_query['master_id'],
						'name'            => $product_query['name'],
						'model'           => $product_query['model'],
						'shipping'        => $product_query['shipping'],
						'image'           => $product_query['image'],
						'option'          => $option_data,
						'download'        => $download_data,
						'quantity'        => $cart['quantity'],
						'minimum'         => $product_query['minimum'],
						'subtract'        => $product_query['subtract'],
						'stock'           => $stock,
						'price'           => ($price + $option_price),
						'total'           => ($price + $option_price) * $cart['quantity'],
						'reward'          => $reward * $cart['quantity'],
						'points'          => ($product_query['points'] ? ($product_query['points'] + $option_points) * $cart['quantity'] : 0),
						'tax_class_id'    => $product_query['tax_class_id'],
						'weight'          => ($product_query['weight'] + $option_weight) * $cart['quantity'],
						'weight_class_id' => $product_query['weight_class_id'],
						'length'          => $product_query['length'],
						'width'           => $product_query['width'],
						'height'          => $product_query['height'],
						'length_class_id' => $product_query['length_class_id'],
						'recurring'       => $recurring
					);
				} else {
					$this->remove($cart['cart_id']);
				}
			}
		}

		return $this->data;
	}

	public function add($product_id, $quantity = 1, $option = array(), $recurring_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");

		if (!$query->row['total']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "cart` SET api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "', customer_id = '" . (int)$this->customer->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', product_id = '" . (int)$product_id . "', recurring_id = '" . (int)$recurring_id . "', `option` = '" . $this->db->escape(json_encode($option)) . "', quantity = '" . (int)$quantity . "', date_added = NOW()");
		} else {
			$this->db->query("UPDATE `" . DB_PREFIX . "cart` SET quantity = (quantity + " . (int)$quantity . ") WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");
		}

		$this->data = array();
	}

	public function update($cart_id, $quantity) {
		$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = '" . (int)$quantity . "' WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		$this->data = array();
	}

	public function remove($cart_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		$this->data = array();
	}

	public function clear() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		$this->data = array();
	}

	public function getRecurringProducts() {
		$product_data = array();

		foreach ($this->getProducts() as $value) {
			if ($value['recurring']) {
				$product_data[] = $value;
			}
		}

		return $product_data;
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	public function countProducts() {
		$product_total = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}

		return $product_total;
	}

	public function hasProducts() {
		return count($this->getProducts());
	}

	public function hasRecurringProducts() {
		return count($this->getRecurringProducts());
	}

	public function hasStock() {
		foreach ($this->getProducts() as $product) {
			if (!$product['stock']) {
				return false;
			}
		}

		return true;
	}

	public function hasShipping() {
		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				return true;
			}
		}

		return false;
	}

	public function hasDownload() {
		foreach ($this->getProducts() as $product) {
			if ($product['download']) {
				return true;
			}
		}

		return false;
	}
}
