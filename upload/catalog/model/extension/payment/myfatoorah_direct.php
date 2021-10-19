<?php

class ModelExtensionPaymentMyFatoorahDirect extends Model {

    private $id     = 'myfatoorah_direct';
    private $path   = 'extension/payment/';
    private $ocCode = 'payment_myfatoorah_direct';

//---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getMethod($address, $total) {

        $this->language->load($this->path . $this->id);

        $title = $this->language->get('text_title');

        $method_data = array(
            'code'       => $this->id,
            'title'      => $title,
            'terms'      => '',
            'sort_order' => $this->config->get($this->ocCode . '_sort_order')
        );
        return $method_data;
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
}
