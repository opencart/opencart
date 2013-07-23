<?php
class ModelEbayPatch extends Model{
    public function runPatch($manual = true){
        $this->load->model('ebay/openbay');
        $this->load->model('setting/setting');

        /**
         * Update the extensions table from ebay to OpenBay
         */
        $this->db->query("UPDATE `".DB_PREFIX."extension` SET `type` = 'openbay' WHERE `type` = 'ebay'");

        $settings = $this->model_setting_setting->getSetting('openbay');

        /**
         * If there are settings returned for the eBay module then it is installed.
         */
        if($settings){
            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_setting_option` (`ebay_setting_option_id` INT(11) NOT NULL AUTO_INCREMENT,`key` VARCHAR(100) NOT NULL,`last_updated` DATETIME NOT NULL,`data` TEXT NOT NULL,PRIMARY KEY (`ebay_setting_option_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_shipping_location` (`ebay_shipping_id` INT(11) NOT NULL AUTO_INCREMENT,`description` VARCHAR(100) NOT NULL,`detail_version` VARCHAR(100) NOT NULL,`shipping_location` VARCHAR(100) NOT NULL,`update_time` VARCHAR(100) NOT NULL,PRIMARY KEY (`ebay_shipping_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_shipping_location_exclude` (`ebay_shipping_exclude_id` INT(11) NOT NULL AUTO_INCREMENT,`description` VARCHAR(100) NOT NULL,`location` VARCHAR(100) NOT NULL,`region` VARCHAR(100) NOT NULL,PRIMARY KEY (`ebay_shipping_exclude_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_transaction` (`ebay_transaction_id` int(11) NOT NULL AUTO_INCREMENT,`order_id` INT(11) NOT NULL,`product_id` INT(11) NOT NULL,`sku` VARCHAR(100) NOT NULL,`txn_id` VARCHAR(100) NOT NULL,`item_id` VARCHAR(100) NOT NULL,`containing_order_id` VARCHAR(100) NOT NULL,`order_line_id` VARCHAR(100) NOT NULL,`qty` INT(11) NOT NULL,`smp_id` INT(11) NOT NULL,`created` DATETIME NOT NULL,`modified` DATETIME NOT NULL,PRIMARY KEY (`ebay_transaction_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_order` (`ebay_order_id` INT(11) NOT NULL AUTO_INCREMENT,`parent_ebay_order_id` INT(11) NOT NULL,`order_id` INT(11) NOT NULL,`smp_id` INT(11) NOT NULL,`tracking_no` VARCHAR(100) NOT NULL,`carrier_id` VARCHAR(100) NOT NULL,PRIMARY KEY (`ebay_order_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_profile` (`ebay_profile_id` INT(11) NOT NULL AUTO_INCREMENT,`name` VARCHAR(100) NOT NULL,`description` TEXT NOT NULL,`type` VARCHAR(10) NOT NULL,`default` TINYINT(1) NOT NULL,`data` TEXT NOT NULL,PRIMARY KEY (`ebay_profile_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

            $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ebay_image_import` (`id` INT(11) NOT NULL AUTO_INCREMENT,`image_original` TEXT NOT NULL,`image_new` TEXT NOT NULL,`name` TEXT NOT NULL,`product_id` INT(11) NOT NULL,`imgcount` INT(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

            $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ebay_stock_reserve` (`id` INT(11) NOT NULL AUTO_INCREMENT,`product_id` INT(11) NOT NULL,`variant_id` VARCHAR(100) NOT NULL,`item_id` VARCHAR(100) NOT NULL,`reserve` INT(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

            $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ebay_order_lock` (`smp_id` INT(11) NOT NULL,PRIMARY KEY (`smp_id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

            $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ebay_template` (`template_id` INT(11) NOT NULL AUTO_INCREMENT,`name` VARCHAR(100) NOT NULL,`html` MEDIUMTEXT NOT NULL,PRIMARY KEY (`template_id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
            
            //check profile table for default column
            $res = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."ebay_listing` LIKE 'status'");
            if($res->num_rows == 0){
                $this->db->query("ALTER TABLE `".DB_PREFIX."ebay_listing` ADD `status` SMALLINT(3) NOT NULL DEFAULT '1'");
            }
            //check profile table for default column
            $res = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."ebay_profile` LIKE 'default'");
            if($res->num_rows == 0){
                $this->db->query("ALTER TABLE `".DB_PREFIX."ebay_profile` ADD `default` TINYINT( 1 ) NOT NULL");
            }
            //check order table for tracking columns column
            $res = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."ebay_order` LIKE 'tracking_no'");
            if($res->num_rows == 0){
                $this->db->query("ALTER TABLE `".DB_PREFIX."ebay_order` ADD `tracking_no` VARCHAR(100) NOT NULL");
                $this->db->query("ALTER TABLE `".DB_PREFIX."ebay_order` ADD `carrier_id` VARCHAR(100) NOT NULL");
            }

            if(!isset($settings['openbaypro_relistitems']) || $settings['openbaypro_relistitems'] == '0')              		{ $settings['openbaypro_stock_allocate'] = 0; }
            if(!isset($settings['openbaypro_stock_allocate']) || $settings['openbaypro_stock_allocate'] == '')              { $settings['openbaypro_stock_allocate'] = 0; }
            if(!isset($settings['openbaypro_update_notify']) || $settings['openbaypro_update_notify'] == '')                { $settings['openbaypro_update_notify'] = 1; }
            if(!isset($settings['openbaypro_confirm_notify']) || $settings['openbaypro_confirm_notify'] == '')              { $settings['openbaypro_confirm_notify'] = 1; }
            if(!isset($settings['openbaypro_confirmadmin_notify']) || $settings['openbaypro_confirmadmin_notify'] == '')    { $settings['openbaypro_confirmadmin_notify'] = 1; }
            if(!isset($settings['openbaypro_created_hours']) || $settings['openbaypro_created_hours'] == '')                { $settings['openbaypro_created_hours'] = 24; }
            if(!isset($settings['openbaypro_create_date']) || $settings['openbaypro_create_date'] == '')                    { $settings['openbaypro_create_date'] = 0; }
            if(!isset($settings['openbaypro_stock_report']) || $settings['openbaypro_stock_report'] == '')                  { $settings['openbaypro_stock_report'] = 1; }
            if(!isset($settings['openbaypro_stock_report_summary']) || $settings['openbaypro_stock_report_summary'] == '')  { $settings['openbaypro_stock_report_summary'] = 0; }
            if(!isset($settings['openbaypro_time_offset']) || $settings['openbaypro_time_offset'] == '')  					{ $settings['openbaypro_time_offset'] = 0; }
            if(!isset($settings['openbay_default_addressformat']) || $settings['openbay_default_addressformat'] == '')      { $settings['openbay_default_addressformat'] = '{firstname} {lastname}
{company}
{address_1}
{address_2}
{city}
{zone}
{postcode}
{country}'; }

            //save any settings changes.
            $this->model_setting_setting->editSetting('openbay',$settings);

            $this->ebay->loadSettings();

            //run the manual upload patch
            if($manual == true){
                $this->load->model('openbay/version');
                $this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` =  '".(int)$this->model_openbay_version->getVersion()."' WHERE  `key` = 'openbay_version' AND `group` = 'openbaymanager' LIMIT 1");
            }
        }

        return true;
    } 
}