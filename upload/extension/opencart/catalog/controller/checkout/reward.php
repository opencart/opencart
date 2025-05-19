<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Checkout;
/**
 * Class Reward
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Checkout
 */
class Reward extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		if ($this->config->get('total_reward_status')) {
			$available = $this->customer->getRewardPoints();

			$points_total = 0;

			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}

			if ($available && $points_total) {
				$this->load->language('extension/opencart/checkout/reward');

				$data['heading_title'] = sprintf($this->language->get('heading_title'), $available);

				$data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);

				$data['save'] = $this->url->link('extension/opencart/checkout/reward.save', 'language=' . $this->config->get('config_language'), true);
				$data['list'] = $this->url->link('checkout/cart.list', 'language=' . $this->config->get('config_language'), true);

				if (isset($this->session->data['reward'])) {
					$data['reward'] = $this->session->data['reward'];
				} else {
					$data['reward'] = '';
				}

				return $this->load->view('extension/opencart/checkout/reward', $data);
			}
		}

		return '';
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/checkout/reward');

		$json = [];

		if (isset($this->request->post['reward'])) {
			$reward = abs((int)$this->request->post['reward']);
		} else {
			$reward = 0;
		}

		if (!$this->config->get('total_reward_status')) {
			$json['error'] = $this->language->get('error_status');
		}

		$available = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		if ($reward > $available) {
			$json['error'] = sprintf($this->language->get('error_points'), $reward);
		}

		if ($reward > $points_total) {
			$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			if ($reward) {
				$this->session->data['reward'] = $reward;
			} else {
				unset($this->session->data['reward']);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
