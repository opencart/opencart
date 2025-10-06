<?php
namespace Opencart\System\Library\Cart;
/**
 * Class Cart
 *
 * @package Opencart\System\Library\Cart
 */
class Cart {
	/**
	 * @var object
	 */
	private object $db;
	/**
	 * @var object
	 */
	private object $config;
	/**
	 * @var object
	 */
	private object $customer;
	/**
	 * @var object
	 */
	private object $session;
	/**
	 * @var object
	 */
	private object $tax;
	/**
	 * @var object
	 */
	private object $weight;
	/**
	 * @var array<int, array<string, mixed>>
	 */
	private array $data = [];

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		// Remove all the expired carts for visitors who never registered
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cart` WHERE `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `customer_id` = '0' AND `date_added` < DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('session_expire') . " SECOND)");

		if ($this->customer->isLogged()) {
			// We want to change the session ID on all the old items in the customers cart
			$this->db->query("UPDATE `" . DB_PREFIX . "cart` SET `session_id` = '" . $this->db->escape($this->session->getId()) . "', `date_added` = NOW() WHERE `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

			// Once the customer is logged in we want to update the customers cart
			$this->db->query("UPDATE `" . DB_PREFIX . "cart` SET `customer_id` = '" . (int)$this->customer->getId() . "', `date_added` = NOW() WHERE `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `customer_id` = '0' AND `session_id` = '" . $this->db->escape($this->session->getId()) . "'");
		}

		// Populate the cart data
		$this->data = $this->getProducts();
	}

	/**
	 * Get Products
	 *
	 * @return array<int, array<string, mixed>> product records
	 *
	 * @example
	 *
	 * $cart = $this->cart->getProducts();
	 */
	public function getProducts(): array {
		if (!$this->data) {
			$cart_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cart` WHERE `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `session_id` = '" . $this->db->escape($this->session->getId()) . "'");

			foreach ($cart_query->rows as $cart) {
				$stock_status = true;

				$product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_store` `p2s` LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p2s`.`product_id` = `p`.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`) WHERE `p2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `p2s`.`product_id` = '" . (int)$cart['product_id'] . "' AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `p`.`date_available` <= NOW() AND `p`.`status` = '1'");

				if ($product_query->num_rows && ($cart['quantity'] > 0)) {
					$stock = $product_query->row['quantity'];

					$option_price = 0;
					$option_points = 0;
					$option_weight = 0;

					$option_data = [];

					$product_options = (array) json_decode(!empty($cart['option']) ? $cart['option'] : '{}', true);

					$variant = json_decode(!empty($product_query->row['variant']) ? $product_query->row['variant'] : '{}', true);


					if ($variant) {
						foreach ($variant as $key => $value) {
							$product_options[$key] = $value;
						}
					}

					if (!$product_query->row['master_id']) {
						$product_id = $cart['product_id'];
					} else {
						$product_id = $product_query->row['master_id'];
					}

					foreach ($product_options as $product_option_id => $value) {
						$option_query = $this->db->query("SELECT `po`.`product_option_id`, `po`.`option_id`, `od`.`name`, `o`.`type` FROM `" . DB_PREFIX . "product_option` `po` LEFT JOIN `" . DB_PREFIX . "option` `o` ON (`po`.`option_id` = `o`.`option_id`) LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`o`.`option_id` = `od`.`option_id`) WHERE `po`.`product_option_id` = '" . (int)$product_option_id . "' AND `po`.`product_id` = '" . (int)$product_id . "' AND `od`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio') {
								$option_value_query = $this->db->query("SELECT `pov`.`option_value_id`, `ovd`.`name`, `pov`.`quantity`, `pov`.`subtract`, `pov`.`price`, `pov`.`price_prefix`, `pov`.`points`, `pov`.`points_prefix`, `pov`.`weight`, `pov`.`weight_prefix` FROM `" . DB_PREFIX . "product_option_value` `pov` LEFT JOIN `" . DB_PREFIX . "option_value` `ov` ON (`pov`.`option_value_id` = `ov`.`option_value_id`) LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ov`.`option_value_id` = `ovd`.`option_value_id`) WHERE `pov`.`product_option_value_id` = '" . (int)$value . "' AND `pov`.`product_option_id` = '" . (int)$product_option_id . "' AND `ovd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
										$stock_status = false;
									}

									$option_data[] = [
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $value,
										'value'                   => $option_value_query->row['name']
									] + $option_query->row + $option_value_query->row;
								}
							} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
								foreach ($value as $product_option_value_id) {
									$option_value_query = $this->db->query("SELECT `pov`.`option_value_id`, `pov`.`quantity`, `pov`.`subtract`, `pov`.`price`, `pov`.`price_prefix`, `pov`.`points`, `pov`.`points_prefix`, `pov`.`weight`, `pov`.`weight_prefix`, `ovd`.`name` FROM `" . DB_PREFIX . "product_option_value` `pov` LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`pov`.`option_value_id` = `ovd`.option_value_id) WHERE `pov`.product_option_value_id = '" . (int)$product_option_value_id . "' AND `pov`.product_option_id = '" . (int)$product_option_id . "' AND `ovd`.language_id = '" . (int)$this->config->get('config_language_id') . "'");

									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}

										if ($option_value_query->row['points_prefix'] == '+') {
											$option_points += $option_value_query->row['points'];
										} elseif ($option_value_query->row['points_prefix'] == '-') {
											$option_points -= $option_value_query->row['points'];
										}

										if ($option_value_query->row['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row['weight'];
										} elseif ($option_value_query->row['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row['weight'];
										}

										if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
											$stock_status = false;
										}

										$option_data[] = [
											'product_option_id'       => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'value'                   => $option_value_query->row['name']
										] + $option_query->row + $option_value_query->row;
									}
								}
							} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
								$option_data[] = [
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => 0,
									'option_value_id'         => 0,
									'value'                   => $value,
									'quantity'                => 0,
									'subtract'                => 0,
									'price'                   => 0,
									'price_prefix'            => '',
									'points'                  => 0,
									'points_prefix'           => '',
									'weight'                  => 0,
									'weight_prefix'           => ''
								] + $option_query->row;
							}
						}
					}

					// Get total products of the same product but with different options
					$product_total = 0;

					foreach ($cart_query->rows as $cart_2) {
						if ($cart_2['product_id'] == $cart['product_id']) {
							$product_total += $cart_2['quantity'];
						}
					}

					$price = $product_query->row['price'] + $option_price;

					$subscription_data = [];

					$subscription_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_subscription` `ps` LEFT JOIN `" . DB_PREFIX . "subscription_plan` `sp` ON (`ps`.`subscription_plan_id` = `sp`.`subscription_plan_id`) LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` `spd` ON (`sp`.`subscription_plan_id` = `spd`.`subscription_plan_id`) WHERE `ps`.`product_id` = '" . (int)$cart['product_id'] . "' AND `ps`.`subscription_plan_id` = '" . (int)$cart['subscription_plan_id'] . "' AND `ps`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `spd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `sp`.`status` = '1'");

					if ($subscription_query->num_rows) {
						$subscription_data = ['remaining' => $subscription_query->row['duration']] + $subscription_query->row;

						// Set the new price if is subscription product
						$price = $subscription_query->row['price'];

						if ($subscription_query->row['trial_status']) {
							$price = $subscription_query->row['trial_price'];
						}
					}

					// Product Discounts
					$product_discount_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_discount` WHERE `product_id` = '" . (int)$cart['product_id'] . "' AND `customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `quantity` <= '" . (int)$product_total . "' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) ORDER BY `quantity` DESC, `priority` ASC, `price` ASC LIMIT 1");

					if ($product_discount_query->num_rows) {
						if ($product_discount_query->row['type'] == 'F') {
							// Fixed Price
							$price = $product_discount_query->row['price'] + $option_price;
						} elseif ($product_discount_query->row['type'] == 'P') {
							// Percentage
							$price -= ($price * ($product_discount_query->row['price'] / 100));
						} elseif ($product_discount_query->row['type'] == 'S') {
							// Subtract
							$price -= $product_discount_query->row['price'];
						}
					}

					// Stock
					if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $product_total)) {
						$stock_status = false;
					}

					// Minimum Quantity
					if ($product_query->row['minimum'] > $product_total) {
						$minimum = false;
					} else {
						$minimum = true;
					}

					// Reward Points
					$product_reward_query = $this->db->query("SELECT `points` FROM `" . DB_PREFIX . "product_reward` WHERE `product_id` = '" . (int)$cart['product_id'] . "' AND `customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "'");

					if ($product_reward_query->num_rows) {
						$reward = $product_reward_query->row['points'];
					} else {
						$reward = 0;
					}

					// Downloads
					$download_data = [];

					$download_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_download` `p2d` LEFT JOIN `" . DB_PREFIX . "download` `d` ON (`p2d`.`download_id` = `d`.`download_id`) LEFT JOIN `" . DB_PREFIX . "download_description` `dd` ON (`d`.`download_id` = `dd`.`download_id`) WHERE `p2d`.`product_id` = '" . (int)$cart['product_id'] . "' AND `dd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

					foreach ($download_query->rows as $download) {
						$download_data[] = $download;
					}

					$this->data[$cart['cart_id']] = [
						'cart_id'        => $cart['cart_id'],
						'option'         => $option_data,
						'subscription'   => $subscription_data,
						'download'       => $download_data,
						'quantity'       => $cart['quantity'],
						'minimum_status' => $minimum,
						'stock'          => $stock,
						'stock_status'   => $stock_status,
						'price'          => $price,
						'total'          => $price * $cart['quantity'],
						'reward'         => $reward * $cart['quantity'],
						'points'         => $product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0,
						'weight'         => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
					] + $product_query->row;

					// Use with order editor and subscriptions
					if ($cart['override']) {
						$override = json_decode($cart['override']);
					} else {
						$override = [];
					}

					foreach ($override as $key => $value) {
						$this->data[$cart['cart_id']][$key] = $value;
					}
				} else {
					$this->remove($cart['cart_id']);
				}
			}
		}

		return $this->data;
	}

	/**
	 * Add
	 *
	 * @param int                  $product_id primary key of the product record
	 * @param int                  $quantity
	 * @param array<string, mixed> $option
	 * @param int                  $subscription_plan_id primary key of the subscription plan record
	 * @param array<string, mixed> $override
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->cart->add($product_id, $quantity, $option, $subscription_plan_id, $override);
	 */
	public function add(int $product_id, int $quantity = 1, array $option = [], int $subscription_plan_id = 0, array $override = []): void {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "cart` WHERE `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `session_id` = '" . $this->db->escape($this->session->getId()) . "' AND `product_id` = '" . (int)$product_id . "' AND `subscription_plan_id` = '" . (int)$subscription_plan_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");

		if (!$query->row['total']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "cart` SET `store_id` = '" . (int)$this->config->get('config_store_id') . "', `customer_id` = '" . (int)$this->customer->getId() . "', `session_id` = '" . $this->db->escape($this->session->getId()) . "', `product_id` = '" . (int)$product_id . "', `subscription_plan_id` = '" . (int)$subscription_plan_id . "', `option` = '" . $this->db->escape(json_encode($option)) . "', `quantity` = '" . (int)$quantity . "', `override` = '" . $this->db->escape(json_encode($override)) . "', `date_added` = NOW()");
		} else {
			$this->db->query("UPDATE `" . DB_PREFIX . "cart` SET `quantity` = (`quantity` + " . (int)$quantity . ") WHERE `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `session_id` = '" . $this->db->escape($this->session->getId()) . "' AND `product_id` = '" . (int)$product_id . "' AND `subscription_plan_id` = '" . (int)$subscription_plan_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");
		}

		$this->data = [];
	}

	/**
	 * Update
	 *
	 * @param int $cart_id  primary key of the cart record
	 * @param int $quantity
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->cart->update($cart_id, $quantity);
	 */
	public function update(int $cart_id, int $quantity): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "cart` SET `quantity` = '" . (int)$quantity . "' WHERE `cart_id` = '" . (int)$cart_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `session_id` = '" . $this->db->escape($this->session->getId()) . "'");

		$this->data = [];
	}

	/**
	 * Has
	 *
	 * @param int $cart_id primary key of the cart record
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $cart = $this->cart->has($cart_id);
	 */
	public function has(int $cart_id): bool {
		return isset($this->data[$cart_id]);
	}

	/**
	 * Remove
	 *
	 * @param int $cart_id primary key of the cart record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $cart = $this->cart->remove($cart_id);
	 */
	public function remove(int $cart_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cart` WHERE `cart_id` = '" . (int)$cart_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `session_id` = '" . $this->db->escape($this->session->getId()) . "'");

		unset($this->data[$cart_id]);
	}

	/**
	 * Clear
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->cart->clear();
	 */
	public function clear(): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cart` WHERE `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `session_id` = '" . $this->db->escape($this->session->getId()) . "'");

		$this->data = [];
	}

	/**
	 * Get Subscriptions
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $subscriptions = $this->cart->getSubscriptions();
	 */
	public function getSubscriptions(): array {
		$product_data = [];

		foreach ($this->getProducts() as $value) {
			if ($value['subscription']) {
				$product_data[] = $value;
			}
		}

		return $product_data;
	}

	/**
	 * Get Weight
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $weight = $this->cart->getWeight();
	 */
	public function getWeight(): float {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	/**
	 * Get Sub Total
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $sub_total = $this->cart->getSubTotal();
	 */
	public function getSubTotal(): float {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	/**
	 * Get Taxes
	 *
	 * @return array<int, float>
	 *
	 * @example
	 *
	 * $taxes = $this->cart->getTaxes();
	 */
	public function getTaxes(): array {
		$tax_data = [];

		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if ($tax_rate['type'] == 'P') {
						$quantity = $product['quantity'];
					} else {
						$quantity = 1;
					}

					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $quantity);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $quantity);
					}
				}
			}
		}

		return $tax_data;
	}

	/**
	 * Get Total
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $total = $this->cart->getTotal();
	 */
	public function getTotal(): float {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	/**
	 * Count Products
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $count_products = $this->cart->countProducts();
	 */
	public function countProducts(): int {
		$product_total = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}

		return $product_total;
	}

	/**
	 * Has Products
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $cart = $this->cart->hasProducts();
	 */
	public function hasProducts(): bool {
		return (bool)count($this->getProducts());
	}

	/**
	 * Has Subscription
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $cart = $this->cart->hasSubscription();
	 */
	public function hasSubscription(): bool {
		return (bool)count($this->getSubscriptions());
	}

	/**
	 * Has Stock
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $cart = $this->cart->hasStock();
	 */
	public function hasStock(): bool {
		foreach ($this->getProducts() as $product) {
			if (!$product['stock_status']) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Has Minimum
	 *
	 * Check if any products have a minimum order quantity amount and do not meet the requirement
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $cart = $this->cart->hasMinimum();
	 */
	public function hasMinimum() {
		foreach ($this->getProducts() as $product) {
			if (!$product['minimum_status']) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Has Shipping
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $cart = $this->cart->hasShipping();
	 */
	public function hasShipping(): bool {
		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Has Download
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $cart = $this->cart->hasDownload();
	 */
	public function hasDownload(): bool {
		foreach ($this->getProducts() as $product) {
			if ($product['download']) {
				return true;
			}
		}

		return false;
	}
}
