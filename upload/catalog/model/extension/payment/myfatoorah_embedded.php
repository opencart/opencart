<?php

class ModelExtensionPaymentMyFatoorahEmbedded extends Model {

    private $id     = 'myfatoorah_embedded';
    private $path   = 'extension/payment/';
    private $ocCode = 'payment_myfatoorah_embedded';

//---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getMethod($address, $total) {

        $this->language->load($this->path . $this->id);

        $script = ($this->config->get($this->ocCode . '_test') === '1') ? 'demo' : 'portal';

        $title = $this->language->get('text_title');

        $method_data = array(
            'code'       => $this->id,
            'title'      => $title . ' <script src="https://' . $script . '.myfatoorah.com/cardview/v1/session.js"></script>',
            'terms'      => '',
            'sort_order' => $this->config->get($this->ocCode . '_sort_order')
        );
        return $method_data;
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
}
