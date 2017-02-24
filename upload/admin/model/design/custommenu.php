<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelDesignCustomMenu extends Model {

    public function getcustommenus() {
        $data = array();

        $sql = "SELECT * FROM `" . DB_PREFIX . "custommenu` m LEFT JOIN " . DB_PREFIX . "custommenu_description md ON (m.custommenu_id = md.custommenu_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY m.sort_order";

        $query = $this->db->query($sql);

        if ($query->rows) {
            foreach ($query->rows as $custommenu) {
                $data[$custommenu['custommenu_id']] = $custommenu;
            }
        }

        return $data;
    }

    public function getChildcustommenus() {
        $data = array();

        $sql = "SELECT * FROM `" . DB_PREFIX . "custommenu_child` mc LEFT JOIN " . DB_PREFIX . "custommenu_child_description mcd ON (mc.custommenu_child_id = mcd.custommenu_child_id) WHERE mcd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY mc.sort_order";

        $query = $this->db->query($sql);

        if ($query->rows) {
            foreach ($query->rows as $custommenu_child) {
                $data[$custommenu_child['custommenu_child_id']] = $custommenu_child;
            }
        }

        return $data;
    }

    public function getcustommenuStores($custommenu_id){
        $custommenu_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custommenu_to_store WHERE custommenu_id = '" . (int)$custommenu_id . "'");

        foreach ($query->rows as $result) {
            $custommenu_store_data[] = $result['store_id'];
        }

        return $custommenu_store_data;

    }

    public function getChildcustommenuStores($custommenu_child_id){

        $custommenu_child_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custommenu_child_to_store WHERE custommenu_child_id = '" . (int)$custommenu_child_id . "'");

        foreach ($query->rows as $result) {
            $custommenu_child_store_data[] = $result['store_id'];
        }

        return $custommenu_child_store_data;
    }

    public function add($data, $languages){
        $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu SET  sort_order= '1', columns = '1', custommenu_type = '" . $this->db->escape($data['type']) . "', status = '1'");

        $custommenu_id = $this->db->getLastId();

        if ($data['type'] == 'custom') {
            $link = $data['link'];

            $data['custommenu_desc'] = array();            
			
			foreach ($languages as $language) {                
				$data['custommenu_desc'][] = array('name' => $data['name'], 'language_id' => $language['language_id']);            
			}
        }
        else {
            $link = (int)$data['id'];

            if ($data['type'] == 'information') {
                $fields = 'title AS name, '.$data['type'].'_id, language_id';
            } else {
                $fields = 'name, '.$data['type'].'_id, language_id';
            }
		
			if ($data['type'] != 'manufacturer') {
                  $query = $this->db->query("SELECT " . $fields . " FROM " . DB_PREFIX . $data['type'] . "_description WHERE ". $data['type'] ."_id = '" . (int)$data['id'] . "'");
				  $data['custommenu_desc'] = $query->rows;
            } else {
				 $fields = 'name, '.$data['type'].'_id ';
				 $query = $this->db->query("SELECT " . $fields . " FROM " . DB_PREFIX . $data['type'] . " WHERE ". $data['type'] ."_id = '" . (int)$data['id'] . "'");
				 $result  = array();
				 foreach ($languages as $language) {
					$result[] = array('name' => $query->row['name'], 'manufacturer_id' => $query->row['manufacturer_id'], 'language_id' => $language['language_id']);
				};
				$data['custommenu_desc'] =  $result;
				 
			}
        }

      

        foreach ($data['custommenu_desc'] as $desc) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_description SET custommenu_id = '" . (int)$custommenu_id . "', language_id = '" . (int)$desc['language_id'] . "', name = '" . $this->db->escape($desc['name']) . "', link = '" . $link . "'");
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_to_store SET custommenu_id = '" . (int)$custommenu_id . "', store_id = '0'");

        $custommenu = array(
            'name' =>$data['custommenu_desc'][0]['name'],
            'custommenu_type' =>$data['type'],
            'custommenu_id' => $custommenu_id
        );

        return $custommenu;
    }

    public function save($data){
        foreach($data['custommenu-item-typecustommenu'] as $key => $value){
            $_custommenu_id = explode('-', $key);
        }
        $custommenu_id = $_custommenu_id[1];

        $this->db->query("UPDATE `" . DB_PREFIX . "custommenu` SET columns = '" . (int)$data['custommenu_columns'] . "' WHERE custommenu_id = '" . (int)$custommenu_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "custommenu_description WHERE custommenu_id = '" . (int)$custommenu_id . "'");

        foreach($data['custommenu_name'] as $language_id => $value){
            $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_description SET custommenu_id = '" . (int)$custommenu_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value) . "', link = '" . $this->db->escape($data['custommenu_link'][$language_id]) . "'");
        }

        if(!empty($data['custommenu_store'] )){
            $this->db->query("DELETE FROM " . DB_PREFIX . "custommenu_to_store WHERE custommenu_id = '" . (int)$custommenu_id . "'");

            foreach($data['custommenu_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_to_store SET custommenu_id = '" . (int)$custommenu_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
    }

    public function saveChild($data){
        foreach($data['custommenu-item-typecustommenu'] as $key => $value){
            $_custommenu_id = explode('-', $key);
        }
        $custommenu_child_id = $_custommenu_id[1];

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custommenu_child WHERE custommenu_child_id = '" . (int)$custommenu_child_id . "'");

        $custommenu_id = $query->row['custommenu_id'];

        $this->db->query("DELETE FROM " . DB_PREFIX . "custommenu_child_description WHERE custommenu_child_id = '" . (int)$custommenu_child_id . "'");

        foreach($data['custommenu_child_name'] as $language_id => $value){
            $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_child_description SET custommenu_id = '" . (int)$custommenu_id . "', custommenu_child_id = '" . (int)$custommenu_child_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value) . "', link = '" . $this->db->escape($data['custommenu_child_link'][$language_id]) . "'");
        }

        if(!empty($data['custommenu_store'] )) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "custommenu_child_to_store WHERE custommenu_child_id = '" . (int)$custommenu_child_id . "'");

            foreach ($data['custommenu_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_child_to_store SET custommenu_child_id = '" . (int)$custommenu_child_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
    }

    public function deletecustommenu($custommenu_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu` WHERE custommenu_id = '" . (int)$custommenu_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_description` WHERE custommenu_id = '" . (int)$custommenu_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_to_store` WHERE custommenu_id = '" . (int)$custommenu_id . "'");
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custommenu_child WHERE custommenu_id = '" . (int)$custommenu_id . "'");
		
        $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_child` WHERE custommenu_id = '" . (int)$custommenu_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_child_description` WHERE custommenu_id = '" . (int)$custommenu_id . "'");
		
		if(!empty($query->num_rows)){
			$this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_child_to_store` WHERE custommenu_child_id = '" . (int)$query->row['custommenu_child_id'] . "'");
		}
    }

    public function deleteChildcustommenu($custommenu_child_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_child` WHERE custommenu_child_id = '" . (int)$custommenu_child_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_child_description` WHERE custommenu_child_id = '" . (int)$custommenu_child_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_child_to_store` WHERE custommenu_child_id = '" . (int)$custommenu_child_id . "'");
    }

    public function getcustommenuDesc() {
        $data = array();

        $link = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custommenu_description AS md LEFT JOIN " . DB_PREFIX . "custommenu AS m  ON m.custommenu_id = md.custommenu_id ");

        foreach ($query->rows as $result) {
            // Quick fix for multilanguage
            // Check if link exists to set for later usage
            if (!empty($result['link'])) {
                $link[$result['custommenu_id']] = $result['link'];
            }

            // Try to get the link from previous set if not already exists
            if (empty($result['link']) and !empty($link[$result['custommenu_id']])) {
                $result['link'] = $link[$result['custommenu_id']];
            }

            $data[$result['custommenu_id']][$result['language_id']] = $result;
        }

        return $data;
    }

    public function getcustommenuChildDesc() {
        $data = array();

        $link = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custommenu_child_description AS md LEFT JOIN " . DB_PREFIX . "custommenu_child AS m  ON m.custommenu_child_id = md.custommenu_child_id ");

        foreach ($query->rows as $result) {
            // Quick fix for multilanguage
            // Check if link exists to set for later usage
            if (!empty($result['link'])) {
                $link[$result['custommenu_child_id']] = $result['link'];
            }

            // Try to get the link from previous set if not already exists
            if (empty($result['link']) and !empty($link[$result['custommenu_child_id']])) {
                $result['link'] = $link[$result['custommenu_child_id']];
            }

            $data[$result['custommenu_child_id']][$result['language_id']] = $result;
        }

        return $data;
    }

	public function enablecustommenu($custommenu_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "custommenu` SET status = '1' WHERE custommenu_id = '" . (int)$custommenu_id . "'");
    }

	public function enableChildcustommenu($custommenu_child_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "custommenu_child` SET status = '1' WHERE custommenu_child_id = '" . (int)$custommenu_child_id . "'");
    }

	public function disablecustommenu($custommenu_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "custommenu` SET status = '0' WHERE custommenu_id = '" . (int)$custommenu_id . "'");
    }

	public function disableChildcustommenu($custommenu_child_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "custommenu_child` SET status = '0' WHERE custommenu_child_id = '" . (int)$custommenu_child_id . "'");
    }
	
	public function changecustommenuPosition($data){
        $custommenuOrder = 1;
        $custommenuSubOrder = 1;

        foreach($data['custommenu-item-db-id'] as $key => $value){
            $custommenuType = explode('-', $key);
			
            $subcustommenu = 0;

            if(($custommenuType[0] == 'Childcustommenu') && $data['custommenu-item-parent-id'][$key] == 0){
                $insertData = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custommenu_child` WHERE custommenu_child_id = '" . $custommenuType[1] . "'");
                $insertDataDesc = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custommenu_child_description` WHERE custommenu_child_id = '" . $custommenuType[1] . "'");

                $this->db->query("INSERT INTO `" . DB_PREFIX . "custommenu` SET sort_order = '" . (int)$insertData->row['sort_order'] . "', columns = '1', custommenu_type = '" . $insertData->row['custommenu_type'] . "', status = '1'");

                $custommenu_id = $this->db->getLastId();

                foreach($insertDataDesc->rows as $dataDesc) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_description SET custommenu_id = '" . (int)$custommenu_id . "', language_id = '" . (int)$dataDesc['language_id'] . "', name = '" . $this->db->escape($dataDesc['name']) . "', link = '" . $this->db->escape($dataDesc['link']) . "'");
                }

                $childStore = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custommenu_child_to_store` WHERE custommenu_child_id = '" . $custommenuType[1] . "'");
                if(!empty($childStore->num_rows)){
                    foreach($childStore->rows as $storeData) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_to_store SET custommenu_id = '" . (int)$custommenu_id . "', store_id = '" . $storeData['store_id'] . "'");
                    }
                }

                $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_child` WHERE custommenu_child_id = '" . $custommenuType[1] . "'");
                $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_child_description` WHERE custommenu_child_id = '" . $custommenuType[1] . "'");
                $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_child_to_store` WHERE custommenu_child_id = '" . $custommenuType[1] . "'");

                $custommenuType[0] = 'Maincustommenu';
                $custommenuType[1] = $custommenu_id;
            }

            if(($custommenuType[0] == 'Maincustommenu') && $data['custommenu-item-parent-id'][$key] <> 0){
                $insertData = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custommenu` WHERE custommenu_id = '" . $custommenuType[1] . "'");
                $insertDataDesc = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custommenu_description` WHERE custommenu_id = '" . $custommenuType[1] . "'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_child SET custommenu_id = '" . $data['custommenu-item-parent-id'][$key] ."', sort_order = '" . (int)$insertData->row['sort_order'] . "', custommenu_type = '" . $insertData->row['custommenu_type'] . "', status = '1'");

                $custommenu_child_id = $this->db->getLastId();

                foreach($insertDataDesc->rows as $dataDesc) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_child_description SET custommenu_id = '" . $data['custommenu-item-parent-id'][$key] . "', custommenu_child_id = '" . (int)$custommenu_child_id . "', language_id = '" . (int)$dataDesc['language_id'] . "', name = '" . $this->db->escape($dataDesc['name']) . "', link = '" . $this->db->escape($dataDesc['link']) . "'");
                }

                $mainStore = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custommenu_to_store` WHERE custommenu_id = '" . $custommenuType[1] . "'");
                if(!empty($mainStore->num_rows)){
                    foreach($mainStore->rows as $storeData) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "custommenu_child_to_store SET custommenu_child_id = '" . (int)$custommenu_child_id . "', store_id = '" . $storeData['store_id'] . "'");
                    }
                }

                $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu` WHERE custommenu_id = '" . $custommenuType[1] . "'");
                $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_description` WHERE custommenu_id = '" . $custommenuType[1] . "'");
                $this->db->query("DELETE FROM `" . DB_PREFIX . "custommenu_to_store` WHERE custommenu_id = '" . $custommenuType[1] . "'");

                $custommenuType[1] = $custommenu_child_id;

                $subcustommenu = '1';
            }

            if($custommenuType[0] == 'Maincustommenu' && empty($subcustommenu)) {
                $this->db->query("UPDATE `" . DB_PREFIX . "custommenu` SET sort_order = '" . (int)$custommenuOrder . "' WHERE custommenu_id = '" . $custommenuType[1] . "'");
                $custommenuOrder++;
                $custommenuSubOrder = 1;
            } else {
                $this->db->query("UPDATE `" . DB_PREFIX . "custommenu_child` SET sort_order = '" . (int)$custommenuSubOrder . "', custommenu_id = '" . $data['custommenu-item-parent-id'][$key] . "' WHERE custommenu_child_id = '" . $custommenuType[1] . "'");
                $this->db->query("UPDATE `" . DB_PREFIX . "custommenu_child_description` SET  custommenu_id = '" . $data['custommenu-item-parent-id'][$key] . "' WHERE custommenu_child_id = '" . $custommenuType[1] . "'");
                $custommenuSubOrder++;
            }
        }
	}
}