<?php
class ControllerAmazonusProduct extends Controller  {
	public function inbound() {
		if ($this->config->get('amazonus_status') != '1') {
			$this->response->setOutput("disabled");
			return;
		}

		ob_start();

		$this->load->library('amazonus');
		$this->load->model('amazonus/product');
		$this->load->library('log');
		$logger = new Log('amazonus_product.log');

		$logger->write("AmazonusProduct/inbound: incoming data");

		$incomingToken = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if($incomingToken != $this->config->get('openbay_amazonus_token')) {
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

		$decodedData = (array)json_decode($data);
		$logger->write("Received data: " . print_r($decodedData, true));
		$status = $decodedData['status'];

		if($status == "submit_error") {
			$message = 'Product was not submited to amazonus properly. Please try again or contact OpenBay.';
			$this->model_amazonus_product->setSubmitError($decodedData['insertion_id'], $message);
		} else {
			$status = (array)$status;
			if($status['successful'] == 1) {
				$this->model_amazonus_product->setStatus($decodedData['insertion_id'], 'ok');
				$insertionProduct = $this->model_amazonus_product->getProduct($decodedData['insertion_id']);
				$this->model_amazonus_product->linkProduct($insertionProduct['sku'], $insertionProduct['product_id'], $insertionProduct['var']);
				$this->model_amazonus_product->deleteErrors($decodedData['insertion_id']);

				$quantityData = array(
					$insertionProduct['sku'] => $this->model_amazonus_product->getProductQuantity($insertionProduct['product_id'], $insertionProduct['var'])
				);
				$logger->write('Updating quantity with data: ' . print_r($quantityData, true));
				$logger->write('Response: ' . print_r($this->openbay->amazonus->updateQuantities($quantityData), true));
			} else {
				$msg = 'Product was not accepted by amazonus. Please try again or contact OpenBay.';
				$this->model_amazonus_product->setSubmitError($decodedData['insertion_id'], $msg);

				if(isset($decodedData['error_details'])) {
					foreach($decodedData['error_details'] as $error) {
						$error = (array)$error;
						$error_data = array(
							'sku' => $error['sku'],
							'error_code' => $error['error_code'],
							'message' => $error['message'],
							'insertion_id' => $decodedData['insertion_id']
						);
						$this->model_amazonus_product->insertError($error_data);

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

		$incomingToken = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incomingToken != $this->config->get('openbay_amazonus_token')) {
			$this->response->setOutput("error 002");
			return;
		}

		$data = $this->openbay->amazonus->decryptArgs($this->request->post['data']);
		if (!$data) {
			$this->response->setOutput("error 003");
			return;
		}

		$dataXml = simplexml_load_string($data);

		if(!isset($dataXml->action)) {
			$this->response->setOutput("error 004");
			return;
		}

		$action = trim((string)$dataXml->action);

		if ($action === "get_amazonus_product") {
			if(!isset($dataXml->product_id)) {
				$this->response->setOutput("error 005");
				return;
			}
			$product_id = trim((string)$dataXml->product_id);
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
?>