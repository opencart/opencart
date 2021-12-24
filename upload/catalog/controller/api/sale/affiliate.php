<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Affiliate extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/affiliate');

		$json = [];

		if (isset($this->request->post['affiliate_id'])) {
			$affiliate_id = (int)$this->request->post['affiliate_id'];
		} else {
			$affiliate_id = 0;
		}

		if ($affiliate_id) {
			$this->load->model('account/affiliate');

			$affiliate_info = $this->model_account_affiliate->getAffiliate($affiliate_id);

			if (!$affiliate_info) {
				$json['error'] = $this->language->get('error_affiliate');
			}
		}

		if (!$json) {
			if ($affiliate_id) {
				$this->session->data['affiliate'] = $this->request->post['affiliate'];
			} else {
				unset($this->session->data['affiliate']);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear(): void {
		unset($this->session->data['affiliate']);

		$json['success'] = $this->language->get('text_success');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}