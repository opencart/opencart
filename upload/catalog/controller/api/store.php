<?php
namespace Opencart\catalog\controller\api;
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

		if (isset($this->request->post['store_id'])) {
			$store_id = (string)$this->request->post['store_id'];
		} else {
			$store_id = '';
		}

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($store_id);

		if (!$store_info) {
			$json['error'] = $this->language->get('error_store');
		}

		if (!$json) {
			$this->session->data['store_id'] = $store_id;

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
