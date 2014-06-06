<?php
class ControllerApiReward extends Controller {
	public function index() {
		$this->load->language('api/reward');

		$json = array();

		$points = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		if (empty($this->request->post['reward'])) {
			$json['error'] = $this->language->get('error_reward');
		}

		if ($this->request->post['reward'] > $points) {
			$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
		}

		if ($this->request->post['reward'] > $points_total) {
			$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
		}

		if (!$json) {
			$this->session->data['reward'] = abs($this->request->post['reward']);

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->response->setOutput(json_encode($json));
	}
	
	public function maximum() {
		$json = array();

		$json['maximum'] = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$json['maximum'] += $product['points'];
			}
		}

		$this->response->setOutput(json_encode($json));				
	}
	
	public function available() {
		$json = array();

		$json['points'] = $this->customer->getRewardPoints();

		$this->response->setOutput(json_encode($json));				
	}
}