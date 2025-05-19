<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Affiliate
 *
 * Can be loaded using $this->load->controller('api/affiliate');
 *
 * @package Opencart\Catalog\Controller\Api\Sale
 */
class Affiliate extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return array<string, mixed>
	 */
	public function index(): array {
		$this->load->language('api/affiliate');

		$output = [];

		if (isset($this->request->post['affiliate_id'])) {
			$affiliate_id = (int)$this->request->post['affiliate_id'];
		} else {
			$affiliate_id = 0;
		}

		// Affiliate
		if ($affiliate_id) {
			$this->load->model('account/affiliate');

			$affiliate_info = $this->model_account_affiliate->getAffiliate($affiliate_id);

			if (!$affiliate_info) {
				$output['error'] = $this->language->get('error_affiliate');
			}
		}

		// Get Sub Total
		if (isset($this->session->data['order_id'])) {
			$subtotal = 0;

			// Order
			$this->load->model('checkout/order');

			$results = $this->model_checkout_order->getTotals($this->session->data['order_id']);

			foreach ($results as $result) {
				if ($result['code'] == 'subtotal') {
					$subtotal = $results['value'];

					break;
				}
			}

			if (!$subtotal) {
				$output['error'] = $this->language->get('error_order');
			}
		}

		if (!$output) {
			$output['success'] = $this->language->get('text_success');

			$this->session->data['affiliate_id'] = $affiliate_id;
		}

		return $output;
	}
}
