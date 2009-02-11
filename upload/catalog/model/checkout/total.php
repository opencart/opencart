<?php
class ModelCheckoutTotal extends Model {
  	public function getTotals() {
		$total_data = array();
		
		$query = $this->db->query("SELECT * FROM extension WHERE `type` = 'total'");
		
		foreach ($query->rows as $result) {
			$this->load->model('total/' . $result['key']);
						
			$results = $this->{'model_total_' . $result['key']}->getTotal();
			
			foreach ($results as $result) {
	    		$total_data[] = array(
		  			'title'        => $result['title'],
		  			'text'         => $result['text'],
		  			'value'        => $result['value'],
					'tax_class_id' => @$result['tax_class_id'],
					'sort_order'   => $result['sort_order']
				);			
			}
		}

		$sort_order = array(); 
	  
		foreach ($total_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

		array_multisort($sort_order, SORT_ASC, $total_data);

		return $total_data;
  	}
}
?>