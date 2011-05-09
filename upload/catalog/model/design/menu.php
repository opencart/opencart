<?php
class ModelDesignMenu extends Model {	
	public function getMenus() {
		$menu_data = array();
		
		$menu_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY m.sort_order ASC");
		
		foreach ($menu_query->rows as $menu) {
			$menu_link_data = array();
		
			$menu_link_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_link ml LEFT JOIN " . DB_PREFIX . "menu_link_description mld ON (ml.menu_link_id = mld.menu_link_id) WHERE ml.menu_id = '" . (int)$menu['menu_id'] . "' AND mld.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ml.sort_order ASC");
			
			foreach ($menu_link_query->rows as $menu_link) {
				$menu_link_data[] = array(
					'name'       => $menu_link['name'],
					'heading'    => $menu_link['heading'],
					'link'       => $menu_link['link'],
					'sort_order' => $menu_link['sort_order']	
				);
			}
			
			$menu_data[] = array(
				'name'       => $menu['name'],
				'link'       => $menu['link'],
				'column'     => $menu['column'],
				'sort_order' => $menu['sort_order'],
				'menu_link'  => $menu_link_data		
			);
		}
		
		return $menu_data;
	}
}
?>