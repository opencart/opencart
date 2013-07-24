<?php
class ModelEbayPatch extends Model{
    public function runPatch($manual = true){
        $this->load->model('setting/setting');

        $settings = $this->model_setting_setting->getSetting('openbay');

        /**
         * If there are settings returned for the eBay module then it is installed.
         */
        if($settings){
            //run the manual upload patch
            if($manual == true){
                $this->load->model('openbay/version');
                $this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` =  '".(int)$this->model_openbay_version->getVersion()."' WHERE  `key` = 'openbay_version' AND `group` = 'openbaymanager' LIMIT 1");
            }
        }

        return true;
    } 
}