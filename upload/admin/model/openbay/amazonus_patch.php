<?php
/**
 * Created by James Allsup
 */
class ModelOpenbayAmazonusPatch extends Model
{ 
    public function runPatch($manual = true){
        /*
         * Manual flag to true is set when the user runs the patch method manually
         * false is when the module is updated using the update system
         */
        $this->load->model('setting/setting');

        $settings = $this->model_setting_setting->getSetting('openbay_amazonus');
        
        if($settings) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazonus_product_search` (
                    `product_id` int(11) NOT NULL,
                    `status` enum('searching','finished') NOT NULL,
                    `matches` int(11) DEFAULT NULL,
                    `data` text,
                    PRIMARY KEY (`product_id`)
                ) DEFAULT COLLATE=utf8_general_ci;");
            
            $this->db->query("
                CREATE TABLE IF NOT EXISTS`" . DB_PREFIX . "amazonus_listing_report` (
                    `sku` varchar(255) NOT NULL,
                    `quantity` int(10) unsigned NOT NULL,
                    `asin` varchar(255) NOT NULL,
                    `price` decimal(10,4) NOT NULL,
                    PRIMARY KEY (`sku`)
                ) DEFAULT COLLATE=utf8_general_ci;
            ");
        }
        
        /*
         * Always return true
         */
        return true;
    } 
}