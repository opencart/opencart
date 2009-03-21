<?php
class ControllerPaymentWorldPay extends Controller {
	protected function index() {
    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->data['action'] = 'https://select.worldpay.com/wcc/purchase';

		$this->data['merchant'] = $this->config->get('worldpay_merchant');
		$this->data['merchant'] = $this->config->get('worldpay_merchant');

/*
					<input type='hidden' name='cartId' value='".$cart_order_id."' />
					<input type='hidden' name='MC_OID' value='".$cart_order_id."' />
					<input type='hidden' name='amount' value='".$basket['grandTotal']."' />
					<input type='hidden' name='currency' value='".$config['defaultCurrency']."' />
					<input type='hidden' name='desc' value='Cart - ".$cart_order_id."' />
					<input type='hidden' name='name' value='".$ccUserData[0]['title']." ".$ccUserData[0]['firstName']." ".$ccUserData[0]['lastName']."' />";
					
					if(!empty($ccUserData[0]['add_2'])){

						$add = $ccUserData[0]['add_1'].",&#10;".$ccUserData[0]['add_2'].",&#10;".$ccUserData[0]['town'].", ".$ccUserData[0]['county'].",&#10;".countryName($ccUserData[0]['country']);
					
					} else {
						
						$add = $ccUserData[0]['add_1'].",&#10;".$ccUserData[0]['town'].",&#10;".$ccUserData[0]['county'].",&#10;".countryName($ccUserData[0]['country']);
					
					}
					
					$hiddenVars .= "<input type='hidden' name='address' value='".$add."' />
					<input type='hidden' name='postcode' value='".$ccUserData[0]['postcode']."' />
					<input type='hidden' name='country' value='".countryIso($ccUserData[0]['country'])."' />
					<input type='hidden' name='tel' value='".$ccUserData[0]['phone']."' />
					<input type='hidden' name='email' value='".$ccUserData[0]['email']."' />";
				
					$hiddenVars .= "<input type='hidden' name='testMode' value='".$module['testMode']."' />";
				
*/		


		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/protx.tpl';
		
		$this->render();		
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
	}
}
?>