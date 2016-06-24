<?php
class ModelExtensionTotalKlarnaFee extends Model {
	public function getTotal($totals) {
		extract($totals);
		
		$this->load->language('extension/total/klarna_fee');

		$status = true;

		$klarna_fee = $this->config->get('klarna_fee');

		if (isset($this->session->data['payment_address_id'])) {
			$this->load->model('account/address');

			$address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} elseif (isset($this->session->data['guest']['payment'])) {
			$address = $this->session->data['guest']['payment'];
		}

		if (!isset($address)) {
			$status = false;
		} elseif (!isset($this->session->data['payment_method']['code']) || $this->session->data['payment_method']['code'] != 'klarna_invoice') {
			$status = false;
		} elseif (!isset($klarna_fee[$address['iso_code_3']])) {
			$status = false;
		} elseif (!$klarna_fee[$address['iso_code_3']]['status']) {
			$status = false;
		} elseif ($this->cart->getSubTotal() >= $klarna_fee[$address['iso_code_3']]['total']) {
			$status = false;
		}

		if ($status) {
			$total['totals'][] = array(
				'code'       => 'klarna_fee',
				'title'      => $this->language->get('text_klarna_fee'),
				'value'      => $klarna_fee[$address['iso_code_3']]['fee'],
				'sort_order' => $klarna_fee[$address['iso_code_3']]['sort_order']
			);

			$tax_rates = $this->tax->getRates($klarna_fee[$address['iso_code_3']]['fee'], $klarna_fee[$address['iso_code_3']]['tax_class_id']);

			foreach ($tax_rates as $tax_rate) {
				if (!isset($total['taxes'][$tax_rate['tax_rate_id']])) {
					$total['taxes'][$tax_rate['tax_rate_id']] = $tax_rate['amount'];
				} else {
					$total['taxes'][$tax_rate['tax_rate_id']] += $tax_rate['amount'];
				}
			}

			$total['total'] += $klarna_fee[$address['iso_code_3']]['fee'];
		}
	}
}