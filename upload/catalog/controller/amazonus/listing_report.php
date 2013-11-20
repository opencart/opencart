<?php
class ControllerAmazonusListingReport extends Controller {
	public function index() {
		if ($this->config->get('amazonus_status') != '1') {
			return;
		}

		$this->load->model('amazonus/product');

		$logger = new Log('amazonus.log');
		$logger->write('amazonus/listing_reports - started');

		$token = $this->config->get('openbay_amazonus_token');

		$incomingToken = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incomingToken !== $token) {
			$logger->write('amazonus/listing_reports - Incorrect token: ' . $incomingToken);
			return;
		}

		$decrypted = $this->openbay->amazonus->decryptArgs($this->request->post['data']);

		if (!$decrypted) {
			$logger->write('amazonus/listing_reports - Failed to decrypt data');
			return;
		}

		$logger->write('Received Listing Report: ' . $decrypted);

		$request = json_decode($decrypted, 1);

		$data = array();

		foreach ($request['products'] as $product) {
			$data[] = array(
				'sku' => $product['sku'],
				'quantity' => $product['quantity'],
				'asin' => $product['asin'],
				'price' => $product['price'],
			);
		}

		if ($data) {
			$this->model_amazonus_product->addListingReport($data);
		}

		$this->model_amazonus_product->removeListingReportLock($request['marketplace']);

		$logger->write('amazonus/listing_reports - Finished');
	}
}
?>