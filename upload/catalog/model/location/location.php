<?php
class ModelLocationLocation extends Model {
    public function getLocation($location_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");
        
        return $query->row; 
    }

    public function getLocations() {        
            
            $LocationList   =   array();
            
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "location ORDER BY sort_order, name");

            foreach($query->rows as $result)    
                $LocationList[$result['name']]   =    array(
                    'location_id' => $result['location_id'],
                    'name'        => $result['name'],
                    'address_1'   => $result['address_1'],
                    'address_2'   => $result['address_2'],
                    'city'        => $result['city'],
                    'postcode'    => $result['postcode'],
                    'sort_order'  => $result['sort_order'],
                    'geocode'     => $result['geocode'],
                    'times'       => $result['times'],
                    'comment'     => $result['comment'],
                    'status'      => $result['status']
                );
    
            return $LocationList;
    }
}
?>