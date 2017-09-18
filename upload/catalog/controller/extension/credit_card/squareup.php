<?php

class ControllerExtensionCreditCardSquareup extends Controller {
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('extension/credit_card/squareup');

        $this->load->model('extension/credit_card/squareup');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/credit_card/squareup', '', true)
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        } 

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        } 

        $data['back'] = $this->url->link('account/account', '', true);

        $data['cards'] = array();

        foreach ($this->model_extension_credit_card_squareup->getCards($this->customer->getId(), $this->config->get('payment_squareup_enable_sandbox')) as $card) {
            $data['cards'][] = array(
                'text' => sprintf($this->language->get('text_card_ends_in'), $card['brand'], $card['ends_in']),
                'delete' => $this->url->link('extension/credit_card/squareup/forget', 'squareup_token_id=' . $card['squareup_token_id'], true)
            );
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        
        $this->response->setOutput($this->load->view('extension/credit_card/squareup', $data));
    }

    public function forget() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('extension/credit_card/squareup');

        $this->load->model('extension/credit_card/squareup');

        $this->load->library('squareup');

        $squareup_token_id = !empty($this->request->get['squareup_token_id']) ?
            $this->request->get['squareup_token_id'] : 0;

        if ($this->model_extension_credit_card_squareup->verifyCardCustomer($squareup_token_id, $this->customer->getId())) {
            $card_info = $this->model_extension_credit_card_squareup->getCard($squareup_token_id);

            $customer_info = $this->model_extension_credit_card_squareup->getCustomer($this->customer->getId(), $card_info['sandbox']);
            
            try {
                $this->squareup->deleteCard($customer_info['square_customer_id'], $card_info['token']);
                
                $this->model_extension_credit_card_squareup->deleteCard($squareup_token_id);
                
                $this->session->data['success'] = $this->language->get('text_success_card_delete');
            } catch (\Squareup\Exception $e) {
                $this->session->data['error'] = $e->getMessage();
            }
        }

        $this->response->redirect($this->url->link('extension/credit_card/squareup', '', true));
    }
}