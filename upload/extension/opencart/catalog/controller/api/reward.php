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
			$this->load->controller('api/customer');
			$this->load->controller('api/cart');
			$this->load->controller('api/payment_address');
			$this->load->controller('api/shipping_address');
			$this->load->controller('api/shipping_method.save');
			$this->load->controller('api/payment_method.save');

			$this->load->model('setting/extension');

			$extensions = $this->model_setting_extension->getExtensionsByType('total');

			foreach ($extensions as $extension) {
				if ($extension['code'] != 'reward') {
					$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
				}
			}
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
			$json['success'] = $this->language->get('text_success');

			$this->session->data['reward'] = $reward;

			if ($this->request->get['route'] == 'extension/opencart/api/reward') {
				$json['products'] = $this->load->controller('api/cart.getProducts');
				$json['totals'] = $this->load->controller('api/cart.getTotals');
				$json['shipping_required'] = $this->cart->hasShipping();
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
