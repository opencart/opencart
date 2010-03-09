<?php
class ModelLocalisationCurrency extends Model {
	public function getCurrencies() {
		$currency_data = $this->cache->get('currency');

		if (!$currency_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");
	
			foreach ($query->rows as $result) {
      			$currency_data[$result['code']] = array(
        			'currency_id'   => $result['currency_id'],
        			'title'         => $result['title'],
        			'code'          => $result['code'],
					'symbol_left'   => $result['symbol_left'],
					'symbol_right'  => $result['symbol_right'],
					'decimal_place' => $result['decimal_place'],
					'value'         => $result['value'],
					'status'        => $result['status'],
					'date_modified' => $result['date_modified']
      			);
    		}	
			
			$this->cache->set('currency', $currency_data);
		}
			
		return $currency_data;	
	}	

}
?>