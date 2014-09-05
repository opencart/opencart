<?php
class ControllerAmazonusSearch extends Controller {
	public function index() {
		if ($this->config->get('amazonus_status') != '1') {
			return;
		}

		$this->load->model('openbay/amazonus_product');

		$logger = new Log('amazonus.log');
		$logger->write('amazonus/search - started');

		$token = $this->config->get('openbay_amazonus_token');

		$incoming_token = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incoming_token !== $token) {
			$logger->write('amazonus/search - Incorrect token: ' . $incoming_token);
			return;
		}

		$decrypted = $this->openbay->amazonus->decryptArgs($this->request->post['data']);

		if (!$decrypted) {
			$logger->write('amazonus/search Failed to decrypt data');
			return;
		}

		$logger->write($decrypted);

		$json = json_decode($decrypted, 1);

		$this->model_openbay_amazonus_product->updateSearch($json);
	}
}