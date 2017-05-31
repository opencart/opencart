<?php
class ModelExtensionOpenBayFba extends Model {
    public function install() {
        $this->load->model('setting/event');

		$this->model_setting_event->addEvent('openbay_fba_add_order', 'catalog/model/checkout/order/addOrder/after', 'extension/openbay/fba/eventAddOrder');
		$this->model_setting_event->addEvent('openbay_fba_add_orderhistory', 'catalog/model/checkout/order/addOrderHistory/after', 'extension/openbay/fba/eventAddOrderHistory');

        $this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fba_order` (
					`order_id` INT(11) NOT NULL,
					`fba_order_fulfillment_id` INT(11) NOT NULL,
					`fba_order_fulfillment_ref` CHAR(50) NOT NULL,
					`status` CHAR(10) NOT NULL,
				    `created` DATETIME NOT NULL,
  				    KEY `fba_order_id` (`order_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

        $this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fba_order_fulfillment` (
					`fba_order_fulfillment_id` INT(11) NOT NULL AUTO_INCREMENT,
					`order_id` INT(11) NOT NULL,
				    `created` DATETIME NOT NULL,
					`request_body` TEXT NOT NULL,
					`response_body` TEXT NOT NULL,
					`response_header_code` INT(3) NOT NULL,
					`type` INT(3) NOT NULL,
					PRIMARY KEY (`fba_order_fulfillment_id`),
  				    KEY `order_id` (`order_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

        // Default settings
        $setting = array();
        $setting["openbay_fba_status"] = 0;
        $setting["openbay_fba_send_orders"] = 0;
        $setting["openbay_fba_debug_log"] = 1;
        $setting["openbay_fba_order_trigger_status"] = 15;
        $setting["openbay_fba_cancel_order_trigger_status"] = 7;
        $setting["openbay_fba_fulfill_policy"] = 'FillAllAvailable';
        $setting["openbay_fba_shipping_speed"] = 'Standard';
        $setting["openbay_fba_order_prefix"] = 'OC-';

		$this->model_setting_setting->editSetting('openbay_fba', $setting);
    }

    public function uninstall() {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'openbay_fba'");

        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('openbay_fba_add_order');
        $this->model_setting_event->deleteEventByCode('openbay_fba_add_orderhistory');
    }

    public function patch() {
        if ($this->config->get('openbay_fba_status') == 1) {

        }
    }

    public function countFbaOrders() {
        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "fba_order`");

        return (int)$query->row['total'];
    }
}
