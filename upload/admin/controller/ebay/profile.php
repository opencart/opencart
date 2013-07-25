<?php
class ControllerEbayProfile extends Controller {

    private $error = array();

    public function profileAll() {
        $this->data = array_merge($this->data, $this->load->language('ebay/profile'));

        $this->load->model('ebay/profile');

        $this->document->setTitle($this->data['lang_title_list']);
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->template = 'ebay/profile_list.tpl';

        $this->children = array(
            'common/header',
            'common/footer'
        );

        if (isset($this->session->data['error'])) {
            $this->data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $this->data['btn_add']  = $this->url->link('ebay/profile/add', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['types']    = $this->model_ebay_profile->getTypes();
        $this->data['profiles'] = $this->model_ebay_profile->getAll();
        $this->data['token']    = $this->session->data['token'];

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('ebay/profile/profileAll', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_heading'),
            'separator' => ' :: '
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));

    }

    public function add() {
        $this->data = array_merge($this->data, $this->load->language('ebay/profile'));

        $this->load->model('ebay/profile');

        $this->data['page_title']   = $this->data['lang_title_list_add'];
        $this->data['btn_save']     = $this->url->link('ebay/profile/add', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel']       = $this->url->link('ebay/profile/profileAll', 'token=' . $this->session->data['token'], 'SSL');

        if (!isset($this->request->post['step1'])) {
            if ($this->request->post && $this->profileValidate()) {
                $this->session->data['success'] = $this->data['lang_added'];

                $this->model_ebay_profile->add($this->request->post);

                $this->redirect($this->url->link('ebay/profile/ProfileAll&token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $this->profileForm();
    }

    public function delete() {
        $this->load->model('ebay/profile');

        if (!$this->user->hasPermission('modify', 'ebay/profile')) {
            $this->error['warning'] = $this->language->get('invalid_permission');
        }else{
            if (isset($this->request->get['ebay_profile_id'])) {
                $this->model_ebay_profile->delete($this->request->get['ebay_profile_id']);
            }
        }

        $this->redirect($this->url->link('ebay/profile/profileAll&token=' . $this->session->data['token'], 'SSL'));
    }

    public function edit() {
        $this->data = array_merge($this->data, $this->load->language('ebay/profile'));

        $this->load->model('ebay/profile');

        $this->data['page_title']   = $this->data['lang_title_list_edit'];
        $this->data['btn_save']     = $this->url->link('ebay/profile/edit', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel']       = $this->url->link('ebay/profile/profileAll', 'token=' . $this->session->data['token'], 'SSL');

        if ($this->request->post && $this->profileValidate()) {
            $this->session->data['success'] = $this->data['lang_updated'];

            $this->model_ebay_profile->edit($this->request->post['ebay_profile_id'], $this->request->post);

            $this->redirect($this->url->link('ebay/profile/profileAll&token=' . $this->session->data['token'], 'SSL'));
        }

        $this->profileForm();
    }

    public function profileForm() {
        $this->load->model('ebay/openbay');
        $this->load->model('ebay/template');

        $this->data['token']                            = $this->session->data['token'];
        $this->data['shipping_international_zones']     = $this->model_ebay_openbay->getShippingLocations();
        $this->data['templates']                        = $this->model_ebay_template->getAll();
        $this->data['types']                            = $this->model_ebay_profile->getTypes();
        $this->data['dispatchTimes']                    = $this->ebay->getSetting('dispatch_time_max');

        if(is_array($this->data['dispatchTimes'])){
            ksort($this->data['dispatchTimes']);
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->request->get['ebay_profile_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $profile_info = $this->model_ebay_profile->get($this->request->get['ebay_profile_id']);
        }

        if (isset($this->request->post['type'])) {
            $type = $this->request->post['type'];
        } else {
            $type = $profile_info['type'];
        }

        if (!array_key_exists($type, $this->data['types'])) {
            $this->session->data['error'] = $this->data['lang_no_template'];

            $this->redirect($this->url->link('ebay/profile/profileAll&token=' . $this->session->data['token']));
        }

        $this->document->setTitle($this->data['page_title']);
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->template = $this->data['types'][$type]['template'];

        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => 'OpenBay Pro',
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => 'eBay',
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('ebay/profile/profileAll', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => 'Profiles',
            'separator' => ' :: '
        );

        if (isset($this->request->post['default'])) {
            $this->data['default'] = $this->request->post['default'];
        } elseif (!empty($profile_info)) {
            $this->data['default'] = $profile_info['default'];
        } else {
            $this->data['default'] = 0;
        }

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } elseif (!empty($profile_info)) {
            $this->data['name'] = $profile_info['name'];
        } else {
            $this->data['name'] = '';
        }

        if (isset($this->request->post['description'])) {
            $this->data['description'] = $this->request->post['description'];
        } elseif (!empty($profile_info)) {
            $this->data['description'] = $profile_info['description'];
        } else {
            $this->data['description'] = '';
        }

        if (isset($this->request->post['type'])) {
            $this->data['type'] = $this->request->post['type'];
        } else {
            $this->data['type'] = $profile_info['type'];
        }

        if (isset($this->request->get['ebay_profile_id'])) {
            $this->data['ebay_profile_id'] = $this->request->get['ebay_profile_id'];
        } else {
            $this->data['ebay_profile_id'] = '';
        }

        if (isset($this->request->post['data'])) {
            $this->data['data'] = $this->request->post['data'];
        } elseif (!empty($profile_info)) {
            $this->data['data'] = $profile_info['data'];
        } else {
            $this->data['data'] = '';
        }

        if ($type == 0 && isset($profile_info['data'])) {
            $national = array();

            $i = 0;
            if (isset($profile_info['data']['service_national']) && is_array($profile_info['data']['service_national'])) {
                foreach ($profile_info['data']['service_national'] as $key => $service) {
                    $national[] = array(
                        'id' => $service,
                        'price' => $profile_info['data']['price_national'][$key],
                        'additional' => $profile_info['data']['priceadditional_national'][$key],
                        'name' => $this->model_ebay_openbay->getShippingServiceName('0', $service)
                    );
                    $i++;
                }
            }

            $this->data['data']['shipping_national']        = $national;
            $this->data['data']['shipping_national_count']  = $i;

            $international = array();

            $i = 0;
            if (isset($profile_info['data']['service_international']) && is_array($profile_info['data']['service_international'])) {
                foreach ($profile_info['data']['service_international'] as $key => $service) {

                    if(!isset($profile_info['data']['shipto_international'][$key])){
                        $profile_info['data']['shipto_international'][$key] = array();
                    }

                    $international[] = array(
                        'id'            => $service,
                        'price'         => $profile_info['data']['price_international'][$key],
                        'additional'    => $profile_info['data']['priceadditional_international'][$key],
                        'name'          => $this->model_ebay_openbay->getShippingServiceName('1', $service),
                        'shipto'        => $profile_info['data']['shipto_international'][$key]
                    );
                    $i++;
                }
            }

            $this->data['data']['shipping_international']           = $international;
            $this->data['data']['shipping_international_count']     = $i;
        }

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function profileGet(){
        $this->load->model('ebay/profile');
        $this->load->model('ebay/openbay');

        $profile_info = $this->model_ebay_profile->get($this->request->get['ebay_profile_id']);
        $zones = $this->model_ebay_openbay->getShippingLocations();

        $national = array();

        $i = 0;
        if (isset($profile_info['data']['service_national']) && is_array($profile_info['data']['service_national'])) {
            foreach ($profile_info['data']['service_national'] as $key => $service) {
                $i++;
                $national[$i] = array(
                    'id'            => $service,
                    'price'         => $profile_info['data']['price_national'][$key],
                    'additional'    => $profile_info['data']['priceadditional_national'][$key],
                    'name'          => $this->model_ebay_openbay->getShippingServiceName('0', $service)
                );
            }
        }

        $shipping_national          = $national;
        $shipping_national_count    = $i;
        $international              = array();

        $i = 0;
        if (isset($profile_info['data']['service_international']) && is_array($profile_info['data']['service_international'])) {
            foreach ($profile_info['data']['service_international'] as $key => $service) {
                $i++;
                $international[$i] = array(
                    'id'            => $service,
                    'price'         => $profile_info['data']['price_international'][$key],
                    'additional'    => $profile_info['data']['priceadditional_international'][$key],
                    'name'          => $this->model_ebay_openbay->getShippingServiceName('1', $service),
                    'shipto'        => $profile_info['data']['shipto_international'][$key]
                );
            }
        }

        $shipping_international         = $international;
        $shipping_international_count   = $i;
        $return                         = array();
        $tmp                            = '';

        if(is_array($shipping_national)){
            foreach($shipping_national as $key => $service){
                $tmp .= '<p class="shipping_national_'.$key.'"><label><strong>Service: </strong><label> ';
                $tmp .= '<input type="hidden" name="service_national['.$key.']" value="'.$service['id'].'" />';
                $tmp .= $service['name'];
                $tmp .= '</p><p class="shipping_national_'.$key.'"><label>First item: </label>';
                $tmp .= '<input type="text" name="price_national['.$key.']" style="width:50px;" value="'.$service['price'].'" />';
                $tmp .= '&nbsp;&nbsp;<label>Additional: </label>';
                $tmp .= '<input type="text" name="priceadditional_national['.$key.']" style="width:50px;" value="'.$service['additional'].'" />&nbsp;&nbsp;<a onclick="removeShipping(\'national\',\''.$key.'\');" class="button"><span>Remove</span></a></p>';
            }
        }
        $return['national_count']   = (int)$shipping_national_count;
        $return['national']         = $tmp;
        $tmp                        = '';

        if(is_array($shipping_international)){
            foreach($shipping_international as $key => $service){

                $tmp .= '<p class="shipping_international_'.$key.'" style="border-top:1px dotted; margin:0; padding:8px 0;"><label><strong>Service: </strong><label> ';
                $tmp .= '<input type="hidden" name="service_international['.$key.']" value="'.$service['id'].'" />';
                $tmp .= $service['name'];
                $tmp .= '</p>';

                $tmp .= '<h5 style="margin:5px 0;" class="shipping_international_'.$key.'">Ship to zones</h5>';
                $tmp .= '<div style="border:1px solid #000; background-color:#F5F5F5; width:100%; min-height:40px; margin-bottom:10px; display:inline-block;" class="shipping_international_'.$key.'">';
                $tmp .= '<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
                $tmp .= '<input type="checkbox" name="shipto_international['.$key.'][]" value="Worldwide" ';
                if(in_array('Worldwide', $service['shipto'])){ $tmp .= ' checked="checked"'; }
                $tmp .= '/> Worldwide</div>';

                foreach($zones as $zone){
                    $tmp .= '<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
                    $tmp .= '<input type="checkbox" name="shipto_international['.$key.'][]" value="'. $zone['shipping_location'] . '"';
                    if(in_array($zone['shipping_location'], $service['shipto'])){ $tmp .= ' checked="checked"'; }
                    $tmp .= '/> '.$zone['description'].'</div>';
                }

                $tmp .= '</div>';
                $tmp .= '<div style="clear:both;" class="shipping_international_'.$key.'"></div>';
                $tmp .= '<p class="shipping_international_'.$key.'"><label>First item: </label>';
                $tmp .= '<input type="text" name="price_international['.$key.']" style="width:50px;" value="'.$service['price'].'" />';
                $tmp .= '&nbsp;&nbsp;<label>Additional: </label>';
                $tmp .= '<input type="text" name="priceadditional_international['.$key.']" style="width:50px;" value="'.$service['additional'].'" />&nbsp;&nbsp;<a onclick="removeShipping(\'international\',\''.$key.'\');" class="button"><span>Remove</span></a></p>';
            }
        }
        $return['international_count']  = (int)$shipping_international_count;
        $return['international']        = $tmp;
        $profile_info['html']           = $return;

        $this->response->setOutput(json_encode($profile_info));
    }

    private function profileValidate() {
        if (!$this->user->hasPermission('modify', 'ebay/profile')) {
            $this->error['warning'] = $this->language->get('invalid_permission');
        }

        if ($this->request->post['name'] == '') {
            $this->error['name'] = $this->data['lang_error_name'];
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}