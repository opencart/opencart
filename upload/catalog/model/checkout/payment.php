<?php
class ModelCheckoutPayment extends Model {
	function getMethods() {
		$method_data = array();
		
		$query = $this->db->query("SELECT * FROM extension WHERE `type` = 'payment'");
		
		foreach ($query->rows as $result) {
			$this->load->model('payment/' . $result['key']);
			
			$method = $this->{'model_payment_' . $result['key']}->getMethod();
			 
			if ($method) {
				$method_data[$result['key']] = $method;
			}
		}
					 
		$sort_order = array(); 
	  
		foreach ($method_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $method_data);				

		return $method_data;
	}
}
?>