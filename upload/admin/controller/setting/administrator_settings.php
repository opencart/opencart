<?php

namespace Opencart\Admin\Controller\Setting;
class AdministratorSettings extends \Opencart\System\Engine\Controller
{
    private static array $setting_keys = [
        'config_product_upc',
        'config_product_ean',
        'config_product_jan',
        'config_product_isbn',
        'config_product_mpn',
        'config_price_incl_tax'
    ];

    public function index(): void
    {

        $this->load->language('setting/administrator_settings');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/administrator_settings', 'user_token=' . $this->session->data['user_token'])
        ];

        $data['upc_enabled'] = $this->config->get('config_product_ean');

        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model("setting/admin");

        // Get the identifier settings of Default Store.
        $identifier_settings = $this->model_setting_admin->getByKeys(self::$setting_keys);
        foreach($identifier_settings as $setting_name => $setting_value){
            $data[$setting_name] = $setting_value;
        }

        $data['save'] = $this->url->link('setting/administrator_settings.save', 'user_token=' . $this->session->data['user_token']);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('setting/administrator_settings', $data));
    }

    public function save(): void
    {
        $this->load->language('setting/administrator_settings');
        $json = [];

        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            $json['error']['warning'] = $this->language->get('error_permission');
        }

        if(empty($json)){
            $this->load->model("setting/admin");

            $values = [];
            foreach(self::$setting_keys as $setting_key){
                if(array_key_exists($setting_key, $this->request->post)){
                    $values[$setting_key] = $this->request->post[$setting_key];
                }
            }

            $this->model_setting_admin->save($values);
            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
