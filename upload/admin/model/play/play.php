<?php
class ModelPlayPlay extends Model
{   
    public function install(){

        $settings                           = array();
        $settings['obp_play_def_currency']  = 'GBP';
        $settings['obp_play_logging']       = 1;
        $settings['obp_play_import_id']     = 1;
        $settings['obp_play_paid_id']       = 2;
        $settings['obp_play_shipped_id']    = 3;
        $settings['obp_play_cancelled_id']  = 7;
        $settings['obp_play_refunded_id']   = 11;
        $settings['obp_play_default_tax']   = 0;

        $this->model_setting_setting->editSetting('play',$settings);

        //product listing table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "play_product_insert` (
                `play_product_insert_id` int(11) NOT NULL AUTO_INCREMENT,
                `play_product_id` VARCHAR(25) NOT NULL,
                `play_product_id_type` int(3) NOT NULL,
                `product_id` int(11) NOT NULL,
                `dispatch_to` int(3) NOT NULL,
                `price_gb` decimal(10,2) NOT NULL,
                `price_eu` decimal(10,2) NOT NULL,
                `condition` int(3) NOT NULL,
                `comment` TEXT NOT NULL,
                `dispatch_from` int(3) NOT NULL,
                `created` DATETIME NOT NULL,
                `submitted` DATETIME NOT NULL,
                `status` int(3) NOT NULL,
                `action` VARCHAR(2) NOT NULL,
            PRIMARY KEY (`play_product_insert_id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "play_product_insert_error` (
                `play_product_insert_error_id` int(11) NOT NULL AUTO_INCREMENT,
                `product_id` int(11) NOT NULL,
                `status_msg` TEXT NOT NULL,
            PRIMARY KEY (`play_product_insert_error_id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "play_order` (
                `order_id` int(11) NOT NULL,
                `play_order_id` int(11) NOT NULL,
                `modified` int(3) NOT NULL,
                `old_status` int(3) NOT NULL,
                `submitted` DATETIME NOT NULL,
                `paid_gbp` decimal(10,2) NOT NULL,
                `paid_euro` decimal(10,2) NOT NULL,
                `tracking_no` VARCHAR(100) NOT NULL,
                `carrier_id` int(3) NOT NULL,
                `carrier_name` VARCHAR(50) NOT NULL,
                `carrier_contact` VARCHAR(150) NOT NULL,
                `refund_message` VARCHAR(150) NOT NULL,
                `refund_reason` VARCHAR(150) NOT NULL,
            PRIMARY KEY (`order_id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
    }

    public function uninstall(){
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "play_product_insert`;");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "play_product_insert_error`;");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "play_order`;");

        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('obp_play');
    }

    public function updatePlayOrderTracking($order_id, $courier_id, $tracking){
        $order_qry = $this->db->query("UPDATE `" . DB_PREFIX . "play_order` SET `tracking_no` = '".$this->db->escape($tracking)."', `carrier_id` = '".(int)$courier_id."' WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");
    }

    public function updatePlayOrderRefund($order_id, $message, $reason){
        $order_qry = $this->db->query("UPDATE `" . DB_PREFIX . "play_order` SET `refund_message` = '".$this->db->escape($message)."', `refund_reason` = '".$this->db->escape($reason)."' WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");
    }
}