<?php
class ControllerModuleReward extends Controller {
	public function index() {
		$this->language->load('module/reward');
					
		$points = $this->customer->getRewardPoints();
		
		$points_total = 0;
		
		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}
					
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $points);
		
		$this->data['text_loading'] = $this->language->get('text_loading');
	
		$this->data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);
		
		$this->data['button_reward'] = $this->language->get('button_reward');
		
		$this->data['status'] = ($points && $points_total && $this->config->get('reward_status'));
		
		if (isset($this->session->data['reward'])) {
			$this->data['reward'] = $this->session->data['reward'];
		} else {
			$this->data['reward'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/reward.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/reward.tpl';
		} else {
			$this->template = 'default/template/module/reward.tpl';
		}
					
		$this->response->setOutput($this->render());		
	}
	
	public function reward() {
		$this->language->load('voucher/reward');
		
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
				
			$this->session->data['success'] = $this->language->get('text_reward');
				
			$json['redirect'] = $this->url->link('checkout/cart');		
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}
?>