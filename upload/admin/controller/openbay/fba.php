<?php
class ControllerOpenbayFba extends Controller {
    public function install() {
        $this->load->model('openbay/fba');
        $this->load->model('setting/setting');
        $this->load->model('extension/extension');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'openbay/fba');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'openbay/fba');

        $this->model_openbay_fba->install();
    }

    public function uninstall() {
        $this->load->model('openbay/fba');
        $this->load->model('setting/setting');
        $this->load->model('extension/extension');

        $this->model_openbay_fba->uninstall();
        $this->model_extension_extension->uninstall('openbay', $this->request->get['extension']);
        $this->model_setting_setting->deleteSetting($this->request->get['extension']);
    }

    public function index() {
        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('openbay/fba');

        $data = $this->load->language('openbay/fba');

        $this->document->setTitle($this->language->get('text_dashboard'));
        $this->document->addScript('view/javascript/openbay/js/faq.js');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_home'),
        );
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_openbay'),
        );
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('openbay/fba', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_dashboard'),
        );

        $data['success'] = '';
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['validation'] = $this->openbay->fba->validate();
        $data['link_settings'] = $this->url->link('openbay/fba/settings', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_account'] = 'https://account.openbaypro.com/fba/index/';
        $data['link_fulfillments'] = $this->url->link('openbay/fba/fulfillmentlist', 'token=' . $this->session->data['token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('openbay/fba.tpl', $data));
    }

    public function settings() {
        $data = $this->load->language('openbay/fba_settings');

        $this->load->model('setting/setting');
        $this->load->model('openbay/fba');
        $this->load->model('localisation/order_status');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_setting_setting->editSetting('openbay_fba', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('openbay/fba/index&token=' . $this->session->data['token']));
        }

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/openbay/js/faq.js');
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_openbay'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/fba', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_fba'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/fba/settings', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('heading_title'),
        );

        $data['action'] = $this->url->link('openbay/fba/settings', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('openbay/fba', 'token=' . $this->session->data['token'], 'SSL');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->request->post['openbay_fba_status'])) {
            $data['openbay_fba_status'] = $this->request->post['openbay_fba_status'];
        } else {
            $data['openbay_fba_status'] = $this->config->get('openbay_fba_status');
        }

        if (isset($this->request->post['openbay_fba_api_key'])) {
            $data['openbay_fba_api_key'] = $this->request->post['openbay_fba_api_key'];
        } else {
            $data['openbay_fba_api_key'] = $this->config->get('openbay_fba_api_key');
        }

        if (isset($this->request->post['openbay_fba_api_account_id'])) {
            $data['openbay_fba_api_account_id'] = $this->request->post['openbay_fba_api_account_id'];
        } else {
            $data['openbay_fba_api_account_id'] = $this->config->get('openbay_fba_api_account_id');
        }

        if (isset($this->request->post['openbay_fba_send_orders'])) {
            $data['openbay_fba_send_orders'] = $this->request->post['openbay_fba_send_orders'];
        } else {
            $data['openbay_fba_send_orders'] = $this->config->get('openbay_fba_send_orders');
        }

        if (isset($this->request->post['openbay_fba_debug_log'])) {
            $data['openbay_fba_debug_log'] = $this->request->post['openbay_fba_debug_log'];
        } else {
            $data['openbay_fba_debug_log'] = $this->config->get('openbay_fba_debug_log');
        }

        if (isset($this->request->post['openbay_fba_order_trigger_status'])) {
            $data['openbay_fba_order_trigger_status'] = $this->request->post['openbay_fba_order_trigger_status'];
        } else {
            $data['openbay_fba_order_trigger_status'] = $this->config->get('openbay_fba_order_trigger_status');
        }

        if (isset($this->request->post['openbay_fba_cancel_order_trigger_status'])) {
            $data['openbay_fba_cancel_order_trigger_status'] = $this->request->post['openbay_fba_cancel_order_trigger_status'];
        } else {
            $data['openbay_fba_cancel_order_trigger_status'] = $this->config->get('openbay_fba_cancel_order_trigger_status');
        }

        $data['fulfillment_policy'] = array(
            'FillOrKill' => $this->language->get('text_fillorkill'),
            'FillAll' => $this->language->get('text_fillall'),
            'FillAllAvailable' => $this->language->get('text_fillallavailable'),
        );

        if (isset($this->request->post['openbay_fba_fulfill_policy'])) {
            $data['openbay_fba_fulfill_policy'] = $this->request->post['openbay_fba_fulfill_policy'];
        } else {
            $data['openbay_fba_fulfill_policy'] = $this->config->get('openbay_fba_fulfill_policy');
        }

        $data['shipping_speed'] = array(
            'Standard' => $this->language->get('text_standard'),
            'Expedited' => $this->language->get('text_expedited'),
            'Priority' => $this->language->get('text_priority'),
        );

        if (isset($this->request->post['openbay_fba_shipping_speed'])) {
            $data['openbay_fba_shipping_speed'] = $this->request->post['openbay_fba_shipping_speed'];
        } else {
            $data['openbay_fba_shipping_speed'] = $this->config->get('openbay_fba_shipping_speed');
        }

        $data['api_server'] = $this->openbay->fba->getServerUrl();
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('openbay/fba_settings.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'openbay/fba')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function fulfillment() {
        if (!isset($this->request->get['fulfillment_id'])) {
            $this->response->redirect($this->url->link('openbay/fba/fulfillmentlist', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data = $this->load->language('openbay/fba_fulfillment');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_home'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_openbay'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/fba', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $data['text_fba'],
        );

        $response = $this->openbay->fba->call("v1/fba/fulfillments/" . $this->request->get['fulfillment_id'] . "/", array());
        $data['response'] = $response['body'];

        if ($response['error'] == true || $response['response_http'] != 200) {
            $this->session->data['error'] = $this->language->get('error_loading_fulfillment');

            $this->response->redirect($this->url->link('openbay/fba/fulfillmentlist&token=' . $this->session->data['token']));
        }

        if (
            $data['response']['fulfillment_order_status'] == 'RECEIVED' ||
            $data['response']['fulfillment_order_status'] == 'PLANNING'
        ) {
            $data['can_cancel'] = true;
        } else {
            $data['can_cancel'] = false;
        }

        if ($data['response']['fulfillment_order_status'] == 'RECEIVED') {
            $data['can_ship'] = true;
        } else {
            $data['can_ship'] = false;
        }

        $data['cancel'] = $this->url->link('openbay/fba/fulfillmentlist', 'token=' . $this->session->data['token'], 'SSL');

        $data['token'] = $this->session->data['token'];

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('openbay/fba_fulfillment_form.tpl', $data));
    }

    public function fulfillmentList() {
        $data = $this->load->language('openbay/fba_fulfillment_list');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_home'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_openbay'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/fba', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $data['text_fba'],
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/fba', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $data['heading_title'],
        );

        if (isset($this->request->get['filter_date'])) {
            $data['filter_date'] = $this->request->get['filter_date'];
            $request_url = "?query_start_date_time=".urlencode($this->request->get['filter_date'] . "T00:00:00Z");
        } else {
            $data['filter_date'] = '';
            $request_url = "";
        }

        $data['fulfillments'] = array();

        $response = $this->openbay->fba->call("v1/fba/fulfillments/".$request_url, array());

        if (isset($response['body']) && !empty($response['body'])) {
            foreach ($response['body'] as $fulfillment_order) {
                $data['fulfillments'][] = array(
                    'seller_fulfillment_order_id' => $fulfillment_order['seller_fulfillment_order_id'],
                    'displayable_order_id' => $fulfillment_order['displayable_order_id'],
                    'displayable_order_date_time' => $fulfillment_order['displayable_order_date_time'],
                    'shipping_speed_category' => $fulfillment_order['shipping_speed_category'],
                    'fulfillment_order_status' => $fulfillment_order['fulfillment_order_status'],
                    'edit' => $this->url->link('openbay/fba/fulfillment', 'token=' . $this->session->data['token'] . '&fulfillment_id=' . $fulfillment_order['seller_fulfillment_order_id'], 'SSL'),
                );
            }
        }

        $data['cancel'] = $this->url->link('openbay/fba/index', 'token=' . $this->session->data['token'], 'SSL');
        $data['token'] = $this->session->data['token'];

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('openbay/fba_fulfillment_list.tpl', $data));
    }

    public function cancelFulfillment() {
        $response = $this->openbay->fba->call("v1/fba/fulfillments/" . $this->request->get['fulfillment_id'] . "/cancel/", array(), 'POST');

        echo '<pre>';
        print_r($response);
    }

    public function shipFulfillment() {
        $response = $this->openbay->fba->call("v1/fba/fulfillments/" . $this->request->get['fulfillment_id'] . "/ship/", array(), 'POST');

        echo '<pre>';
        print_r($response);
    }
}