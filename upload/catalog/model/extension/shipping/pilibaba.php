<?php
class ModelExtensionShippingPilibaba extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/pilibaba');

		$status = true;

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['pilibaba'] = array(
				'code'         => 'pilibaba.pilibaba',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('payment_pilibaba_shipping_fee'),
				'tax_class_id' => 0,
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('payment_pilibaba_shipping_fee'), 0, $this->config->get('config_tax')), $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'pilibaba',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => 1,
				'error'      => false
			);
		}

		return $method_data;
	}
}
