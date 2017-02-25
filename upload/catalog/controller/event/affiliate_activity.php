<?php
class ControllerEventAffiliateActivity extends Controller {
	// model/affiliate/affiliate/addAffiliate/after
	public function addAffiliate(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('affiliate/activity');

			$activity_data = array(
				'affiliate_id' => $output,
				'name'         => $args[0]['firstname'] . ' ' . $args[0]['lastname']
			);

			$this->model_affiliate_activity->addActivity('register', $activity_data);
		}
	}
	
	// model/affiliate/affiliate/editAffiliate/after
	public function editAffiliate(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('affiliate/activity');

			$activity_data = array(
				'affiliate_id' => $this->affiliate->getId(),
				'name'         => $this->affiliate->getFirstName() . ' ' . $this->affiliate->getLastName()
			);

			$this->model_affiliate_activity->addActivity('edit', $activity_data);
		}
	}
	
	// model/affiliate/affiliate/editPassword/after
	public function editPassword(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('affiliate/activity');
			
			if ($this->affiliate->isLogged()) {
				$activity_data = array(
					'affiliate_id' => $this->affiliate->getId(),
					'name'         => $this->affiliate->getFirstName() . ' ' . $this->affiliate->getLastName()
				);

				$this->model_affiliate_activity->addActivity('password', $activity_data);
			} else {
				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByEmail($args[0]);
		
				if ($affiliate_info) {
					$activity_data = array(
						'affiliate_id' => $affiliate_info['affiliate_id'],
						'name'         => $affiliate_info['firstname'] . ' ' . $affiliate_info['lastname']
					);
	
					$this->model_affiliate_activity->addActivity('reset', $activity_data);
				}
			}	
		}
	}
	
	// model/affiliate/affiliate/editPayment
	public function editPayment(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('affiliate/activity');

			$activity_data = array(
				'affiliate_id' => $this->affiliate->getId(),
				'name'         => $this->affiliate->getFirstName() . ' ' . $this->affiliate->getLastName()
			);

			$this->model_affiliate_activity->addActivity('payment', $activity_data);
		}
	}
	
	// model/affiliate/affiliate/deleteLoginAttempts
	public function login(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('affiliate/activity');

			$activity_data = array(
				'affiliate_id' => $this->affiliate->getId(),
				'name'         => $this->affiliate->getFirstName() . ' ' . $this->affiliate->getLastName()
			);

			$this->model_affiliate_activity->addActivity('login', $activity_data);
		}	
	}
	 
	// model/affiliate/affiliate/editCode
	public  function forgotten(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity') && $this->request->get['route'] == 'affiliate/forgotten') {
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByEmail($args[0]);

			if ($affiliate_info) {
				$this->load->model('affiliate/activity');

				$activity_data = array(
					'affiliate_id' => $affiliate_info['affiliate_id'],
					'name'         => $affiliate_info['firstname'] . ' ' . $affiliate_info['lastname']
				);

				$this->model_affiliate_activity->addActivity('forgotten', $activity_data);
			}
		}	
	}
}