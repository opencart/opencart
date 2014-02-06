<?php
class ControllerAmazonusListing extends Controller {
	public function index() {
		if ($this->config->get('amazonus_status') != '1') {
			return;
		}

		$this->load->library('log');
		$this->load->library('amazonus');
		$this->load->model('openbay/amazonus_listing');
		$this->load->model('openbay/amazonus_product');

		$logger = new Log('amazonus_listing.log');
		$logger->write('amazonus/listing - started');

		$token = $this->config->get('openbay_amazonus_token');

		$incomingToken = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incomingToken !== $token) {
			$logger->write('amazonus/listing - Incorrect token: ' . $incomingToken);
			return;
		}

		$decrypted = $this->openbay->amazonus->decryptArgs($this->request->post['data']);

		if (!$decrypted) {
			$logger->write('amazonus/order Failed to decrypt data');
			return;
		}

		$data = json_decode($decrypted, 1);

		$logger->write("Received data: " . print_r($data, 1));

		if ($data['status']) {
			$logger->write("Updating " . $data['product_id'] . ' as successful');
			$this->model_openbay_amazonus_listing->listingSuccessful($data['product_id']);
			$this->model_openbay_amazonus_product->linkProduct($data['sku'], $data['product_id']);
			$logger->write("Updated successfully");
		} else {
			$logger->write("Updating " . $data['product_id'] . ' as failed');
			$this->model_openbay_amazonus_listing->listingFailed($data['product_id'], $data['messages']);
			$logger->write("Updated successfully");
		}
	}
}
?>