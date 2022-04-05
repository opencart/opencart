<?php
namespace Opencart\Catalog\Controller\Api\Localisation;
class Store extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/localisation/store');

		$json = [];

		if (isset($this->request->post['store_id'])) {
			$store_id = (int)$this->request->post['store_id'];
		} else {
			$store_id = 0;
		}

		if ($store_id) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($store_id);

			if (!$store_info) {
				$json['error'] = $this->language->get('error_store');
			}
		}

		if (!$json) {
			$this->session->data['store_id'] = $store_id;

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
