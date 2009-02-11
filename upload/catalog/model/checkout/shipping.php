<?php
class ModelCheckoutShipping extends Model {
	function getQuotes() {
		$quote_data = array();
		
		$query = $this->db->query("SELECT * FROM extension WHERE `type` = 'shipping'");
		
		foreach ($query->rows as $result) {
			$this->load->model('shipping/' . $result['key']);
			
			$quote = $this->{'model_shipping_' . $result['key']}->getQuote();

			if ($quote) {
				$quote_data[$result['key']] = array(
					'title'      => $quote['title'],
					'quote'      => $quote['quote'], 
					'sort_order' => $quote['sort_order'],
					'error'      => $quote['error']
				);
			}
		}

		$sort_order = array();
	  
		foreach ($quote_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $quote_data);
						
		return $quote_data;
	}
}
?>