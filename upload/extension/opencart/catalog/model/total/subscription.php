<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Subscription
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Subscription extends \Opencart\System\Engine\Model {
	/**
	 * Get Total
	 *
	 * @param array<int, array<string, mixed>> $totals
	 * @param array<int, float>                $taxes
	 * @param float                            $total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if (isset($this->session->data['subscription_discount'])) {
			$this->load->language('extension/opencart/total/subscription', 'subscription');

			if ($this->cart->hasSubscription()) {
				$subscription_discount_info = $this->model_extension_opencart_total_subscription->getDiscount($this->session->data['subscription_discount']);

				if ($subscription_discount_info) {
					$implode = [];

					foreach ($subscription_discount_info['recurring'] as $recurring_id) {
						$implode[] = "`r`.`recurring_id` = '" . (int)$recurring_id . "'";
					}

					$cart = $this->cart->getProducts();

					$discount_total = 0;

					foreach ($cart as $product) {
						if (in_array($product['product_id'], $subscription_discount_info['product'])) {
							$discount = 0;

							if ($implode) {
								$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_recurring` `pr` INNER JOIN `" . DB_PREFIX . "recurring` `r` ON (`r`.`recurring_id` = `pr`.`recurring_id`) WHERE `pr`.`product_id` = '" . (int)$product['product_id'] . "' AND (" . implode(" OR ", $implode) . ") AND `r`.`status` = '1'");

								if ($query->num_rows == 1) {
									$recurring_data = [$query->row];
								} elseif ($query->num_rows > 1) {
									$recurring_data = $query->rows;
								}

								if ($recurring_data) {
									$trial_end = new \DateTime('now');
									$subscription_end = new \DateTime('now');

									foreach ($recurring_data as $recurring) {
										if (($recurring['trial'] == 1) && ($recurring['trial_duration'] != 0)) {
											$trial_end = $this->calculateSchedule($recurring['trial_frequency'], $trial_end, $recurring['trial_cycle'] * $recurring['trial_duration']);
										} elseif ($recurring['trial'] == 1) {
											$trial_end = new \DateTime('0000-00-00');
										}

										if (date_format($trial_end, 'Y-m-d H:i:s') > date_format($subscription_end, 'Y-m-d H:i:s') && $recurring['duration'] != 0) {
											$subscription_end = new \DateTime(date_format($trial_end, 'Y-m-d H:i:s'));
											$subscription_end = $this->calculateSchedule($recurring['frequency'], $subscription_end, $recurring['cycle'] * $recurring['duration']);
										} elseif (date_format($trial_end, 'Y-m-d H:i:s') == date_format($subscription_end, 'Y-m-d H:i:s') && $recurring['duration'] != 0) {
											$subscription_end = $this->calculateSchedule($recurring['frequency'], $subscription_end, $recurring['cycle'] * $recurring['duration']);
										} elseif (date_format($trial_end, 'Y-m-d H:i:s') > date_format($subscription_end, 'Y-m-d H:i:s') && $recurring['duration'] == 0) {
											$subscription_end = new \DateTime('0000-00-00');
										} elseif (date_format($trial_end, 'Y-m-d H:i:s') == date_format($subscription_end, 'Y-m-d H:i:s') && $recurring['duration'] == 0) {
											$subscription_end = new \DateTime('0000-00-00');
										}

										$recurring_expiry = date_format($subscription_end, 'Y-m-d');

										$discount_expiry = date('Y-m-d', strtotime($subscription_discount_info['date_end']));

										if ($discount_expiry <= $recurring_expiry) {
											$discount = $product['total'] / 100 * $subscription_discount_info['discount'];
											$discount_total += $discount;
										}
									}
								}
							} else {
								$discount = $product['total'] / 100 * $subscription_discount_info['discount'];
								$discount_total += $discount;
							}

							if ($product['tax_class_id']) {
								$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

								foreach ($tax_rates as $tax_rate) {
									$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
								}
							}
						}
					}

					// If discount greater than total
					if ($discount_total > $total) {
						$discount_total = $total;
					}

					if ($discount_total > 0) {
						$totals[] = [
							'extension'  => 'opencart',
							'code'       => 'subscription',
							'title'      => sprintf($this->language->get('subscription_text_subscription'), $this->session->data['subscription_discount']),
							'value'      => -$discount_total,
							'sort_order' => (int)$this->config->get('total_subscription_sort_order')
						];

						$total -= $discount_total;
					}
				}
			}
		}
	}

	/**
	 * Get Discount
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getDiscount(string $code): array {
		$status = true;

		$subscription_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_discount` WHERE `code` = '" . $this->db->escape($code) . "' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) AND `status` = '1'");

		if ($subscription_query->num_rows) {
			if ($subscription_query->row['total'] > $this->cart->getSubTotal()) {
				$status = false;
			}

			$this->load->model('account/order');

			$subscription_total = $this->model_account_order->getTotalSubscriptions();

			if ($subscription_query->row['uses_total'] > 0 && ($subscription_total >= $subscription_query->row['uses_total'])) {
				$status = false;
			}

			$subscription_total = $this->getTotalHistoriesBySubscriptionsDiscount($code);

			if ($subscription_query->row['uses_total'] > 0 && ($subscription_total >= $subscription_query->row['uses_total'])) {
				$status = false;
			}

			if (!$this->customer->getId()) {
				$status = false;
			}

			if ($this->customer->getId()) {
				$customer_total = $this->getTotalHistoriesByCustomerId($code, $this->customer->getId());

				if ($subscription_query->row['uses_customer'] > 0 && ($customer_total >= $subscription_query->row['uses_customer'])) {
					$status = false;
				}
			}

			// Products
			$subscription_product_data = [];

			$subscription_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_discount_product` WHERE `subscription_discount_id` = '" . (int)$subscription_query->row['subscription_discount_id'] . "'");

			foreach ($subscription_product_query->rows as $product) {
				$subscription_product_data[] = $product['product_id'];
			}

			$product_data = [];

			if ($subscription_product_data && $this->cart->hasSubscription()) {
				foreach ($this->cart->getProducts() as $product) {
					if ($product['subscription'] && in_array($product['product_id'], $subscription_product_data)) {
						$product_data[] = $product['product_id'];

						continue;
					}
				}

				if (!$product_data) {
					$status = false;
				}
			}

			// Recurring
			$recurring_data = [];

			$recurring_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_discount_recurring` `sdr` INNER JOIN `" . DB_PREFIX . "product_recurring` `pr` ON (`pr`.`recurring_id` = `sdr`.`recurring_id`) INNER JOIN `" . DB_PREFIX . "recurring` `r` ON (`r`.`recurring_id` = `pr`.`recurring_id`) WHERE `sdr`.`subscription_discount_id` = '" . (int)$subscription_query->row['subscription_discount_id'] . "' AND `pr`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `r`.`status` = '1'");

			foreach ($recurring_query->rows as $recurring) {
				$recurring_data[] = $recurring['recurring_id'];
			}
		} else {
			$status = false;
		}

		if ($status) {
			return [
				'subscription_discount_id' => $subscription_query->row['subscription_discount_id'],
				'code'                     => $subscription_query->row['code'],
				'name'                     => $subscription_query->row['name'],
				'discount'                 => $subscription_query->row['discount'],
				'total'                    => $subscription_query->row['total'],
				'product'                  => $product_data,
				'recurring'                => $recurring_data,
				'date_start'               => $subscription_query->row['date_start'],
				'date_end'                 => $subscription_query->row['date_end'],
				'uses_total'               => $subscription_query->row['uses_total'],
				'uses_customer'            => $subscription_query->row['uses_customer'],
				'status'                   => $subscription_query->row['status'],
				'date_added'               => $subscription_query->row['date_added']
			];
		} else {
			return [];
		}
	}

	/**
	 * Confirm
	 *
	 * @param array<string, mixed> $order_info
	 * @param array<string, mixed> $order_total
	 *
	 * @return int
	 */
	public function confirm(array $order_info, array $order_total): int {
		$code = '';

		$start = strpos($order_total['title'], '(');
		$end = strrpos($order_total['title'], ')');

		if ($start !== false && $end !== false) {
			$code = substr($order_total['title'], $start + 1, $end - ($start + 1));
		}

		if ($code) {
			$status = true;

			$subscription_discount_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_discount` WHERE `code` = '" . $this->db->escape($code) . "' AND `status` = '1'");

			if ($subscription_discount_query->num_rows) {
				$subscription_discount_total = $this->model_extension_opencart_total_subscription->getTotalHistoriesBySubscriptionsDiscount($code);

				if ($subscription_discount_query->row['uses_total'] > 0 && ($subscription_discount_total >= $subscription_discount_query->row['uses_total'])) {
					$status = false;
				}

				if ($order_info['customer_id']) {
					$customer_total = $this->model_extension_opencart_total_subscription->getTotalHistoriesByCustomerId($code, $order_info['customer_id']);

					if ($subscription_discount_query->row['uses_customer'] > 0 && ($customer_total >= $subscription_discount_query->row['uses_customer'])) {
						$status = false;
					}
				}
			} else {
				$status = false;
			}

			if ($status) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_discount_history` SET `subscription_discount_id` = '" . (int)$subscription_discount_query->row['subscription_discount_id'] . "', `order_id` = '" . (int)$order_info['order_id'] . "', `customer_id` = '" . (int)$order_info['customer_id'] . "', `amount` = '" . (float)$order_total['value'] . "', `date_added` = NOW()");
			} else {
				return $this->config->get('config_fraud_status_id');
			}
		}

		return 0;
	}

	/**
	 * Unconfirm
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function unconfirm(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_discount_history` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * Get Total Histories By Subscriptions Discount
	 *
	 * @param string $code
	 *
	 * @return int
	 */
	private function getTotalHistoriesBySubscriptionsDiscount(string $code): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_discount_history` `sdh` LEFT JOIN `" . DB_PREFIX . "subscription_discount` `sd` ON (`sdh`.`subscription_discount_id` = `sd`.`subscription_discount_id`) WHERE `sd`.`code` = '" . $this->db->escape($code) . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Histories By Customer ID
	 *
	 * @param string $code
	 * @param int    $customer_id
	 *
	 * @return int
	 */
	private function getTotalHistoriesByCustomerId(string $code, int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_discount_history` `sdh` LEFT JOIN `" . DB_PREFIX . "subscription_discount` `sd` ON (`sdh`.`subscription_discount_id` = `sd`.`subscription_discount_id`) WHERE `sd`.`code` = '" . $this->db->escape($code) . "' AND `sdh`.`customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Calculate Schedule
	 *
	 * @param string    $frequency
	 * @param \Datetime $next_payment
	 * @param int       $cycle
	 *
	 * @return \Datetime
	 */
	private function calculateSchedule(string $frequency, \DateTime $next_payment, int $cycle) {
		$next_payment = clone $next_payment;

		if ($frequency == 'semi_month') {
			$day = $next_payment->format('d');
			$value = 15 - $day;
			$is_even = false;

			if ($cycle % 2 == 0) {
				$is_even = true;
			}

			$odd = ($cycle + 1) / 2;
			$plus_even = ($cycle / 2) + 1;
			$minus_even = $cycle / 2;

			if ($day == 1) {
				$odd--;
				$plus_even--;
				$day = 16;
			}

			if ($day <= 15 && $is_even) {
				$next_payment->modify('+' . $value . ' day');
				$next_payment->modify('+' . $minus_even . ' month');
			} elseif ($day <= 15) {
				$next_payment->modify('first day of this month');
				$next_payment->modify('+' . $odd . ' month');
			} elseif ($day > 15 && $is_even) {
				$next_payment->modify('first day of this month');
				$next_payment->modify('+' . $plus_even . ' month');
			} elseif ($day > 15) {
				$next_payment->modify('+' . $value . ' day');
				$next_payment->modify('+' . $odd . ' month');
			}
		} else {
			$next_payment->modify('+' . $cycle . ' ' . $frequency);
		}

		return $next_payment;
	}
}
