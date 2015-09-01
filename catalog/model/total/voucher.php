<?php
class ModelTotalVoucher extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['voucher'])) {
			$this->load->language('total/voucher');

			$this->load->model('checkout/voucher');

			$voucher_info = $this->model_checkout_voucher->getVoucher($this->session->data['voucher']);

			if ($voucher_info) {
				if ($voucher_info['amount'] > $total) {
					$amount = $total;
				} else {
					$amount = $voucher_info['amount'];
				}

				$total_data[] = array(
					'code'       => 'voucher',
					'title'      => sprintf($this->language->get('text_voucher'), $this->session->data['voucher']),
					'value'      => -$amount,
					'sort_order' => $this->config->get('voucher_sort_order')
				);

				$total -= $amount;
			}
		}
	}

	public function confirm($order_info, $order_total) {
		$code = '';

		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) {
			$code = substr($order_total['title'], $start, $end - $start);
		}

		$this->load->model('checkout/voucher');

		$voucher_info = $this->model_checkout_voucher->getVoucher($code);

		if ($voucher_info) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_history` SET voucher_id = '" . (int)$voucher_info['voucher_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', amount = '" . (float)$order_total['value'] . "', date_added = NOW()");
		}
	}

	public function unconfirm($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher_history` WHERE order_id = '" . (int)$order_id . "'");
	}
}