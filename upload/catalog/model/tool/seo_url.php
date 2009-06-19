<?php
class ModelToolSeoUrl extends Model {
	public function rewrite($link) {
		if ($this->config->get('config_seo_url')) {
			$url_data = parse_url($link);
		
			$data = array();
		
			parse_str(html_entity_decode($url_data['query']), $data);
		
			$url = 'route=' . $data['route'];
			
			unset($data['route']);
			
			if (isset($data['path'])) {
				$url .= '&path=' . $data['path'];
				
				unset($data['path']);
			}
			
			if (isset($data['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $data['manufacturer_id'];
				
				unset($data['manufacturer_id']);
			}
			
			if (isset($data['product_id'])) {
				$url .= '&product_id=' . $data['product_id'];
				
				unset($data['product_id']);
			}	
			
			if (isset($data['information_id'])) {
				$url .= '&information_id=' . $data['information_id'];
				
				unset($data['information_id']);
			}			
			
			$seo_url_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($url) . "'");
				
			if ($seo_url_query->num_rows) {
				$query = '';
			
				if ($data) {
					$query = '?' . str_replace('&', '&amp;', http_build_query($data));
				}
			
				return $url_data['scheme'] . '://' . $url_data['host'] . '/' . trim($seo_url_query->row['alias'], '/') . $query;
			} else {
				return $link;
			}
		} else {
			return $link;
		}		
	}
}
?>