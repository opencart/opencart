<?php
class ModelCheckoutExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}
	
	function getExtensionsByPosition($type, $position) {
		$module_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");
		
		foreach ($query->rows as $result) {
			if ($this->config->get($result['key'] . '_status') && ($this->config->get($result['key'] . '_position') == $position)) {
				$module_data[] = array(
					'code'       => $result['key'],
					'sort_order' => $this->config->get($result['key'] . '_sort_order')
				);
			}
		}
		
		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $module_data);
    	
    	return $module_data;
	}
}
?>