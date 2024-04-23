<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Affiliate
 *
 * @package Opencart\Catalog\Controller\Api\Sale
 */
class Affiliate extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
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

		// Get subtotal
		if (isset($this->session->data['order_id'])) {
			$subtotal = 0;

			$this->load->model('checkout/order');

			$results = $this->model_checkout_order->getTotals($this->session->data['order_id']);

			foreach ($results as $result) {
				if ($result['code'] == 'subtotal') {
					$subtotal = $results['value'];

					break;
				}
			}

			if (!$subtotal) {
				$json['error'] = $this->language->get('error_order');
			}
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$this->session->data['affiliate_id'] = $affiliate_id;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Remove
	 *
	 * @return void
	 */
	public function remove(): void {
		$this->load->language('api/sale/affiliate');

		$json['success'] = $this->language->get('text_remove');

		unset($this->session->data['affiliate_id']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
