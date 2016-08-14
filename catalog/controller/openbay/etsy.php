<?php
class ControllerOpenbayEtsy extends Controller {
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
		$incoming_secret = isset($body['auth']['secret']) ? $body['auth']['secret'] : '';

		if ($incoming_token !== $this->config->get('etsy_token') || $incoming_secret !== $this->config->get('etsy_enc1')) {
			$this->openbay->etsy->log('etsy/inbound - Auth failed (401): ' . $incoming_token . '/' . $incoming_secret);
			http_response_code(401);
			exit();
		}

		$data = array();

		if (isset($body['data']) && !empty($body['data'])) {
			$decrypted = $this->openbay->etsy->decryptArgs($body['data'], true);

			if (!$decrypted) {
				$this->openbay->etsy->log('etsy/inbound Failed to decrypt data');
				http_response_code(400);
				exit();
			}

			$data = json_decode($decrypted);
		}

		switch ($body['action']) {
			case 'orders':
				$this->load->model('openbay/etsy_order');

				$this->openbay->etsy->log('Orders action found');

				$this->model_openbay_etsy_order->inbound($data);

				break;
			case 'products';
				$this->load->model('openbay/etsy_product');

				$this->model_openbay_etsy_product->inbound($data);

				break;
		}
	}

	public function eventAddOrderHistory($order_id) {
		if (!empty($order_id)) {
			$this->load->model('openbay/etsy_order');

			$this->model_openbay_etsy_order->addOrderHistory($order_id);
		}
	}
}