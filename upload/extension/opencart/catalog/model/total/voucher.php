<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Voucher
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Voucher extends \Opencart\System\Engine\Model {
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
		if (isset($this->session->data['voucher'])) {
			$this->load->language('extension/opencart/total/voucher', 'voucher');

			$this->load->model('checkout/voucher');

			$voucher_info = $this->model_checkout_voucher->getVoucher($this->session->data['voucher']);

			if ($voucher_info) {
				$amount = min($voucher_info['amount'], $total);

				if ($amount > 0) {
					$totals[] = [
						'extension'  => 'opencart',
						'code'       => 'voucher',
						'title'      => sprintf($this->language->get('voucher_text_voucher'), $this->session->data['voucher']),
						'value'      => -$amount,
						'sort_order' => (int)$this->config->get('total_voucher_sort_order')
					];

					$total -= $amount;
				} else {
					unset($this->session->data['voucher']);
				}
			} else {
				unset($this->session->data['voucher']);
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
			$this->load->model('checkout/voucher');

			$voucher_info = $this->model_checkout_voucher->getVoucher($code);

			if ($voucher_info) {
				$this->model_checkout_voucher->addHistory($voucher_info['voucher_id'], $order_info['order_id'], $order_total['value']);
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
		$this->load->model('checkout/voucher');

		$this->model_checkout_voucher->deleteHistoryByOrderId($order_info['order_id']);
	}
}
