<?php
class ModelExtensionFraudMaxMind extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "maxmind` (
			  `order_id` int(11) NOT NULL,
			  `customer_id` int(11) NOT NULL,
			  `country_match` varchar(3) NOT NULL,
			  `country_code` varchar(2) NOT NULL,
			  `high_risk_country` varchar(3) NOT NULL,
			  `distance` int(11) NOT NULL,
			  `ip_region` varchar(255) NOT NULL,
			  `ip_city` varchar(255) NOT NULL,
			  `ip_latitude` decimal(10,6) NOT NULL,
			  `ip_longitude` decimal(10,6) NOT NULL,
			  `ip_isp` varchar(255) NOT NULL,
			  `ip_org` varchar(255) NOT NULL,
			  `ip_asnum` int(11) NOT NULL,
			  `ip_user_type` varchar(255) NOT NULL,
			  `ip_country_confidence` varchar(3) NOT NULL,
			  `ip_region_confidence` varchar(3) NOT NULL,
			  `ip_city_confidence` varchar(3) NOT NULL,
			  `ip_postal_confidence` varchar(3) NOT NULL,
			  `ip_postal_code` varchar(10) NOT NULL,
			  `ip_accuracy_radius` int(11) NOT NULL,
			  `ip_net_speed_cell` varchar(255) NOT NULL,
			  `ip_metro_code` int(3) NOT NULL,
			  `ip_area_code` int(3) NOT NULL,
			  `ip_time_zone` varchar(255) NOT NULL,
			  `ip_region_name` varchar(255) NOT NULL,
			  `ip_domain` varchar(255) NOT NULL,
			  `ip_country_name` varchar(255) NOT NULL,
			  `ip_continent_code` varchar(2) NOT NULL,
			  `ip_corporate_proxy` varchar(3) NOT NULL,
			  `anonymous_proxy` varchar(3) NOT NULL,
			  `proxy_score` int(3) NOT NULL,
			  `is_trans_proxy` varchar(3) NOT NULL,
			  `free_mail` varchar(3) NOT NULL,
			  `carder_email` varchar(3) NOT NULL,
			  `high_risk_username` varchar(3) NOT NULL,
			  `high_risk_password` varchar(3) NOT NULL,
			  `bin_match` varchar(10) NOT NULL,
			  `bin_country` varchar(2) NOT NULL,
			  `bin_name_match` varchar(3) NOT NULL,
			  `bin_name` varchar(255) NOT NULL,
			  `bin_phone_match` varchar(3) NOT NULL,
			  `bin_phone` varchar(32) NOT NULL,
			  `customer_phone_in_billing_location` varchar(8) NOT NULL,
			  `ship_forward` varchar(3) NOT NULL,
			  `city_postal_match` varchar(3) NOT NULL,
			  `ship_city_postal_match` varchar(3) NOT NULL,
			  `score` decimal(10,5) NOT NULL,
			  `explanation` text NOT NULL,
			  `risk_score` decimal(10,5) NOT NULL,
			  `queries_remaining` int(11) NOT NULL,
			  `maxmind_id` varchar(8) NOT NULL,
			  `error` text NOT NULL,
			  `date_added` datetime NOT NULL,
			  PRIMARY KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");		
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "maxmind`");
	}
	
	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "maxmind` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}	
}