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
	public function index(): array {
		$this->load->language('extension/opencart/api/reward');

		$output = [];

		if (isset($this->request->post['reward'])) {
			$reward = abs((int)$this->request->post['reward']);
		} else {
			$reward = 0;
		}

		if (!$this->config->get('total_reward_status')) {
			$output['error'] = $this->language->get('error_status');
		}

		$available = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		if ($reward > $available) {
			$output['error'] = sprintf($this->language->get('error_points'), $reward);
		}

		if ($reward > $points_total) {
			$output['error'] = sprintf($this->language->get('error_maximum'), $points_total);
		}

		if (!$output) {
			$this->session->data['reward'] = $reward;

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}

	/**
	 * Validate
	 *
	 * @return bool
	 */
	public function validate(): bool {
		if (empty($this->request->post['reward']) || (isset($this->session->data['reward']) && $this->request->post['reward'] == $this->session->data['reward'])) {
			return true;
		}

		return false;
	}
}
