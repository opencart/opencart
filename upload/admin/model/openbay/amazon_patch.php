<?php
class ModelOpenbayAmazonPatch extends Model {
	public function runPatch($manual = true) {
		/*
		 * Manual flag to true is set when the user runs the patch method manually
		 * false is when the module is updated using the update system
		 */
		$this->load->model('setting/setting');

		$settings = $this->model_setting_setting->getSetting('openbay_amazon');

		if ($settings) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazon_product_search` (
					`product_id` int(11) NOT NULL,
					`marketplace` enum('uk','de','es','it','fr') NOT NULL,
					`status` enum('searching','finished') NOT NULL,
					`matches` int(11) DEFAULT NULL,
					`data` text,
					PRIMARY KEY (`product_id`,`marketplace`)
				) DEFAULT COLLATE=utf8_general_ci;");

			$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazon_listing_report` (
					`marketplace` enum('uk','de','fr','es','it') NOT NULL,
					`sku` varchar(255) NOT NULL,
					`quantity` int(10) unsigned NOT NULL,
					`asin` varchar(255) NOT NULL,
					`price` decimal(10,4) NOT NULL,
					PRIMARY KEY (`marketplace`,`sku`)
				) DEFAULT COLLATE=utf8_general_ci;");

			if (!$this->config->get('openbay_amazon_processing_listing_reports')) {
				$settings['openbay_amazon_processing_listing_reports'] = array();
			}

			$this->model_setting_setting->editSetting('openbay_amazon', $settings);
		}

		return true;
	}
}