<?php
/**
* Unisender subscriber for OpenCart (ocStore) 2.3.x
*
* Main class subscribe/unsubscribe Unisender maillists
*
* @author Alexander Toporkov <toporchillo@gmail.com>
* @copyright (C) 2013- Alexander Toporkov
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*
* Version of this module: http://opencartforum.ru/files/file/1258-unisender-eksport-kontakov/
*/
class ControllerExtensionFeedUnisender extends Controller {

	public function update() {
		$newsletter = $this->customer->getNewsletter();
		$data = array('email'=>$this->customer->getEmail());
		
		$key = $this->config->get('feed_unisender_key');
		if (!$this->config->get('feed_unisender_status') || !$key) return;
	
		$field_names = array(0=>'email', 1=>'email_status');
		$dat = array(0=>array(0=>$data['email'], 1=>($newsletter ? 'active' : 'inactive')));
		
		if ($newsletter) {
			$subscribtions = $this->config->get('feed_unisender_subscribtion');
			$field_names[2] = 'email_list_ids';
			$dat[0][2] = implode(',', $subscribtions);
		}
		return $this->send($field_names, $dat);
	}

	public function subscribe_customer() {
		$customer_id = $this->session->data['customer_id'];
		$this->load->model('account/customer');
		$data = $this->model_account_customer->getCustomer($customer_id);
		$this->subscribe($data);
	}
	
	public function subscribe_guest() {
		$order_id = $this->session->data['order_id'];
		$this->load->model('checkout/order');
		$data = $this->model_checkout_order->getOrder($order_id);
		if ($data['customer_id'] > 0) return;
		$this->subscribe($data);
	}
	
	public function subscribe($data) {
		$key = $this->config->get('feed_unisender_key');
		if (!$this->config->get('feed_unisender_status') || !$key) return;
		
		$subscribtions = $this->config->get('feed_unisender_subscribtion');
		
		$field_names = array(0=>'email');
		$dat = array(0=>array(0=>$data['email']));
		$double_optin = $this->config->get('feed_unisender_ignore') ? 1 : 0;
		
		if (((isset($data['newsletter']) && $data['newsletter']) || $this->config->get('feed_unisender_ignore')) && is_array($subscribtions) && count($subscribtions) > 0) {
			$field_names[1] = 'email_request_ip';
			$dat[0][1] = $this->request->server['REMOTE_ADDR'];

			$field_names[2] = 'email_confirm_ip';
			$dat[0][2] = $this->request->server['REMOTE_ADDR'];
			
			$field_names[3] = 'email_add_time';
			$dat[0][3] = gmdate('Y-m-d h:i:s', time()-20);

			$field_names[4] = 'email_confirm_time';
			$dat[0][4] = gmdate('Y-m-d h:i:s', time()-10);
			
			$field_names[5] = 'email_list_ids';
			$dat[0][5] = implode(',', $subscribtions);
		}
		if (isset($data['telephone']) && $data['telephone']) {
			$field_names[6] = 'phone';
			$dat[0][6] = $data['telephone'];
		}
		$field_names[7] = 'Name';
		$dat[0][7] = trim($data['firstname'].' '.$data['lastname']);
				
		$res = $this->send($field_names, $dat, $double_optin);
		return $res;
	}
	
	private function send($field_names, $dat, $double_optin=0) {
		$key = $this->config->get('feed_unisender_key');
		$exp = array(
			'api_key' => $key,
			'double_optin' => $double_optin
		);
		
		foreach($field_names as $n=>$v) {
			$exp['field_names['.$n.']'] = $v;
		}
		foreach($dat[0] as $n=>$v) {
			$exp['data[0]['.$n.']'] = $v;
		}
	
        $ch = curl_init ('https://api.unisender.com/ru/api/importContacts?format=json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $exp);
        $res = curl_exec ($ch) ;
        curl_close ($ch);
		return json_decode($res);
	}
}
?>