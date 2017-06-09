<?php
class ControllerExtensionOpenbayFba extends Controller {
    public function install() {
        $this->load->model('extension/openbay/fba');
        $this->load->model('setting/setting');
        $this->load->model('setting/extension');
        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/openbay/fba');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/openbay/fba');

        $this->model_extension_openbay_fba->install();
    }

    public function uninstall() {
        $this->load->model('extension/openbay/fba');
        $this->load->model('setting/setting');
        $this->load->model('setting/extension');

        $this->model_extension_openbay_fba->uninstall();
        $this->model_setting_extension->uninstall('openbay', $this->request->get['extension']);
        $this->model_setting_setting->deleteSetting($this->request->get['extension']);
    }

    public function index() {
        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('extension/openbay/fba');

        $data = $this->load->language('extension/openbay/fba');

        $this->document->setTitle($this->language->get('text_dashboard'));
        $this->document->addScript('view/javascript/openbay/js/faq.js');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'text'      => $this->language->get('text_home'),
        );
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
            'text'      => $this->language->get('text_openbay'),
        );
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true),
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
        $data['link_settings'] = $this->url->link('extension/openbay/fba/settings', 'user_token=' . $this->session->data['user_token'], true);
        $data['link_account'] = 'https://account.openbaypro.com/fba/index/';
        $data['link_fulfillments'] = $this->url->link('extension/openbay/fba/fulfillmentlist', 'user_token=' . $this->session->data['user_token'], true);
        $data['link_orders'] = $this->url->link('extension/openbay/fba/orderlist', 'user_token=' . $this->session->data['user_token'], true);
		$data['link_signup'] = 'https://account.openbaypro.com/fba/apiRegister/?endpoint=2&utm_source=opencart_install&utm_medium=dashboard&utm_campaign=fba';

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/openbay/fba', $data));
    }

    public function settings() {
        $data = $this->load->language('extension/openbay/fba_settings');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/openbay/js/faq.js');

        $this->load->model('setting/setting');
        $this->load->model('extension/openbay/fba');
        $this->load->model('localisation/order_status');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_setting_setting->editSetting('openbay_fba', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $this->language->get('text_home'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $this->language->get('text_openbay'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $this->language->get('text_fba'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba/settings', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $this->language->get('heading_title'),
        );

        $data['action'] = $this->url->link('extension/openbay/fba/settings', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true);
        $data['link_signup'] = 'https://account.openbaypro.com/fba/apiRegister/?endpoint=2&utm_source=opencart_install&utm_medium=settings&utm_campaign=fba';

        $data['user_token'] = $this->session->data['user_token'];

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
            $data['openbay_fba_api_key'] = trim($this->request->post['openbay_fba_api_key']);
        } else {
            $data['openbay_fba_api_key'] = trim($this->config->get('openbay_fba_api_key'));
        }

        if (isset($this->request->post['openbay_fba_encryption_key'])) {
            $data['openbay_fba_encryption_key'] = trim($this->request->post['openbay_fba_encryption_key']);
        } else {
            $data['openbay_fba_encryption_key'] = trim($this->config->get('openbay_fba_encryption_key'));
        }

        if (isset($this->request->post['openbay_fba_encryption_iv'])) {
            $data['openbay_fba_encryption_iv'] = trim($this->request->post['openbay_fba_encryption_iv']);
        } else {
            $data['openbay_fba_encryption_iv'] = trim($this->config->get('openbay_fba_encryption_iv'));
        }

        if (isset($this->request->post['openbay_fba_api_account_id'])) {
            $data['openbay_fba_api_account_id'] = trim($this->request->post['openbay_fba_api_account_id']);
        } else {
            $data['openbay_fba_api_account_id'] = trim($this->config->get('openbay_fba_api_account_id'));
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

        $order_total = $this->model_extension_openbay_fba->countFbaOrders();

        if ($order_total > 0) {
            $data['prefix_can_edit'] = false;
        } else {
            $data['prefix_can_edit'] = true;
        }

        if (isset($this->request->post['openbay_fba_order_prefix'])) {
            $data['openbay_fba_order_prefix'] = $this->request->post['openbay_fba_order_prefix'];
        } else {
            $data['openbay_fba_order_prefix'] = $this->config->get('openbay_fba_order_prefix');
        }

        if (isset($this->request->post['openbay_fba_order_trigger_status'])) {
            $data['openbay_fba_order_trigger_status'] = $this->request->post['openbay_fba_order_trigger_status'];
        } else {
            $data['openbay_fba_order_trigger_status'] = $this->config->get('openbay_fba_order_trigger_status');
        }

        if (isset($this->request->post['openbay_fba_only_fill_complete'])) {
            $data['openbay_fba_only_fill_complete'] = $this->request->post['openbay_fba_only_fill_complete'];
        } else {
            $data['openbay_fba_only_fill_complete'] = $this->config->get('openbay_fba_only_fill_complete');
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

        $this->response->setOutput($this->load->view('extension/openbay/fba_settings', $data));
    }

    public function verifyCredentials() {
        $this->load->language('extension/openbay/fba_settings');

        $errors = array();

        if (!isset($this->request->post['openbay_fba_api_key']) || empty($this->request->post['openbay_fba_api_key'])) {
            $errors[] = array('message' => $this->language->get('error_api_key'));
        }

        if (!isset($this->request->post['openbay_fba_api_account_id']) || empty($this->request->post['openbay_fba_api_account_id'])) {
            $errors[] = array('message' => $this->language->get('error_api_account_id'));
        }

        if (!isset($this->request->post['openbay_fba_encryption_key']) || empty($this->request->post['openbay_fba_encryption_key'])) {
            $errors[] = array('message' => $this->language->get('error_encryption_key'));
        }

        if (!isset($this->request->post['openbay_fba_encryption_iv']) || empty($this->request->post['openbay_fba_encryption_iv'])) {
            $errors[] = array('message' => $this->language->get('error_encryption_iv'));
        }

        if (!$errors) {
            $this->openbay->fba->setApiKey($this->request->post['openbay_fba_api_key']);
            $this->openbay->fba->setAccountId($this->request->post['openbay_fba_api_account_id']);
            $this->openbay->fba->setEncryptionKey($this->request->post['openbay_fba_encryption_key']);
            $this->openbay->fba->setEncryptionIv($this->request->post['openbay_fba_encryption_iv']);

            $response = $this->openbay->fba->call("v1/fba/status/", array(), 'GET');
        } else {
            $response = array(
                "result" => null,
                "error" => true,
                "error_messages" => $errors,
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/openbay/fba')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['openbay_fba_api_key']) {
            $this->error['api_key'] = $this->language->get('error_api_key');
        }

        if (!$this->request->post['openbay_fba_api_account_id']) {
            $this->error['api_account_id'] = $this->language->get('error_api_account_id');
        }

        return !$this->error;
    }

    public function fulfillment() {
        $data = $this->load->language('extension/openbay/fba_fulfillment');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/openbay/js/faq.js');

        if (!isset($this->request->get['fulfillment_id'])) {
            $this->response->redirect($this->url->link('extension/openbay/fba/fulfillmentlist', 'user_token=' . $this->session->data['user_token'] . (!empty($this->request->get['filter_date']) ? '&filter_date=' . $this->request->get['filter_date'] : ''), true));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'text'      => $this->language->get('text_home'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $this->language->get('text_openbay'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $data['text_fba'],
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba/fulfillmentlist', 'user_token=' . $this->session->data['user_token'] . (!empty($this->request->get['filter_date']) ? '&filter_date=' . $this->request->get['filter_date'] : ''), true),
            'text' => $data['heading_title'],
        );

        $response = $this->openbay->fba->call("v1/fba/fulfillments/" . $this->request->get['fulfillment_id'] . "/", array());
        $data['response'] = $response['body'];

        if ($response['error'] == true || $response['response_http'] != 200) {
            $this->session->data['error'] = $this->language->get('error_loading_fulfillment');

            $this->response->redirect($this->url->link('extension/openbay/fba/fulfillmentlist', 'user_token=' . $this->session->data['user_token'], true));
        }

        $data['user_token'] = $this->session->data['user_token'];

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

        $this->response->setOutput($this->load->view('extension/openbay/fba_fulfillment_form', $data));
    }

    public function fulfillmentList() {
        $data = $this->load->language('extension/openbay/fba_fulfillment_list');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/openbay/js/faq.js');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'text'      => $this->language->get('text_home'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $this->language->get('text_openbay'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $data['text_fba'],
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true),
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

        $response = $this->openbay->fba->call("v1/fba/fulfillments/".$request_url, array(), 'GET');

        if (isset($response['body']) && is_array($response['body'])) {
            foreach ($response['body'] as $fulfillment_order) {
                $data['fulfillments'][] = array(
                    'seller_fulfillment_order_id' => $fulfillment_order['seller_fulfillment_order_id'],
                    'displayable_order_id' => $fulfillment_order['displayable_order_id'],
                    'displayable_order_date_time' => $fulfillment_order['displayable_order_date_time'],
                    'shipping_speed_category' => $fulfillment_order['shipping_speed_category'],
                    'fulfillment_order_status' => $fulfillment_order['fulfillment_order_status'],
                    'edit' => $this->url->link('extension/openbay/fba/fulfillment', 'user_token=' . $this->session->data['user_token'] . '&fulfillment_id=' . $fulfillment_order['seller_fulfillment_order_id'] . (!empty($data['filter_date']) ? '&filter_date=' . $data['filter_date'] : ''), true),
                );
            }
        }

        $data['cancel'] = $this->url->link('extension/openbay/fba/index', 'user_token=' . $this->session->data['user_token'], true);
        $data['user_token'] = $this->session->data['user_token'];

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

        $this->response->setOutput($this->load->view('extension/openbay/fba_fulfillment_list', $data));
    }

    public function shipFulfillment() {
        $this->load->language('extension/openbay/fba_fulfillment');

        $errors = array();

        if (empty($this->request->get['order_id']) || empty($this->request->get['fba_order_fulfillment_id'])) {
            $this->session->data['error'] = $this->language->get('error_missing_id');

            $this->response->redirect($this->url->link('extension/openbay/fba/orderlist', 'user_token=' . $this->session->data['user_token'], true));
        } else {
            $order_id = (int)$this->request->get['order_id'];
            $fba_order_fulfillment_id = (int)$this->request->get['fba_order_fulfillment_id'];

            $this->openbay->fba->log('shipFulfillment request for order ID: ' . $order_id . ', Fulfillment ID: ' . $fba_order_fulfillment_id);

            $fba_fulfillment_id = $this->openbay->fba->createFBAFulfillmentID($order_id, 1);

            $response = $this->openbay->fba->call("v1/fba/fulfillments/" . $this->config->get('openbay_fba_order_prefix') . $order_id . '-' . $fba_order_fulfillment_id . "/ship/", array(), 'GET');



            if (!isset($response['response_http']) || $response['response_http'] != 200) {
                /**
                 * @todo notify the admin about any errors
                 */
                $errors[] = $this->language->get('error_amazon_request');

                //$this->openbay->fba->updateFBAOrderStatus($order_id, 1);
            } else {
                $this->openbay->fba->populateFBAFulfillment(json_encode(array()), json_encode($response), $response['response_http'], $fba_fulfillment_id);

                $this->openbay->fba->updateFBAOrderStatus($order_id, 3);

                $this->session->data['success'] = $this->language->get('text_fulfillment_shipped');
            }
        }

        if ($errors) {
            $this->session->data['error'] = $errors;
        }

        $this->response->redirect($this->url->link('extension/openbay/fba/order', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id, true));
    }

    public function cancelFulfillment() {
        $this->load->language('extension/openbay/fba_fulfillment');

        $errors = array();

        if (empty($this->request->get['order_id']) || empty($this->request->get['fba_order_fulfillment_id'])) {
            $this->session->data['error'] = $this->language->get('error_missing_id');

            $this->response->redirect($this->url->link('extension/openbay/fba/orderlist', 'user_token=' . $this->session->data['user_token'], true));
        } else {
            $order_id = (int)$this->request->get['order_id'];
            $fba_order_fulfillment_id = (int)$this->request->get['fba_order_fulfillment_id'];

            $this->openbay->fba->log('cancelFulfillment request for order ID: ' . $order_id . ', Fulfillment ID: ' . $fba_order_fulfillment_id);

            $fba_fulfillment_id = $this->openbay->fba->createFBAFulfillmentID($order_id, 2);

            $response = $this->openbay->fba->call("v1/fba/fulfillments/" . $this->config->get('openbay_fba_order_prefix') . $order_id . '-' . $fba_order_fulfillment_id . "/cancel/", array(), 'POST');

            if (!isset($response['response_http']) || $response['response_http'] != 200) {
                /**
                 * @todo notify the admin about any errors
                 */
                $errors[] = $this->language->get('error_amazon_request');
            } else {
                $this->openbay->fba->populateFBAFulfillment(json_encode(array()), json_encode($response), $response['response_http'], $fba_fulfillment_id);

                $this->openbay->fba->updateFBAOrderStatus($order_id, 4);

                $this->session->data['success'] = $this->language->get('text_fulfillment_cancelled');
            }
        }

        if ($errors) {
            $this->session->data['error'] = $errors;
        }

        $this->response->redirect($this->url->link('extension/openbay/fba/order', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id, true));
    }

    public function resendFulfillment() {
        $this->load->language('extension/openbay/fba_fulfillment');

        $errors = array();

        if (empty($this->request->get['order_id'])) {
            $this->session->data['error'] = $this->language->get('error_missing_id');

            $this->response->redirect($this->url->link('extension/openbay/fba/orderlist', 'user_token=' . $this->session->data['user_token'], true));
        } else {
            $order_id = (int)$this->request->get['order_id'];

            $this->openbay->fba->log('resendFulfillment request for order ID: ' . $order_id);

            $this->load->model('sale/order');
            $this->load->model('catalog/product');

            $order = $this->model_sale_order->getOrder($order_id);

            if ($order['shipping_method']) {
                if ($this->config->get('openbay_fba_order_trigger_status') == $order['order_status_id']) {
                    $fba_fulfillment_id = $this->openbay->fba->createFBAFulfillmentID($order_id, 1);

                    $order_products = $this->model_sale_order->getOrderProducts($order_id);

                    $fulfillment_items = array();

                    foreach ($order_products as $order_product) {
                        $product = $this->model_catalog_product->getProduct($order_product['product_id']);

                        if ($product['location'] == 'FBA') {
                            $fulfillment_items[] = array(
                                'seller_sku' => $product['sku'],
                                'quantity' => $order_product['quantity'],
                                'seller_fulfillment_order_item_id' => $this->config->get('openbay_fba_order_prefix') . $fba_fulfillment_id . '-' . $order_product['order_product_id'],
                                'per_unit_declared_value' => array(
                                    'currency_code' => $order['currency_code'],
                                    'value' => number_format($order_product['price'], 2)
                                ),
                            );
                        }
                    }

                    $total_fulfillment_items = count($fulfillment_items);

                    if (!empty($fulfillment_items)) {
                        $request = array();

                        $datetime = new DateTime($order['date_added']);
                        $request['displayable_order_datetime'] = $datetime->format(DateTime::ISO8601);

                        $request['seller_fulfillment_order_id'] = $this->config->get('openbay_fba_order_prefix') . $order_id . '-' . $fba_fulfillment_id;
                        $request['displayable_order_id'] = $order_id;
                        $request['displayable_order_comment'] = 'none';
                        $request['shipping_speed_category'] = $this->config->get('openbay_fba_shipping_speed');
                        $request['fulfillment_action'] = ($this->config->get('openbay_fba_send_orders') == 1 ? 'Ship' : 'Hold');
                        $request['fulfillment_policy'] = $this->config->get('openbay_fba_fulfill_policy');

                        $request['destination_address'] = array(
                            'name' => $order['shipping_firstname'] . ' ' . $order['shipping_lastname'],
                            'line_1' => (!empty($order['shipping_company']) ? $order['shipping_company'] : $order['shipping_address_1']),
                            'line_2' => (!empty($order['shipping_company']) ? $order['shipping_address_1'] : $order['shipping_address_2']),
                            'line_3' => (!empty($order['shipping_company']) ? $order['shipping_address_2'] : ''),
                            'state_or_province_code' => $order['shipping_zone'],
                            'city' => $order['shipping_city'],
                            'country_code' => $order['shipping_iso_code_2'],
                            'postal_code' => $order['shipping_postcode'],
                        );

                        $request['items'] = $fulfillment_items;

                        $response = $this->openbay->fba->call("v1/fba/fulfillments/", $request, 'POST');

                        if ($response['response_http'] != 201) {
                            /**
                             * @todo notify the admin about any errors
                             */
                            $errors[] = $this->language->get('error_amazon_request');

                            $this->openbay->fba->updateFBAOrderStatus($order_id, 1);
                        } else {
                            if ($this->config->get('openbay_fba_send_orders') == 1) {
                                $this->openbay->fba->updateFBAOrderStatus($order_id, 3);
                            } else {
                                $this->openbay->fba->updateFBAOrderStatus($order_id, 2);
                            }

                            $this->openbay->fba->updateFBAOrderRef($order_id, $this->config->get('openbay_fba_order_prefix') . $order_id . '-' . $fba_fulfillment_id);

                            $this->session->data['success'] = $this->language->get('text_fulfillment_sent');
                        }

                        $this->openbay->fba->populateFBAFulfillment(json_encode($request), json_encode($response), $response['response_http'], $fba_fulfillment_id);
                        $this->openbay->fba->updateFBAOrderFulfillmentID($order_id, $fba_fulfillment_id);
                    } else {
                        $errors[] = $this->language->get('error_no_items');
                    }
                }
            } else {
                $errors[] = $this->language->get('error_no_shipping');
            }
        }

        if ($errors) {
            $this->session->data['error'] = $errors;
        }

        $this->response->redirect($this->url->link('extension/openbay/fba/order', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id, true));
    }

    public function orderList() {
        $data = $this->load->language('extension/openbay/fba_order');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/openbay/js/faq.js');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'text'      => $this->language->get('text_home'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $this->language->get('text_openbay'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $data['text_fba'],
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $data['heading_title'],
        );

        $filters = array();

        $url = '';

        if (isset($this->request->get['filter_start'])) {
            $filters['filter_start'] = $this->request->get['filter_start'];
            $data['filter_start'] = $this->request->get['filter_start'];
            $url .= "&filter_start=".urlencode($this->request->get['filter_start']);
        } else {
            $filters['filter_start'] = null;
            $data['filter_start'] = null;
        }

        if (isset($this->request->get['filter_end'])) {
            $filters['filter_end'] = $this->request->get['filter_end'];
            $data['filter_end'] = $this->request->get['filter_end'];
            $url .= "&filter_end=".urlencode($this->request->get['filter_end']);
        } else {
            $filters['filter_end'] = null;
            $data['filter_end'] = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filters['filter_status'] = $this->request->get['filter_status'];
            $data['filter_status'] = $this->request->get['filter_status'];
            $url .= "&filter_status=".urlencode($this->request->get['filter_status']);
        } else {
            $filters['filter_status'] = null;
            $data['filter_status'] = null;
        }

        $data['orders'] = array();

        $orders = $this->openbay->fba->getFBAOrders($filters);

        if (!empty($orders)) {
            foreach ($orders as $order) {
                $data['orders'][] = array(
                    'order_id' => $order['order_id'],
                    'order_link' => $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order['order_id'] . $url, true),
                    'status' => $order['status'],
                    'created' => $order['created'],
                    'fba_item_count' => $this->openbay->fba->hasOrderFBAItems($order['order_id']),
                    'view' => $this->url->link('extension/openbay/fba/order', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order['order_id'] . $url, true)
                );
            }
        }

        $data['cancel'] = $this->url->link('extension/openbay/fba/index', 'user_token=' . $this->session->data['user_token'], true);
        $data['user_token'] = $this->session->data['user_token'];

        $data['status_options'] = array(
            0 => $this->language->get('text_option_new'),
            1 => $this->language->get('text_option_error'),
            2 => $this->language->get('text_option_held'),
            3 => $this->language->get('text_option_shipped'),
        );

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

        $this->response->setOutput($this->load->view('extension/openbay/fba_order_list', $data));
    }

    public function order() {
        $data = $this->load->language('extension/openbay/fba_order');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/openbay/js/faq.js');

        $this->load->model('sale/order');
        $this->load->model('catalog/product');

        if (!isset($this->request->get['order_id'])) {
            $this->response->redirect($this->url->link('extension/openbay/fba/orderList', 'user_token=' . $this->session->data['user_token'], true));
        }

        $order_id = (int)$this->request->get['order_id'];
        $order_fba = $this->openbay->fba->getFBAOrder($order_id);
        $order_info = $this->model_sale_order->getOrder($order_id);

        if ($order_fba['status'] == 2 || $order_fba['status'] == 3 || $order_fba['status'] == 4) {
            $data['fulfillment_id'] = $order_fba['fba_order_fulfillment_ref'];
            $data['fulfillment_link'] = $this->url->link('extension/openbay/fba/fulfillment', 'user_token=' . $this->session->data['user_token'] . '&fulfillment_id=' . $data['fulfillment_id'], true);
        } else {
            $data['fulfillment_id'] = '';
            $data['fulfillment_link'] = '';
        }

        $data['fba_order_status'] = $order_fba['status'];
        $data['order_id'] = $order_id;
        $data['order_link'] = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id, true);
        $data['resend_link'] = $this->url->link('extension/openbay/fba/resendfulfillment', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id, true);
        $data['ship_link'] = $this->url->link('extension/openbay/fba/shipfulfillment', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id . '&fba_order_fulfillment_id=' . $order_fba['fba_order_fulfillment_id'], true);
        $data['cancel_link'] = $this->url->link('extension/openbay/fba/cancelfulfillment', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id . '&fba_order_fulfillment_id=' . $order_fba['fba_order_fulfillment_id'], true);

        $data['cancel'] = $this->url->link('extension/openbay/fba/orderlist', 'user_token=' . $this->session->data['user_token'], true);

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'text'      => $this->language->get('text_home'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $this->language->get('text_openbay'),
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $data['text_fba'],
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay/fba/orderlist', 'user_token=' . $this->session->data['user_token'], true),
            'text' => $data['heading_title'],
        );

        $data['fulfillments'] = array();

        if (is_array($order_fba['fulfillments'])) {
            foreach ($order_fba['fulfillments'] as $fulfillment) {
                $response_body = json_decode($fulfillment['response_body'], true);

                $fulfillment_errors = array();

                if (isset($response_body['error']) && $response_body['error'] == true) {
                    if (is_array($response_body['error_messages']) && !empty($response_body['error_messages'])) {
                        $fulfillment_errors = $response_body['error_messages'];
                    }
                }

                $data['fulfillments'][] = array(
                    'fba_order_fulfillment_id' => $fulfillment['fba_order_fulfillment_id'],
                    'created' => $fulfillment['created'],
                    'request_body' => json_decode($fulfillment['request_body']),
                    'request_body_output' => print_r(json_decode($fulfillment['request_body']), true),
                    'response_body' => json_decode($fulfillment['response_body']),
                    'response_body_output' => print_r(json_decode($fulfillment['response_body']), true),
                    'response_header_code' => $fulfillment['response_header_code'],
                    'errors' => $fulfillment_errors,
                    'type' => $fulfillment['type'],
                );
            }
        }

        // Shipping Address
        if ($order_info['shipping_address_format']) {
            $format = $order_info['shipping_address_format'];
        } else {
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        $find = array(
            '{firstname}',
            '{lastname}',
            '{company}',
            '{address_1}',
            '{address_2}',
            '{city}',
            '{postcode}',
            '{zone}',
            '{zone_code}',
            '{country}'
        );

        $replace = array(
            'firstname' => $order_info['shipping_firstname'],
            'lastname'  => $order_info['shipping_lastname'],
            'company'   => $order_info['shipping_company'],
            'address_1' => $order_info['shipping_address_1'],
            'address_2' => $order_info['shipping_address_2'],
            'city'      => $order_info['shipping_city'],
            'postcode'  => $order_info['shipping_postcode'],
            'zone'      => $order_info['shipping_zone'],
            'zone_code' => $order_info['shipping_zone_code'],
            'country'   => $order_info['shipping_country']
        );

        $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

        $data['status_options'] = array(
            0 => $this->language->get('text_option_new'),
            1 => $this->language->get('text_option_error'),
            2 => $this->language->get('text_option_held'),
            3 => $this->language->get('text_option_shipped'),
            4 => $this->language->get('text_option_cancelled'),
        );

        $data['type_options'] = array(
            0 => $this->language->get('text_type_new'),
            1 => $this->language->get('text_type_ship'),
            2 => $this->language->get('text_type_cancel'),
        );

        $data['products'] = array();

        $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

        foreach ($products as $product) {
            $option_data = array();

            $product_info = $this->model_catalog_product->getProduct($product['product_id']);

            $options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

            foreach ($options as $option) {
                if ($option['type'] != 'file') {
                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => $option['value'],
                        'type'  => $option['type']
                    );
                }
            }

            $data['products'][] = array(
                'order_product_id' => $product['order_product_id'],
                'product_id'       => $product['product_id'],
                'name'    	 	   => $product['name'],
                'sku'    		   => $product_info['sku'],
                'option'   		   => $option_data,
                'quantity'		   => $product['quantity'],
                'fba'		       => ($product_info['location'] == 'FBA' ? 1 : 0),
                'href'     		   => $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'], true),
            );
        }

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

        $this->response->setOutput($this->load->view('extension/openbay/fba_order_info', $data));
    }
}
