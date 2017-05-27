<?php
class ControllerExtensionOpenbayEtsy extends Controller {
	public function inbound() {
		if ($this->config->get('etsy_status') != '1') {
			$this->openbay->etsy->log('etsy/inbound - module inactive (503)');
			http_response_code(503);
			exit();
		}

		$body = $this->request->post;

		if (!isset($body['action']) || !isset($body['auth'])) {
			$this->openbay->etsy->log('etsy/inbound - action or auth data not set (401)');
			http_response_code(401);
			exit();
		}

		$incoming_token = isset($body['auth']['token']) ? $body['auth']['token'] : '';

		if (!hash_equals($this->config->get('etsy_token'), $incoming_token)) {
			$this->openbay->etsy->log('etsy/inbound - Auth failed (401): ' . $incoming_token);
			http_response_code(401);
			exit();
		}

		$data = array();

		if (isset($body['data']) && !empty($body['data'])) {
            $decrypted = $this->openbay->decrypt($body['data'], $this->openbay->etsy->getEncryptionKey(), $this->openbay->etsy->getEncryptionIv());

			if (!$decrypted) {
				$this->openbay->etsy->log('etsy/inbound Failed to decrypt data');
				http_response_code(400);
				exit();
			}

			$data = json_decode($decrypted);
		}

		switch ($body['action']) {
			case 'orders':
				$this->load->model('extension/openbay/etsy_order');

				$this->openbay->etsy->log('Orders action found');

				$this->model_extension_openbay_etsy_order->inbound($data);

				break;
			case 'products';
				$this->load->model('extension/openbay/etsy_product');

				$this->model_extension_openbay_etsy_product->inbound($data);

				break;
		}
	}

	public function eventAddOrderHistory($route, $data) {
		$this->openbay->etsy->log('eventAddOrderHistory Event fired: ' . $route);

		if (isset($data[0]) && !empty($data[0])) {
			$this->load->model('extension/openbay/etsy_order');

			$this->openbay->etsy->log('Order ID: ' . (int)$data[0]);

			$this->model_extension_openbay_etsy_order->addOrderHistory((int)$data[0]);
		}
	}
}
