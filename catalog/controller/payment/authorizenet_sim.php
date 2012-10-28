<?php
class ControllerPaymentAuthorizeNetSim extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['action'] = $this->config->get('authorizenet_sim_url');
		
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$data =& $this->data;
			
		/* 6.1 Essential Fields
		 * The following parameters are required, and validated with each request. If one
		 * is missing or the validation fails the customer will see an error page. The
		 * merchant will also receive an email explaining the problem.
		 */

		/** Payment Page ID from the Administration Console
		 *  Varies by merchant
		 *	Maximum length 20, the Payment Page ID from the Administration Console. Case-sensitive.
		 */
		$data['x_login'] = $this->config->get('authorizenet_sim_merchant');
  
		/** 
		 * 
		 * Chosen by merchant 	Can be a random number. Used in "x_fp_hash" 
		 * calculation in order to make it unique but not used otherwise. 
		 * Returned with Relay Response / Silent Post / Receipt Link. 
		 * No length restriction
		 * 
		 * @var unknown_type
		 */
		$data['x_fp_sequence'] = $this->session->data['order_id'];

		/** 
		 * 
		 * Time in seconds since January 1, 1970. UTC, Coordinated Universal Time
		 * Requests expire after 15 minutes / 900 seconds.
		 * 
		 * @var Time in seconds since January 1, 1970. UTC
		 */
		$data['x_fp_timestamp'] = time();
		
		/** 
		 * 
		 * Positive number
		 * Total dollar amount to be charged inclusive of freight and tax; Maximum Length 15
		 * 
		 * @var Positive number
		 */
		$data['x_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);	

		/** 
		 * 
		 * HMAC-MD5  hash from the merchant's transaction key and 
		 * concatenation of the values for "x_login", "x_fp_sequence", 
		 * "x_fp_timestamp", "x_amount", and (if given) "x_currency_code" 
		 * – all separated by the  "^" character. Note that if 
		 * "x_currency_code" is not present, then a "^" character is still 
		 * added. The transaction key is generated within the payment page 
		 * configuration section of the Administration console tab, 
		 * "Keys".
		 * @var String
		 */
		$data['x_fp_hash'] = null; // calculated later, once all fields are populated		

		/** 
		 * 
		 * PAYMENT_FORM Case-sensitive
		 * 
		 * Required in order to stay compatible with the Authorize.Net 
		 * protocol. 
		 * 
		 * @var String
		 */
		$data['x_show_form'] = 'PAYMENT_FORM';
		
		/* 6.2 Transaction and Display Fields */
		$mode = $this->config->get('authorizenet_sim_mode');
		if ($mode == 'live') {
			$data['x_test_request'] = 'false';
		} else {
			$data['x_test_request'] = 'true';
		}
		$data['x_type'] = 'AUTH_CAPTURE';
		$data['x_currency_code'] = $this->currency->getCode();
		
		/* 6.3 Order and Customer Detail Fields */

		/* Order Information Fields */
		$data['x_invoice_num'] = $this->session->data['order_id'];
		$data['x_description'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
		
		/* Customer Name and Billing Address Fields */
		$data['x_first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$data['x_last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$data['x_company'] = html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
		$data['x_address'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
		$data['x_city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$data['x_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		$data['x_zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		$data['x_country'] = html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8');
		$data['x_phone'] = $order_info['telephone'];
		
		/* Customer Shipping Address Fields */
		$data['x_ship_to_first_name'] = html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_last_name'] = html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_company'] = html_entity_decode($order_info['shipping_company'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_address'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_city'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_state'] = html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_zip'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_country'] = html_entity_decode($order_info['shipping_country'], ENT_QUOTES, 'UTF-8');
		
		/* Additional Customer Data Field */
		$data['x_customer_ip'] = $this->request->server['REMOTE_ADDR'];
		$data['x_email'] = $order_info['email'];
		
		/* 7 Relay Response Mode */
		$data['x_relay_response'] = 'true';
				
		// calculate this after all our fields are generated
		$data['x_fp_hash'] = $this->calculateFpHash();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/authorizenet_sim_index.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/authorizenet_sim_index.tpl';
		} else {
			$this->template = 'default/template/payment/authorizenet_sim_index.tpl';
		}	
		
		$this->render();	
	}
	
	
	/** Calculates the x_fp_hash value for transaction
	 * 
	 * HMAC-MD5 keyed by the merchant's transaction key and 
	 * concatenation of the values for "x_login", "x_fp_sequence", 
	 * "x_fp_timestamp", "x_amount", and (if given) "x_currency_code" 
	 * all separated by the  "^" character. Note that if 
	 * "x_currency_code" is not present, then a "^" character is still 
	 * added. 
	 * 
	 * The transaction key is generated within the payment page 
	 * configuration section of the Administration console tab
	 * 
	 * @return String
	 */
	private function calculateFpHash() {
		$this->load->library('hash');
		$hash = new Hash();
		
		$data = $this->data;
		
		$code = $data['x_login'] . '^' . $data['x_fp_sequence'] . '^' .
			$data['x_fp_timestamp'] . '^' . $data['x_amount'] . '^' .
			$data['x_currency_code'];
		
		$fp_hash = $hash->hmac_md5($code, 
			$this->config->get('authorizenet_sim_transaction_key') );
	
		return $fp_hash;
	}

	private function calculateResponseHash() {
		$this->load->library('hash');
		$hash = new Hash();
		
		$data = $this->request->post;
		
		$code = $this->config->get('authorizenet_sim_response_key') . 
			$data['x_login'] . $data['x_trans_id'] . $data['x_amount'];
	
		return md5($code);
	}
	
	public function callback() {
		//Transaction_Approved
		$details =& $this->request->post;
		
		// Ensure our hashes are in the same case
		$calc_hash = strtolower($this->calculateResponseHash() );
		$posted_hash = strtolower($details['x_MD5_Hash']);
		
		$data =& $this->data;
		$data['x_response_reason_text'] = $details['x_response_reason_text'];
		$data['x_response_code'] = $details['x_response_code'];
		$data['exact_ctr'] = $details['exact_ctr'];
		$data['exact_issname'] = $details['exact_issname'];
		$data['exact_issconf'] = $details['exact_issconf'];
		$data['hash_match'] = ($calc_hash == $posted_hash);
		$data['order_id'] = $details['x_invoice_num'];
		
		$data['button_confirm'] = $this->language->get('button_continue');
		$data['confirm'] = $this->url->https('checkout/success');
		
		if ($data['hash_match'] ) {
			$order_id = $data['order_id'];
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			if ($order_info) {
				if ($data['x_response_code'] == '1') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('authorizenet_sim_order_status_id'));
				} else {
					$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id') );
				}
			}
		}
		
		$this->document->breadcrumbs = array(); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	); 

		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);	
      	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/authorizenet_sim_callback.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/authorizenet_sim_callback.tpl';
		} else {
			$this->template = 'default/template/payment/authorizenet_sim_callback.tpl';
		}	

		$this->render();
	}
}
?>