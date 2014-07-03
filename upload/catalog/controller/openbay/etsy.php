<?php
class ControllerOpenbayEtsy extends Controller {
	public function orders() {
		if ($this->config->get('etsy_status') != '1') {
			return;
		}

		$token = $this->config->get('openbay_etsy_token');

		$incoming_token = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incoming_token !== $token) {
			$this->openbay->etsy->log('etsy/order - Incorrect token: ' . $incoming_token);
			return;
		}

		$decrypted = $this->openbay->etsy->decryptArgs($this->request->post['data']);

		if (!$decrypted) {
			$this->openbay->etsy->log('amazon/order Failed to decrypt data');
			return;
		}

		$data = json_decode($decrypted);

		$this->openbay->etsy->log($decrypted);
	}
}