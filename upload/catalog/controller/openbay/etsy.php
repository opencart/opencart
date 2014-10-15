<?php
class ControllerOpenbayEtsy extends Controller {
	public function inbound() {
		if ($this->config->get('etsy_status') != '1') {
			$this->openbay->etsy->log('etsy/inbound - module inactive');
			return;
		}

		$body = $this->request->post;

		if (!isset($body['action']) || !isset($body['auth'])) {
			$this->openbay->etsy->log('etsy/inbound - action or auth data not set');
			return;
		}

		$token = $this->config->get('etsy_token');
		$secret = $this->config->get('etsy_enc1');

		$incoming_token = isset($body['auth']['token']) ? $body['auth']['token'] : '';
		$incoming_secret = isset($body['auth']['secret']) ? $body['auth']['secret'] : '';

		if ($incoming_token !== $token || $incoming_secret !== $secret) {
			$this->openbay->etsy->log('etsy/inbound - Auth failed: ' . $incoming_token . '/' . $incoming_secret);
			return;
		}

		$data = array();

		if (isset($body['data']) && !empty($body['data'])) {
			$decrypted = $this->openbay->etsy->decryptArgs($body['data']);

			if (!$decrypted) {
				$this->openbay->etsy->log('etsy/inbound Failed to decrypt data');
				return;
			}

			$data = json_decode($decrypted);
		}

		//$this->openbay->etsy->log(print_r($data, true));

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

	public function eventAddOrder($order_id) {
		$this->openbay->etsy->addOrder($order_id);
	}
}