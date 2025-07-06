<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Reward
 *
 * Can be called from $this->load->model('extension/opencart/total/reward');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Reward extends \Opencart\System\Engine\Model {
	/**
	 * Get Total
	 *
	 * @param array<int, array<string, mixed>> $totals
	 * @param  array<int, float>               &$taxes
	 * @param  float                           &$total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if (isset($this->session->data['reward'])) {
			$this->load->language('extension/opencart/total/reward', 'reward');

			$points = $this->customer->getRewardPoints();

			if ($this->session->data['reward'] <= $points && $this->session->data['reward'] > 0) {
				$discount_total = 0;

				$points_total = 0;

				foreach ($this->cart->getProducts() as $product) {
					if ($product['points']) {
						$points_total += $product['points'];
					}
				}

				$points = min($points, $points_total);

				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;

					if ($product['points']) {
						$discount = $product['total'] * ($this->session->data['reward'] / $points_total);

						if ($product['tax_class_id']) {
							$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

							foreach ($tax_rates as $tax_rate) {
								if ($tax_rate['type'] == 'P') {
									$taxes[(int)$tax_rate['tax_rate_id']] -= (float)$tax_rate['amount'];
								}
							}
						}
					}

					$discount_total += $discount;
				}

				$totals[] = [
					'extension'  => 'opencart',
					'code'       => 'reward',
					'title'      => sprintf($this->language->get('reward_text_reward'), -$this->session->data['reward']),
					'value'      => -$discount_total,
					'sort_order' => (int)$this->config->get('total_reward_sort_order')
				];

				$total -= $discount_total;
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
		$this->load->language('extension/opencart/total/reward');

		$points = 0.0;

		$start = strpos($order_total['title'], '(');
		$end = strrpos($order_total['title'], ')');

		if ($start !== false && $end !== false) {
			$points = (float)substr($order_total['title'], $start + 1, $end - ($start + 1));
		}

		// Reward
		$this->load->model('account/reward');

		if ($order_info['customer_id'] && $this->model_account_reward->getRewardTotal($order_info['customer_id']) >= $points) {
			$this->model_account_reward->addReward($order_info['customer_id'], $order_info['order_id'], sprintf($this->language->get('text_order_id'), (int)$order_info['order_id']), (int)$points);
		} else {
			return $this->config->get('config_fraud_status_id');
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
		// Reward
		$this->load->model('account/reward');

		$this->model_account_reward->deleteRewardByOrderId($order_info['order_id']);
	}
}
