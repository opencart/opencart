<?php
class ModelOpenbayFba extends Model {
    public function install() {
        $this->load->model('extension/event');

        // register the event triggers
        if (version_compare(VERSION, '2.0.1', '>=')) {
            $this->load->model('extension/event');
            $this->model_extension_event->addEvent('openbay_fba', 'post.order.history.add', 'openbay/fba/eventAddOrderHistory');
        } else {
            $this->load->model('tool/event');
            $this->model_tool_event->addEvent('openbay_fba', 'post.order.history.add', 'openbay/fba/eventAddOrderHistory');
        }

        // Default settings
        $setting = array();
        $setting["openbay_fba_status"] = 0;
        $setting["openbay_fba_send_orders"] = 0;
        $setting["openbay_fba_debug_log"] = 1;
        $setting["openbay_fba_order_trigger_status"] = 15;
        $setting["openbay_fba_cancel_order_trigger_status"] = 7;
        $setting["openbay_fba_fulfill_policy"] = 'FillAllAvailable';
        $setting["openbay_fba_shipping_speed"] = 'Standard';

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
}