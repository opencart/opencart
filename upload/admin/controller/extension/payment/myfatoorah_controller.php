<?php

class MyfatoorahController extends Controller {

    private $error = array();

//-----------------------------------------------------------------------------------------------------------------------------------------
    public function loadAdmin($id, $fields) {
        $path = "extension/payment/$id";

        // Load language
        $data = $this->language->load($path);

        //diff from oc version
        $ocUserToken    = 'user_token=' . $this->session->data['user_token'];
        $ocExLink       = 'marketplace/extension&type=payment';
        $ocCode         = $data['ocCode'] = 'payment_' . $id;

        // Set document title
        $this->document->setTitle($this->language->get('heading_title'));

        // Load settings
        $this->load->model('setting/setting');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }


        // If isset request to change settings
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate($ocCode)) {

            // Edit settings
            $this->model_setting_setting->editSetting($ocCode, $this->request->post);

            // Set success message
            $this->session->data['success'] = $this->language->get('text_success');

            // Return to extensions page
            $this->response->redirect($this->url->link($path, $ocUserToken, true));
        }

        //Load errors if exist
        $data['error_warning'] = (isset($this->error['warning'])) ? $this->error['warning'] : '';

        $data['error_apiKey'] = (isset($this->error['apiKey'])) ? $this->error['apiKey'] : '';

        // Load action buttons urls
        $data['action'] = $this->url->link($path, $ocUserToken, true);
        $data['cancel'] = $this->url->link($ocExLink, $ocUserToken, true);

        // Load breadcrumbs
        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $ocUserToken, true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $data['cancel']
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $data['action']
        );

        // Set default values for fields
        foreach ($fields as $field) {
            $key          = $ocCode . '_' . $field;
            $data[$field] = (isset($this->request->post[$key])) ? $this->request->post[$key] : $this->config->get($key);
        }

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        // Default values
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = 1;
        }
        if (!isset($data['debug'])) {
            $data['debug'] = 1;
        }
        if (!isset($data['initial_order_status_id'])) {
            $data['initial_order_status_id'] = 1;
        }
        if (!isset($data['order_status_id'])) {
            $data['order_status_id'] = $this->config->get('config_processing_status');
        }
        if (!isset($data['failed_order_status_id'])) {
            $data['failed_order_status_id'] = 10;
        }

        // Load default layout, must be in the end
        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/myfatoorah', $data));
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
    protected function validate($ocCode) {

        if ($this->request->post[$ocCode . '_status'] == 1 && !trim($this->request->post[$ocCode . '_apiKey'])) {
            $this->error['apiKey']  = $this->error['warning'] = $this->language->get('error_apiKey');
        }

        return !$this->error;
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
}
