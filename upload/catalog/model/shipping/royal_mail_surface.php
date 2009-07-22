<?php
class ModelShippingRoyalMailSurface extends Model {
	function getQuote() {
		$this->load->language('shipping/royal_mail_surface');
		
		if ($this->config->get('royal_mail_surface_status')) {
			$address = $this->customer->getAddress($this->session->data['shipping_address_id']);
			
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('royal_mail_surface_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
      		if (!$this->config->get('royal_mail_surface_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}

		$method_data = array();
	
		if ($status) {
			$quote_data = array();
			
      		$quote_data['royal_mail_surface'] = array(
        		'id'           => 'royal_mail_surface.royal_mail_surface',
        		'title'        => $this->language->get('text_description'),
        		'cost'         => $this->config->get('royal_mail_surface_cost'),
        		'tax_class_id' => $this->config->get('royal_mail_surface_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('royal_mail_surface_cost'), $this->config->get('royal_mail_surface_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'id'         => 'royal_mail_surface',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('royal_mail_surface_sort_order'),
        		'error'      => FALSE
      		);
		}
	
		return $method_data;
	}
}
?>