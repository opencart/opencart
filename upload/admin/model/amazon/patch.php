<?php
class ModelAmazonPatch extends Model { 
    
    public function runPatch($manual = true){
        /*
         * Manual flag to true is set when the user runs the patch method manually
         * false is when the module is updated using the update system
         */
        $this->load->model('setting/setting');

        $settings = $this->model_setting_setting->getSetting('openbay_amazon');
        
        if($settings) {
            // Put patching code here
        }

        return true;
    } 
}