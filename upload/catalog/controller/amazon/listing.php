<?php

class ControllerAmazonListing extends Controller {

    public function index() {
        if ($this->config->get('amazon_status') != '1') {
            return;
        }
        
        $this->load->library('log');
        $this->load->library('amazon');
        $this->load->model('amazon/listing');
        $this->load->model('amazon/product');
        
        $logger = new Log('amazon_listing.log');
        $logger->write('amazon/listing - started');
        
        $token = $this->config->get('openbay_amazon_token');
        
        $incomingToken = isset($this->request->post['token']) ? $this->request->post['token'] : '';
        
        if ($incomingToken !== $token) {
            $logger->write('amazon/listing - Incorrect token: ' . $incomingToken);
            return;
        }
        
        $decrypted = $this->amazon->decryptArgs($this->request->post['data']);
        
        if (!$decrypted) {
            $logger->write('amazon/order Failed to decrypt data');
            return;
        }
        
        $data = json_decode($decrypted, 1);
        
        $logger->write("Received data: " . print_r($data, 1));
        
        if ($data['status']) {
            $logger->write("Updating " . $data['product_id'] . ' from ' . $data['marketplace'] . ' as successful');
            $this->model_amazon_listing->listingSuccessful($data['product_id'], $data['marketplace']);
            $this->model_amazon_product->linkProduct($data['sku'], $data['product_id']);
            $logger->write("Updated successfully");
        } else {
            $logger->write("Updating " . $data['product_id'] . ' from ' . $data['marketplace'] . ' as failed');
            $this->model_amazon_listing->listingFailed($data['product_id'], $data['marketplace'], $data['messages']);
            $logger->write("Updated successfully");
        }
    }

}