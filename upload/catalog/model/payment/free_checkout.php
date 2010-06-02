<?php 
class ModelPaymentFreeCheckout extends Model {
  	public function getMethod($country_id = '', $zone_id = '', $postcode = '') {
		$this->load->language('payment/free_checkout');
		
		$method_data = array();
		
		$status = FALSE;
		
		if ($this->config->get('free_checkout_status')) {
		
			$method_data = array();
			
			// Get All taxes, shipping fees, and order totals to be sure the price is still 0.00
			$total = 0;
			$taxes = $this->cart->getTaxes();
			 
			$this->load->model('checkout/extension');
			
			$sort_order = array(); 
			
			$results = $this->model_checkout_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				$this->load->model('total/' . $result['key']);

				$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
			}
			
			if ($total <= 0) {
				$status = TRUE;
			}
				
			if ($status) {  
				$method_data = array( 
					'id'         => 'free_checkout',
					'title'      => $this->language->get('text_title'),
					'sort_order' => $this->config->get('free_checkout_sort_order')
				);
			}
		}
    	return $method_data;
  	}
}
?>