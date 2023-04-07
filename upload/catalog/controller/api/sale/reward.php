<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Reward extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/reward');

		$json = [];

		if (isset($this->request->post['reward'])) {
			$reward = abs((int)$this->request->post['reward']);
		} else {
			$reward = 0;
		}

		$available = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		if ($reward) {
			if ($reward > $available) {
				$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
			}

			if ($reward > $points_total) {
				$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
			}
		}

		if (!$json) {
			if ($reward) {
				$json['success'] = $this->language->get('text_success');

				$this->session->data['reward'] = $reward;
			} else {
				$json['success'] = $this->language->get('text_remove');

				unset($this->session->data['reward']);
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function maximum(): void {
		$this->load->language('api/sale/reward');

		$json = [];

		$json['maximum'] = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$json['maximum'] += $product['points'];
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function available(): void {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(['points' => $this->customer->getRewardPoints()]));
	}
}
