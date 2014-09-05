<?php
class ControllerAmazonListingReport extends Controller {
	public function index() {
		if ($this->config->get('amazon_status') != '1') {
			return;
		}

		$this->load->model('openbay/amazon_product');

		$logger = new Log('amazon.log');
		$logger->write('amazon/listing_reports - started');

		$token = $this->config->get('openbay_amazon_token');

		$incoming_token = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incoming_token !== $token) {
			$logger->write('amazon/listing_reports - Incorrect token: ' . $incoming_token);
			return;
		}

		$decrypted = $this->openbay->amazon->decryptArgs($this->request->post['data']);

		if (!$decrypted) {
			$logger->write('amazon/listing_reports - Failed to decrypt data');
			return;
		}

		$logger->write('Received Listing Report: ' . $decrypted);

		$request = json_decode($decrypted, 1);

		$data = array();

		foreach ($request['products'] as $product) {
			$data[] = array(
				'marketplace' => $request['marketplace'],
				'sku' => $product['sku'],
				'quantity' => $product['quantity'],
				'asin' => $product['asin'],
				'price' => $product['price'],
			);
		}

		if ($data) {
			$this->model_openbay_amazon_product->addListingReport($data);
		}

		$this->model_openbay_amazon_product->removeListingReportLock($request['marketplace']);

		$logger->write('amazon/listing_reports - Finished');
	}
}