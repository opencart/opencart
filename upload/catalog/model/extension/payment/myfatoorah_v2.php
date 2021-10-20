<?php

require_once DIR_SYSTEM . 'library/myfatoorah/PaymentMyfatoorahApiV2.php';

class ModelExtensionPaymentMyfatoorahV2 extends Model {

    private $id     = 'myfatoorah_v2';
    private $path   = 'extension/payment/';
    private $ocCode = 'payment_myfatoorah_v2';

//---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getMethod($address, $total) {

        $this->language->load($this->path . $this->id);

        $title = $this->language->get('text_title');

        //if this is not the (admin @editOrder) view i.e. it is the (catalog @checkout) view
        if (!isset($this->session->data['api_id'])) {
            $types = $this->config->get($this->ocCode . '_payment_type');

            if ($types == 'multigateways') {
                $title = $this->getTitle($title);
            }
        }

        if (!$title) {
            return [];
        }

        $method_data = array(
            'code'       => $this->id,
            'title'      => $title,
            'terms'      => '',
            'sort_order' => $this->config->get($this->ocCode . '_sort_order')
        );
        return $method_data;
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getTitle($title) {

        $mfObj = new PaymentMyfatoorahApiV2($this->config->get($this->ocCode . '_apiKey'), $this->config->get($this->ocCode . '_test'));

        $paymentMethods = $mfObj->getVendorGatewaysByType();

        $count = count($paymentMethods);
        if ($count == 0) {
            unset($this->session->data[$this->id . '_payment']);
            return false;
        } else if ($count == 1) {
            $this->session->data[$this->id . '_payment'] = $paymentMethods[0]->PaymentMethodId;
            return ($this->language->get('code') == 'ar') ? $paymentMethods[0]->PaymentMethodAr : $paymentMethods[0]->PaymentMethodEn;
        }


        $data = ['gateways' => $paymentMethods, 'language' => $this->language->get('code'), 'title' => $title, 'id' => $this->id];

        //for journal3 only
        $scriptPath = 'catalog/view/javascript/myfatoorah/';
        $viewObj    = ($this->journal3) ? $this->journal3 : $this;

        $viewObj->document->addStyle($scriptPath . $this->config->get($this->ocCode . '_view') . '.css');
        $viewObj->document->addScript($scriptPath . $this->id . '.js', 'footer');

        $data['styles']  = ($this->journal3) ? [] : $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts('footer');

        return $this->load->view($this->path . $this->id . '_paymentType', $data);
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
}
