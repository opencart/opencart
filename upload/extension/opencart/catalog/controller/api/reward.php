<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Api;
/**
 * Class Reward
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Api
 */
class Reward extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): void {
		$this->load->language('extension/opencart/api/reward');

		$json = [];

		if ($this->request->get['route'] == 'extension/opencart/api/reward') {
			$this->load->controller('api/order');
		}

		if (isset($this->request->post['reward'])) {
			$reward = abs((int)$this->request->post['reward']);
		} else {
			$reward = 0;
		}

		if (!$this->config->get('total_reward_status')) {
			$json['error'] = $this->language->get('error_status');
		}

		if (!$json) {
			$available = $this->customer->getRewardPoints();

			$points_total = 0;

			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}

			if ($reward > $available) {
				$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
			}

			if ($reward > $points_total) {
				$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
			}
		}

		if (!$json) {
			$this->session->data['reward'] = $reward;

			$json['success'] = $this->language->get('text_success');
		} else {
			// Store the errors to be shown on the confirm api call
			$this->session->data['error']['coupon'] = $json['error'];
		}

		if ($this->request->get['route'] == 'extension/opencart/api/reward') {
			$json['products'] = $this->load->controller('api/cart.getProducts');
			$json['totals'] = $this->load->controller('api/cart.getTotals');
			$json['shipping_required'] = $this->cart->hasShipping();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
