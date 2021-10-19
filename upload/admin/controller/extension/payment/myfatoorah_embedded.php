<?php

require_once 'myfatoorah_controller.php';

class ControllerExtensionPaymentMyfatoorahEmbedded extends MyfatoorahController {

//-----------------------------------------------------------------------------------------------------------------------------------------
    public function index() {
        $id = 'myfatoorah_embedded';

        // Set default values for fields
        $fields = [
            'apiKey',
            'status', 'test', 'webhook_secret_key', 'sort_order', 'debug', 'initial_order_status_id', 'order_status_id', 'failed_order_status_id'
        ];

        $this->loadAdmin($id, $fields);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
}
