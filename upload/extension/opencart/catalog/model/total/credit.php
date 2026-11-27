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
	 * @return int
	 */
	public function confirm(array $order_info, array $order_total): int {
		$this->load->language('extension/opencart/total/credit');

		if (!$order_info['customer_id']) {
			return 0;
		}

		// Transaction
		$this->load->model('account/transaction');

		// Hold a lock on the customer's credit from the balance check to the insert, so that concurrent order confirmations can not spend the same credit twice
		$lock = DB_PREFIX . 'customer_transaction_' . (int)$order_info['customer_id'];

		$query = $this->db->query("SELECT GET_LOCK('" . $this->db->escape($lock) . "', 10) AS `lock`");

		if (empty($query->row['lock'])) {
			return (int)$this->config->get('config_fraud_status_id');
		}

		// The order total value holds the used credit as a negative amount
		$status = round($this->model_account_transaction->getTransactionTotal($order_info['customer_id']) + (float)$order_total['value'], 4) >= 0;

		if ($status) {
			$this->model_account_transaction->addTransaction($order_info['customer_id'], $order_info['order_id'], sprintf($this->language->get('text_order_id'), (int)$order_info['order_id']), (float)$order_total['value']);
		}

		$this->db->query("DO RELEASE_LOCK('" . $this->db->escape($lock) . "')");

		if (!$status) {
			return (int)$this->config->get('config_fraud_status_id');
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
		$this->load->model('account/transaction');

		$this->model_account_transaction->deleteTransactionsByOrderId($order_info['order_id']);
	}
}
