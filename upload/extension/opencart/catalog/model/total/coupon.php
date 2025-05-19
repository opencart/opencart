<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Coupon
 *
 * Can be called from $this->load->model('extension/opencart/total/coupon');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Coupon extends \Opencart\System\Engine\Model {
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
		// Coupon
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
						if ($coupon_info['type'] == 'F' && $sub_total) {
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
			// Coupon
			$this->load->model('marketing/coupon');

			$status = true;

			$coupon_info = $this->model_marketing_coupon->getCouponByCode($code);

			if ($coupon_info) {
				// Total Coupons
				$coupon_total = $this->model_marketing_coupon->getTotalHistories($coupon_info['coupon_id']);

				if ($coupon_info['uses_total'] > 0 && ($coupon_total >= $coupon_info['uses_total'])) {
					$status = false;
				}

				if ($order_info['customer_id']) {
					// Total Customers
					$customer_total = $this->model_marketing_coupon->getTotalHistoriesByCustomerId($coupon_info['coupon_id'], $order_info['customer_id']);

					if ($coupon_info['uses_customer'] > 0 && ($customer_total >= $coupon_info['uses_customer'])) {
						$status = false;
					}
				}
			} else {
				$status = false;
			}

			if ($status) {
				$this->model_marketing_coupon->addHistory($coupon_info['coupon_id'], $order_info['order_id'], $order_info['customer_id'], $order_total['value']);
			} else {
				return $this->config->get('config_fraud_status_id');
			}
		}

		return 0;
	}

	/**
	 * Unconfirm
	 *
	 * @param array<string, mixed> $order_info
	 *
	 * @return void
	 */
	public function unconfirm(array $order_info): void {
		// Histories
		$this->load->model('marketing/coupon');

		$this->model_marketing_coupon->deleteHistoriesByOrderId($order_info['order_id']);
	}
}
