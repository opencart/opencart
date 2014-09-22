<?php
class ModelDesignLayout extends Model {
	public function getLayout($route) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE '" . $this->db->escape($route) . "' LIKE route AND store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY route DESC LIMIT 1");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}
	
	public function getLayoutModules($layout_id, $position) {
		$layout_module_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_module lm LEFT JOIN " . DB_PREFIX . "module m ON (lm.module_id = m.module_id) WHERE lm.layout_id = '" . (int)$layout_id . "' AND lm.position = '" . $this->db->escape($position) . "' ORDER BY lm.sort_order");
		
		foreach ($query->rows as $result) {
			$layout_module_data[$result['module_id']] = $result['code'];
		}
		
		return $layout_module_data;
	}	
}