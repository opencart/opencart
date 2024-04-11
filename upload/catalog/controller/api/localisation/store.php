<?php
namespace Opencart\Catalog\Controller\Api\Localisation;
/**
 * Class Store
 *
 * @package Opencart\Catalog\Controller\Api\Localisation
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('api/localisation/store');

		$json = [];

		if (isset($this->request->post['store'])) {
			$store = (string)$this->request->post['store'];
		} else {
			$store = '';
		}

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStoreByCode($store);

		if (!$store_info) {
			$json['error'] = $this->language->get('error_store');
		}

		if (!$json) {
			$this->session->data['store'] = $store;

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
