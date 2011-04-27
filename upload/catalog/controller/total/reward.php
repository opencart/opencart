<?php 
class ControllerTotalReward extends Controller {
	public function index() {
		$points = $this->customer->getRewardPoints();
		
		$points_total = 0;
		
		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}	
						
		if ($points && $points_total && $this->config->get('reward_status')) {
			$this->language->load('total/reward');
			
			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $points);
			
			$this->data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);
			
			$this->data['button_reward'] = $this->language->get('button_reward');
				
			if (isset($this->session->data['reward'])) {
				$this->data['reward'] = $this->session->data['reward'];
			} else {
				$this->data['reward'] = '';
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/reward.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/total/reward.tpl';
			} else {
				$this->template = 'default/template/total/reward.tpl';
			}
						
			$this->render();	
		}
  	}
		
	public function calculate() {
		$this->language->load('total/reward');
		
		$json = array();
		
		if (isset($this->request->post['reward'])) {
			if (!$this->request->post['reward']) {
				$json['error'] = $this->language->get('error_empty');
			}
			
			$points = $this->customer->getRewardPoints();
			
			if ($this->request->post['reward'] > $points) {
				$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
			}
			
			$points_total = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}				
			
			if ($this->request->post['reward'] > $points_total) {
				$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
			}
			
			if (!isset($json['error'])) {			
				$this->session->data['reward'] = $this->request->post['reward'];
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
	}
}
?>