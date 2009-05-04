<?php
class ControllerPaymentMoneybookers extends Controller {
	var $order_info;
	var $mb_products_ordered;
	var $mb_id;
	
	
	protected function index() {
		$this->load->model('checkout/order');
		$this->load->language('payment/moneybookers');
		
		$this->order_info							= $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->getProducts();
		$this->get_mb_id();

    	$this->data['button_continue']				= $this->language->get('button_continue');
		$this->data['button_back']					= $this->language->get('button_back');
        $this->data['action']						= 'https://www.moneybookers.com/app/payment.pl' . $this->mb_id;

		$this->data['storelogourl']					= HTTP_SERVER . 'catalog/view/theme/default/image/logo.png';
		$this->data['language']						= $this->language->get('code');					
		$this->data['recipient_desc']				= $this->config->get('config_store');
		$this->data['detail1_desc']					= $this->language->get('detail1_desc');
		$this->data['detail1_text']					= $this->mb_products_ordered;
		$this->data['trans_id']						= $this->session->data['order_id'];
        $this->data['mb_email']						= $this->config->get('moneybookers_email');
        $this->data['amount']						= $this->currency->format($this->order_info['total'], $this->order_info['currency'], $this->order_info['value'], FALSE);
        $this->data['currency']						= $this->order_info['currency'];
        $this->data['order_id']						= $this->session->data['order_id'];
        $this->data['cust_firstname']				= $this->order_info['payment_firstname'];
        $this->data['cust_lastname']				= $this->order_info['payment_lastname'];
        $this->data['cust_address1']				= $this->order_info['shipping_address_1'];
        $this->data['cust_address2']				= $this->order_info['shipping_address_2'];
        $this->data['cust_postcode']				= $this->order_info['shipping_postcode'];
        $this->data['cust_city']					= $this->order_info['shipping_city'];
        $this->data['cust_zone']					= $this->order_info['shipping_zone'];
		$this->data['cust_country']					= $this->isoCode3($this->order_info['payment_country']);
        $this->data['cust_email']					= $this->order_info['email'];

        $this->data['return_url']					= $this->url->https('checkout/success');
        $this->data['cancel_url']					= $this->url->https('checkout/payment');
        $this->data['back']							= $this->data['cancel_url'];
        $this->data['mb_note']						= $this->config->get('moneybookers_custnote');
        
		
		$this->id									= 'payment';
		$this->template								= $this->config->get('config_template') . 'payment/moneybookers.tpl';
		$this->render();
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
	}
	
	private function isoCode3($country_name) {
		$iso_code_3 = $this->db->query("SELECT * FROM `country` WHERE `name` = '". $country_name . "'");
		return $iso_code_3->row['iso_code_3'];
	}	
	
	private function getProducts() {
		foreach ($this->cart->getProducts() as $product) {
    		$this->mb_products_ordered .= $product['quantity'] . ' x ' . $product['name'] . ' ';
    	}
	}
	
	private function get_mb_id() {
		$this->mb_id	= $this->config->get('entry_mb_id');
		if ($this->mb_id == "") {
			$this->mb_id = '?rid=2198937';
		} else {
			$this->mb_id = '?rid=' . $this->mb_id;
		}
	}
}
?>