<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Affiliate extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/affiliate');

		$json = [];

		if (isset($this->request->post['affiliate_id'])) {
			$affiliate_id = (string)$this->request->post['affiliate_id'];
		} else {
			$affiliate_id = '';
		}

		$this->load->model('account/affiliate');

		$affiliate_info = $this->model_account_affiliate->getAffiliate($affiliate_id);

		if (!$affiliate_info) {
			$json['error'] = $this->language->get('error_affiliate');
		}

		if (!$json) {
			$this->session->data['affiliate'] = $this->request->post['affiliate'];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}