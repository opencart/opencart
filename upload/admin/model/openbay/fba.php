<?php
class ModelOpenbayFba extends Model {
    public function install() {
        $this->load->model('extension/event');

        // register the event triggers
        if (version_compare(VERSION, '2.0.1', '>=')) {
            $this->load->model('extension/event');
            $this->model_extension_event->addEvent('openbay_fba', 'post.order.add', 'openbay/fba/eventAddOrder');
            $this->model_extension_event->addEvent('openbay_fba', 'post.order.history.add', 'openbay/fba/eventAddOrderHistory');
        } else {
            $this->load->model('tool/event');
            $this->model_tool_event->addEvent('openbay_fba', 'post.order.add', 'openbay/fba/eventAddOrder');
            $this->model_tool_event->addEvent('openbay_fba', 'post.order.history.add', 'openbay/fba/eventAddOrderHistory');
        }

        $this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fba_order` (
					`order_id` INT(11) NOT NULL,
					`status` CHAR(10) NOT NULL,
				    `created` DATETIME NOT NULL,
  				    KEY `fba_order_id` (`order_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");

        $this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fba_order_fulfillment` (
					`fba_order_fulfillment_id` INT(11) NOT NULL AUTO_INCREMENT,
					`order_id` INT(11) NOT NULL,
				    `created` DATETIME NOT NULL,
					`request_body` TEXT NOT NULL,
					`response_body` TEXT NOT NULL,
					`response_header_code` INT(3) NOT NULL,
					PRIMARY KEY (`fba_order_fulfillment_id`),
  				    KEY `order_id` (`order_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");

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

        $this->load->model('extension/event');
        $this->model_extension_event->deleteEvent('openbay_fba');
    }

    public function patch() {
        if ($this->config->get('openbay_amazon_status') == 1) {
            /*
             * Manual flag to true is set when the user runs the patch method manually
             * false is when the module is updated using the update system
             */
            $this->load->model('setting/setting');

            $settings = $this->model_setting_setting->getSetting('openbay_fba');

            if ($settings) {
                if (!$this->config->get('openbay_amazon_processing_listing_reports')) {
                    $settings['openbay_amazon_processing_listing_reports'] = array();
                }

                $this->model_setting_setting->editSetting('openbay_fba', $settings);
            }

            //remove the current events
            $this->model_extension_event->deleteEvent('openbay_fba');

            //re-add the correct events
            $this->model_extension_event->addEvent('openbay_fba', 'post.order.history.add', 'openbay/amazon/eventAddOrderHistory');

            return true;
        }
    }

    public function countFbaOrders() {
        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "fba_order`");

        return (int)$query->row['total'];
    }
}