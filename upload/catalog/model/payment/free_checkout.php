<?php 
class ModelPaymentFreeCheckout extends Model {
  	public function getMethod($address) {
		$this->load->language('payment/free_checkout');
		
		$method_data = array();
		
		// Get All taxes, shipping fees, and order totals to be sure the price is still 0.00
		$total = 0;
		$taxes = $this->cart->getTaxes();
		 
		$this->load->model('setting/extension');
		
		$sort_order = array(); 
		
		$results = $this->model_setting_extension->getExtensions('total');
		
		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}
		
		array_multisort($sort_order, SORT_ASC, $results);
		
		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('total/' . $result['code']);

				$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
			}
		}
		
		$status = false;
		
		if ($total <= 0) {
			$status = true;
		}
			
		if ($status) {  
			$method_data = array( 
				'code'       => 'free_checkout',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('free_checkout_sort_order')
			);
		}
		
    	return $method_data;
  	}
}
?>