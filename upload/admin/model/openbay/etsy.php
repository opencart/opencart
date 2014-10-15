<?php
class ModelOpenbayEtsy extends Model{
	public function install() {
		$settings                 = array();
		$settings["etsy_token"]   = '';
		$settings["etsy_secret"]  = '';
		$settings["etsy_string1"] = '';
		$settings["etsy_string2"] = '';

		$this->model_setting_setting->editSetting('etsy', $settings);

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "etsy_setting_option` (
					`etsy_setting_option_id` INT(11) NOT NULL AUTO_INCREMENT,
					`key` VARCHAR(100) NOT NULL,
					`last_updated` DATETIME NOT NULL,
					`data` TEXT NOT NULL,
					PRIMARY KEY (`etsy_setting_option_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "etsy_listing` (
				  `etsy_listing_id` int(11) NOT NULL AUTO_INCREMENT,
				  `etsy_item_id` char(100) NOT NULL,
				  `product_id` int(11) NOT NULL,
				  `status` SMALLINT(3) NOT NULL DEFAULT '1',
				  `created` DATETIME NOT NULL,
				  PRIMARY KEY (`etsy_listing_id`),
  				  KEY `product_id` (`product_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "etsy_order` (
				  `etsy_order_id` int(11) NOT NULL AUTO_INCREMENT,
				  `order_id` int(11) NOT NULL,
				  `receipt_id` int(11) NOT NULL,
				  `paid` int(1) NOT NULL,
				  `shipped` int(1) NOT NULL,
				  PRIMARY KEY (`etsy_order_id`),
  				  KEY `order_id` (`order_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "etsy_order_lock` (
				  `order_id` int(11) NOT NULL,
				  PRIMARY KEY (`order_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

		// register the event triggers
		$this->model_tool_event->addEvent('openbaypro_etsy', 'post.order.add', 'openbay/etsy/eventAddOrder');
	}

	public function uninstall() {
		// remove the event triggers
		$this->model_tool_event->deleteEvent('openbaypro_etsy');
	}

	public function verifyAccount() {
		if ($this->openbay->etsy->validate() == true) {
			return $this->openbay->etsy->call('account/info', 'GET');
		} else {
			return false;
		}
	}
}