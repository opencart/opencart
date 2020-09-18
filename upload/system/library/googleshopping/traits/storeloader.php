<?php

namespace googleshopping\traits;

trait StoreLoader {
    protected function loadStore($store_id) {
        $this->registry->set('setting', new \Config());

        $this->load->model('setting/setting');

        foreach ($this->model_setting_setting->getSetting('advertise_google', $store_id) as $key => $value) {
            $this->setting->set($key, $value);
        }
    }
}