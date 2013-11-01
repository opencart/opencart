<?php
class ControllerAmazonSearch extends Controller {
	public function index() {
		if ($this->config->get('amazon_status') != '1') {
			return;
		}

		$this->load->model('amazon/product');

		$logger = new Log('amazon.log');
		$logger->write('amazon/search - started');

		$token = $this->config->get('openbay_amazon_token');

		$incomingToken = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incomingToken !== $token) {
			$logger->write('amazon/search - Incorrect token: ' . $incomingToken);
			return;
		}

		$decrypted = $this->openbay->amazon->decryptArgs($this->request->post['data']);

		if (!$decrypted) {
			$logger->write('amazon/search Failed to decrypt data');
			return;
		}

		$logger->write($decrypted);

		$json = json_decode($decrypted, 1);

		$this->model_amazon_product->updateSearch($json);
	}
}
?>