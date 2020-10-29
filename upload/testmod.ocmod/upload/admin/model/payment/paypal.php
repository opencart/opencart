<?php
class ModelExtensionPaymentPayPal extends Model {
		
	public function configureSmartButton() {
		$this->load->model('user/user_group');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `code` = 'paypal_smart_button'");
        		
        if (empty($query->row)) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = 'module', `code` = 'paypal_smart_button'");

            $user_group_id = $this->user->getGroupId();
       									
			$this->model_user_user_group->addPermission($user_group_id, 'access', 'extension/module/paypal_smart_button');
			$this->model_user_user_group->addPermission($user_group_id, 'modify', 'extension/module/paypal_smart_button');
			
			$this->load->controller('extension/module/paypal_smart_button/install');
        }
	}
	
	public function log($data, $title = null) {
		if ($this->config->get('payment_paypal_debug')) {
			$log = new Log('paypal.log');
			$log->write('PayPal debug (' . $title . '): ' . json_encode($data));
		}
	}
}
