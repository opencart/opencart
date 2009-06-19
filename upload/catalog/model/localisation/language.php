<?php
class ModelLocalisationLanguage extends Model {
	public function getLanguages() {
		$language_data = $this->cache->get('language');
		
		if (!$language_data) {		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");
		
    		foreach ($query->rows as $result) {
      			$language_data[$result['language_id']] = array(
        			'language_id' => $result['language_id'],
        			'name'        => $result['name'],
        			'code'        => $result['code'],
					'locale'      => $result['locale'],
					'image'       => $result['image'],
					'directory'   => $result['directory'],
					'filename'    => $result['filename'],
					'sort_order'  => $result['sort_order'],
					'status'      => $result['status']
      			);
    		}	
			
			$this->cache->set('language', $language_data);
		}
		
		return $language_data;	
	}
}
?>