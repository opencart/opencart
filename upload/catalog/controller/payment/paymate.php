<?php
class ControllerPaymentPaymate extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['mid'] = $this->config->get('paymate_username');

		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		
		$this->data['return'] = $this->url->https('payment/paymate/callback&oid=' . base64_encode($encryption->encrypt($order_info['order_id'])) . '&conf=' . base64_encode($encryption->encrypt($order_info['payment_firstname'] . $order_info['payment_lastname'])));

		if($this->config->get('paymate_include_order')) {
			$this->data['ref'] = html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8') . " (#" . $order_info['order_id'] . ")";
		} else {
			$this->data['ref'] = html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8');
		}

		$paymate_cur = array(
			'AUD',
			'NZD',
			'USD',
			'EUR',
			'GBP'
		);

		if(in_array(strtoupper($order_info['currency']), $paymate_cur)) {
			$this->data['currency'] = $order_info['currency'];
			$this->data['amt'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE); 
		} else {
			//The user doesn't have a supported currency selected, we can still see if there is a supported currency to convert to
			for($findcur = 0; $findcur < sizeof($paymate_cur); $findcur++) {
				if($this->currency->getValue($paymate_cur[$findcur])) {
					$this->data['currency'] = $paymate_cur[$findcur];
					$this->data['amt'] = $this->currency->format($order_info['total'],$paymate_cur[$findcur],"",FALSE);
					break;
				} elseif($findcur == (sizeof($paymate_cur) - 1)){
					//We haven't found a supported currency, we are falling back to AUD unconverted (Paymate default)
					$this->data['currency'] = "AUD";
					$this->data['amt'] = $order_info['total'];
				}
			}
		}

		$this->data['pmt_contact_firstname'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$this->data['pmt_contact_surname'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$this->data['pmt_contact_phone'] = $order_info['telephone'];
		$this->data['pmt_sender_email'] = $order_info['email'];
		$this->data['regindi_address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_sub'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_pcode'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		
		//Needed to get the iso_code
                $payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
                $this->data['pmt_country'] = $payment_address['iso_code_2'];

		$this->data['action'] = "https://www.paymate.com/PayMate/ExpressPayment";

		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paymate.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/paymate.tpl';
		} else {
			$this->template = 'default/template/payment/paymate.tpl';
		}	
		
		$this->render();
	}
	
	public function callback() {
	 	$this->load->language('payment/paymate');
		$error = "";

		if(isset($this->request->post['responseCode'])) {
			if($this->request->post['responseCode'] == "PA" || $this->request->post['responseCode'] == "PP") {
				if(isset($this->request->get['oid']) && isset($this->request->get['conf'])) {
					//Load this to decypt the vars passed as 'oid' and 'conf'
					$this->load->library('encryption');
					
					$encryption = new Encryption($this->config->get('config_encryption'));

					$order_id = $encryption->decrypt(base64_decode($this->request->get['oid']));

					$this->load->model('checkout/order');
					
					$order_info = $this->model_checkout_order->getOrder($order_id);

					if((isset($order_info['payment_firstname']) && isset($order_info['payment_lastname'])) && strcmp($encryption->decrypt(base64_decode($this->request->get['conf'])),$order_info['payment_firstname'] . $order_info['payment_lastname']) == 0) {
						$this->model_checkout_order->confirm($order_id,$this->config->get('paymate_order_status_id'));
					} else {
						//Couldn't find/verify the order, fail
						$error = $this->language->get('text_unable');
					}
				} else {
					//No confirmation, fail
					$error = $this->language->get('text_unable');
				}
			} else {
				//Transaction declined 
				$error = $this->language->get('text_declined'); 
			}
		} else {
			//No response code, fail
			$error = $this->language->get('text_unable');
		}

		if ($error != '') {
			//Construct a failure page using the success template
			$this->data['heading_title'] = $this->language->get('text_failed');
			$this->data['text_message'] = sprintf($this->language->get('text_failed_message'), $error, $this->url->http('information/contact'));
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['continue'] = $this->url->http('common/home');
			
			$this->template = $this->config->get('config_template') . 'common/success.tpl';

			$this->response->setOutput($this->render(TRUE));
		} else {
			//Use the success page that ships with Opencart to save on code
			header("Location: " . $this->url->https('checkout/success'));
		}
	}
}
?>
