<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Credit
 *
 * Can be called from $this->load->model('extension/opencart/total/credit');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Credit extends \Opencart\System\Engine\Model {
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
		$this->load->language('extension/opencart/total/credit');

		$balance = $this->customer->getBalance();

		if ((float)$balance) {
			$credit = min($balance, $total);

			if ((float)$credit > 0) {
				$totals[] = [
					'extension'  => 'opencart',
					'code'       => 'credit',
					'title'      => $this->language->get('text_credit'),
					'value'      => -$credit,
					'sort_order' => (int)$this->config->get('total_credit_sort_order')
				];

				$total -= $credit;
			}
		}
	}

	/**
	 * Confirm
	 *
	 * @param array<string, mixed> $order_info
	 * @param array<string, mixed> $order_total
	 *
	 * @return void
	 */
	public function confirm(array $order_info, array $order_total): void {
		$this->load->language('extension/opencart/total/credit');

		if ($order_info['customer_id']) {
			// Transaction
			$this->load->model('account/transaction');

			$this->model_account_transaction->addTransaction($order_info['customer_id'], $order_info['order_id'], sprintf($this->language->get('text_order_id'), (int)$order_info['order_id']), (float)$order_total['value']);
		}
	}

	/**
	 * Unconfirm
	 *
	 * @param array<string, mixed> $order_info
	 *
	 * @return void
	 */
	public function unconfirm(array $order_info): void {
		// Transaction
		$this->load->model('account/transaction');

		$this->model_account_transaction->deleteTransactionByOrderId($order_info['order_id']);
	}
}
