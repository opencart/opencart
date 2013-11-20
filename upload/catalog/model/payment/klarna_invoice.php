<?php
class ModelPaymentKlarnaInvoice extends Model {
	public function getMethod($address, $total) {
		$this->language->load('payment/klarna_invoice');

		$status = true;

		$klarna_invoice = $this->config->get('klarna_invoice');

		if (!isset($klarna_invoice[$address['iso_code_3']])) {
			$status = false;
		} elseif (!$klarna_invoice[$address['iso_code_3']]['status']) {
			$status = false;
		}

		if ($status) {  
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$klarna_invoice[$address['iso_code_3']]['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if ($klarna_invoice[$address['iso_code_3']]['total'] > 0 && $klarna_invoice[$address['iso_code_3']]['total'] > $total) {
				$status = false;
			} elseif (!$klarna_invoice[$address['iso_code_3']]['geo_zone_id']) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}

			// Maps countries to currencies
			$country_to_currency = array(
				'NOR' => 'NOK',
				'SWE' => 'SEK',
				'FIN' => 'EUR',
				'DNK' => 'DKK',
				'DEU' => 'EUR',
				'NLD' => 'EUR',
			);				

			if (!isset($country_to_currency[$address['iso_code_3']]) || !$this->currency->has($country_to_currency[$address['iso_code_3']])) {
				$status = false;
			} 

			if ($address['iso_code_3'] == 'NLD' && $this->currency->has('EUR') && $this->currency->format($total, 'EUR', '', false) > 250.00) {
				$status = false;
			}
		}

		$method = array();

		if ($status) {
			$klarna_fee = $this->config->get('klarna_fee');

			if ($klarna_fee[$address['iso_code_3']]['status'] && $this->cart->getSubTotal() < $klarna_fee[$address['iso_code_3']]['total']) {
				$title = sprintf($this->language->get('text_fee'), $this->currency->format($this->tax->calculate($klarna_fee[$address['iso_code_3']]['fee'], $klarna_fee[$address['iso_code_3']]['tax_class_id']), '', ''), $klarna_invoice[$address['iso_code_3']]['merchant'], strtolower($address['iso_code_2']), $this->currency->format($this->tax->calculate($klarna_fee[$address['iso_code_3']]['fee'], $klarna_fee[$address['iso_code_3']]['tax_class_id']), $country_to_currency[$address['iso_code_3']], '', false));
			} else {
				$title = sprintf($this->language->get('text_no_fee'), $klarna_invoice[$address['iso_code_3']]['merchant'], strtolower($address['iso_code_2']));
			}

			$method = array(
				'code'       => 'klarna_invoice',
				'title'      => $title,
				'sort_order' => $klarna_invoice[$address['iso_code_3']]['sort_order']
			);
		}

		return $method;
	}
}
?>