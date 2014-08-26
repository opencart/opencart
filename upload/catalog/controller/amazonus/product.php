<?php
class ControllerAmazonusProduct extends Controller  {
	public function inbound() {
		if ($this->config->get('amazonus_status') != '1') {
			$this->response->setOutput("disabled");
			return;
		}

		ob_start();

		$this->load->library('amazonus');
		$this->load->model('openbay/amazonus_product');
		$this->load->library('log');
		$logger = new Log('amazonus_product.log');

		$logger->write("AmazonusProduct/inbound: incoming data");

		$incoming_token = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if($incoming_token != $this->config->get('openbay_amazonus_token')) {
			$logger->write("Error - Incorrect token: " . $this->request->post['token']);
			ob_get_clean();
			$this->response->setOutput("tokens did not match");
			return;
		}

		$data = $this->openbay->amazonus->decryptArgs($this->request->post['data']);
		if(!$data) {
			$logger->write("Error - Failed to decrypt received data.");
			ob_get_clean();
			$this->response->setOutput("failed to decrypt");
			return;
		}

		$decoded_data = (array)json_decode($data);
		$logger->write("Received data: " . print_r($decoded_data, true));
		$status = $decoded_data['status'];

		if($status == "submit_error") {
			$message = 'Product was not submited to amazonus properly. Please try again or contact OpenBay.';
			$this->model_openbay_amazonus_product->setSubmitError($decoded_data['insertion_id'], $message);
		} else {
			$status = (array)$status;
			if($status['successful'] == 1) {
				$this->model_openbay_amazonus_product->setStatus($decoded_data['insertion_id'], 'ok');
				$insertion_product = $this->model_openbay_amazonus_product->getProduct($decoded_data['insertion_id']);
				$this->model_openbay_amazonus_product->linkProduct($insertion_product['sku'], $insertion_product['product_id'], $insertion_product['var']);
				$this->model_openbay_amazonus_product->deleteErrors($decoded_data['insertion_id']);

				$quantity_data = array(
					$insertion_product['sku'] => $this->model_openbay_amazonus_product->getProductQuantity($insertion_product['product_id'], $insertion_product['var'])
				);
				$logger->write('Updating quantity with data: ' . print_r($quantity_data, true));
				$logger->write('Response: ' . print_r($this->openbay->amazonus->updateQuantities($quantity_data), true));
			} else {
				$msg = 'Product was not accepted by amazonus. Please try again or contact OpenBay.';
				$this->model_openbay_amazonus_product->setSubmitError($decoded_data['insertion_id'], $msg);

				if(isset($decoded_data['error_details'])) {
					foreach($decoded_data['error_details'] as $error) {
						$error = (array)$error;
						$error_data = array(
							'sku' => $error['sku'],
							'error_code' => $error['error_code'],
							'message' => $error['message'],
							'insertion_id' => $decoded_data['insertion_id']
						);
						$this->model_openbay_amazonus_product->insertError($error_data);

					}
				}
			}
		}

		$logger->write("Data processed successfully.");
		ob_get_clean();
		$this->response->setOutput("ok");
	}

	public function dev() {
		if ($this->config->get('amazonus_status') != '1') {
			$this->response->setOutput("error 001");
			return;
		}

		$incoming_token = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incoming_token != $this->config->get('openbay_amazonus_token')) {
			$this->response->setOutput("error 002");
			return;
		}

		$data = $this->openbay->amazonus->decryptArgs($this->request->post['data']);
		if (!$data) {
			$this->response->setOutput("error 003");
			return;
		}

		$data_xml = simplexml_load_string($data);

		if(!isset($data_xml->action)) {
			$this->response->setOutput("error 004");
			return;
		}

		$action = trim((string)$data_xml->action);

		if ($action === "get_amazonus_product") {
			if(!isset($data_xml->product_id)) {
				$this->response->setOutput("error 005");
				return;
			}
			$product_id = trim((string)$data_xml->product_id);
			if ($product_id === "all") {
				$all_rows = $this->db->query("
					SELECT * FROM `" . DB_PREFIX . "amazonus_product`
				")->rows;

				$response = array();
				foreach ($all_rows as $row) {
					unset($row['data']);
					$response[] = $row;
				}

				$this->response->setOutput(print_r($response, true));
				return;
			} else {
				$response = $this->db->query("
					SELECT * FROM `" . DB_PREFIX . "amazonus_product`
					WHERE `product_id` = '" . (int)$product_id . "'
				")->rows;

				$this->response->setOutput(print_r($response, true));
				return;
			}
		} else {
			$this->response->setOutput("error 999");
			return;
		}
	}
}