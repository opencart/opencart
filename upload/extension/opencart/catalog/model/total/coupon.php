<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Coupon
 *
 * @package
 */
class Coupon extends \Opencart\System\Engine\Model {
	/**
	 * @param array $totals
	 * @param array $taxes
	 * @param float $total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if (isset($this->session->data['coupon'])) {
			$this->load->language('extension/opencart/total/coupon', 'coupon');

			$this->load->model('marketing/coupon');

			$coupon_info = $this->model_marketing_coupon->getCoupon($this->session->data['coupon']);

			if ($coupon_info) {
				$discount_total = 0;

				$products = $this->cart->getProducts();

				if (!$coupon_info['product']) {
					$sub_total = $this->cart->getSubTotal();
				} else {
					$sub_total = 0;

					foreach ($products as $product) {
						if (in_array($product['product_id'], $coupon_info['product'])) {
							$sub_total += $product['total'];
						}
					}
				}

				if ($coupon_info['type'] == 'F') {
					$coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
				}

				foreach ($products as $product) {
					$discount = 0;

					if (!$coupon_info['product']) {
						$status = true;
					} else {
						$status = in_array($product['product_id'], $coupon_info['product']);
					}

					if ($status) {
						if ($coupon_info['type'] == 'F') {
							$discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
						} elseif ($coupon_info['type'] == 'P') {
							$discount = $product['total'] / 100 * $coupon_info['discount'];
						}

						if ($product['tax_class_id']) {
							$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

							foreach ($tax_rates as $tax_rate) {
								if ($tax_rate['type'] == 'P') {
									$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
								}
							}
						}
					}

					$discount_total += $discount;
				}

				if ($coupon_info['shipping'] && isset($this->session->data['shipping_method']['cost']) && isset($this->session->data['shipping_method']['tax_class_id'])) {
					if (!empty($this->session->data['shipping_method']['tax_class_id'])) {
						$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							if ($tax_rate['type'] == 'P') {
								$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
					}

					$discount_total += $this->session->data['shipping_method']['cost'];
				}

				// If discount greater than total
				if ($discount_total > $total) {
					$discount_total = $total;
				}

				if ($discount_total > 0) {
					$totals[] = [
						'extension'  => 'opencart',
						'code'       => 'coupon',
						'title'      => sprintf($this->language->get('coupon_text_coupon'), $this->session->data['coupon']),
						'value'      => -$discount_total,
						'sort_order' => (int)$this->config->get('total_coupon_sort_order')
					];

					$total -= $discount_total;
				}
			}
		}
	}

	/**
	 * @param array $order_info
	 * @param array $order_total
	 *
	 * @return int
	 */
	public function confirm(array $order_info, array $order_total): int {
		$code = '';

		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) {
			$code = substr($order_total['title'], $start, $end - $start);
		}

		if ($code) {
			$status = true;

			$coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE `code` = '" . $this->db->escape($code) . "' AND `status` = '1'");

			if ($coupon_query->num_rows) {
				$this->load->model('marketing/coupon');

				$coupon_total = $this->model_marketing_coupon->getTotalHistoriesByCoupon($code);

				if ($coupon_query->row['uses_total'] > 0 && ($coupon_total >= $coupon_query->row['uses_total'])) {
					$status = false;
				}

				if ($order_info['customer_id']) {
					$customer_total = $this->model_marketing_coupon->getTotalHistoriesByCustomerId($code, $order_info['customer_id']);

					if ($coupon_query->row['uses_customer'] > 0 && ($customer_total >= $coupon_query->row['uses_customer'])) {
						$status = false;
					}
				}
			} else {
				$status = false;
			}

			if ($status) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET `coupon_id` = '" . (int)$coupon_query->row['coupon_id'] . "', `order_id` = '" . (int)$order_info['order_id'] . "', `customer_id` = '" . (int)$order_info['customer_id'] . "', `amount` = '" . (float)$order_total['value'] . "', `date_added` = NOW()");
			} else {
				return $this->config->get('config_fraud_status_id');
			}
		}

		return 0;
	}

	/**
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function unconfirm(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_history` WHERE `order_id` = '" . (int)$order_id . "'");
	}
}
