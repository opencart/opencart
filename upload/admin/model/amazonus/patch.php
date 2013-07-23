<?php
/**
 * Created by James Allsup
 */
class ModelamazonusPatch extends Model
{ 
    public function runPatch($manual = true){
        /*
         * Manual flag to true is set when the user runs the patch method manually
         * false is when the module is updated using the update system
         */
        $this->load->model('setting/setting');

        $settings = $this->model_setting_setting->getSetting('openbay_amazonus');
        
        if($settings) {
            //Check if we have amazonus_product table without version column
            $amazonusProductVersionColumn = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "amazonus_product` WHERE `Field` = 'version'")->rows;
            if(count($amazonusProductVersionColumn) != 1) {
                $this->db->query("ALTER TABLE `" . DB_PREFIX . "amazonus_product`
                    ADD(`version` int(11) NOT NULL DEFAULT 2)");
            }
            
            //Check if we have amazonus_product table without messages column
            $amazonusProductMessages = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "amazonus_product` WHERE `Field` = 'messages'")->rows;
            if(count($amazonusProductMessages) != 1) {
                $this->db->query("ALTER TABLE `" . DB_PREFIX . "amazonus_product`
                    ADD(`messages` text NOT NULL)");
            }
            
            //Check if we have amazonus_product status column of "set" type instead of "enum"
            $productStatusSet = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "amazonus_product` WHERE `Field` = 'status' AND `Type` = '" . $this->db->escape("set('saved','uploaded','ok','error')") . "'")->row;
            if(!empty($productStatusSet)) {
                $this->db->query("ALTER TABLE `" . DB_PREFIX . "amazonus_product`
                    MODIFY `status` ENUM('saved','uploaded','ok','error')");
            }
        }
        
        /*
         * Always return true
         */
        return true;
    } 
}